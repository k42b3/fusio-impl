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

namespace Fusio\Impl\Service\Health;

/**
 * CheckResult
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CheckResult
{
    private $checks = [];

    /**
     * @return boolean
     */
    public function isHealthy()
    {
        $count = 0;
        foreach ($this->checks as $check) {
            if ($check['healthy'] === true) {
                $count++;
            }
        }
        
        return count($this->checks) === $count;
    }

    /**
     * @return array
     */
    public function getChecks()
    {
        return $this->checks;
    }

    /**
     * @param string $name
     * @param boolean $healthy
     * @param string|null $error
     */
    public function add($name, $healthy, $error = null)
    {
        $check = [
            'healthy' => $healthy,
        ];

        if ($error !== null) {
            $check['error'] = $error;
        }

        $this->checks[$name] = $check;
    }
}
