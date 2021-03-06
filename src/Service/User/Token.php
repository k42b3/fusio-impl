<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl\Service\User;

use Firebase\JWT\JWT;
use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Table;
use PSX\Framework\Config\Config;
use PSX\Http\Exception as StatusCode;

/**
 * Token
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Token
{
    /**
     * @var Table\User
     */
    private $userTable;

    /**
     * @var Config
     */
    private $psxConfig;

    public function __construct(Table\User $userTable, Config $psxConfig)
    {
        $this->userTable = $userTable;
        $this->psxConfig = $psxConfig;
    }

    /**
     * Returns a user for the provided one time token. Note we delete the token
     * in case we can return a valid user
     * 
     * @param string $token
     * @return int
     */
    public function getUser(string $token)
    {
        try {
            JWT::decode($token, $this->psxConfig->get('fusio_project_key'), ['HS256']);
        } catch (\RuntimeException $e) {
            throw new StatusCode\BadRequestException('Invalid token provided');
        }

        $user = $this->userTable->getOneByToken($token);
        if (empty($user)) {
            throw new StatusCode\BadRequestException('Could not find user for token');
        }

        $this->resetToken($user['id']);

        return $user['id'];
    }

    /**
     * Generates a one time token for the user and assigns the token to the user
     *
     * @param int $userId
     * @return string
     */
    public function generateToken($userId): string
    {
        $payload = [
            'exp' => time() + (60 * 60),
            'jti' => TokenGenerator::generateCode(),
        ];

        $token = JWT::encode($payload, $this->psxConfig->get('fusio_project_key'), 'HS256');

        $this->userTable->update([
            'id'    => $userId,
            'token' => $token
        ]);

        return $token;
    }

    /**
     * Removes any token from the provided user
     * 
     * @param int $userId
     */
    public function resetToken($userId)
    {
        $this->userTable->update([
            'id'    => $userId,
            'token' => ''
        ]);
    }
}
