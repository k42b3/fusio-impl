<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Impl\Service\System\Deploy;

use RuntimeException;

/**
 * EnvProperties
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class EnvProperties
{
    /**
     * @param string $data
     * @return string
     */
    public static function replace($data)
    {
        $vars = [];

        // dir properties
        $vars['dir'] = [
            'cache' => PSX_PATH_CACHE,
            'src'   => PSX_PATH_LIBRARY,
            'temp'  => sys_get_temp_dir(),
        ];

        // env properties
        $vars['env'] = [];
        foreach ($_SERVER as $key => $value) {
            if (is_scalar($value)) {
                $vars['env'][strtolower($key)] = $value;
            }
        }

        foreach ($vars as $type => $properties) {
            $search  = [];
            $replace = [];
            foreach ($properties as $key => $value) {
                $search[]  = '${' . $type . '.' . $key . '}';
                $replace[] = is_string($value) ? trim(json_encode($value), '"') : $value;
            }

            $data = str_replace($search, $replace, $data);

            // check whether we have variables which were not replaced
            preg_match('/\$\{' . $type . '\.([0-9A-z_]+)\}/', $data, $matches);
            if (isset($matches[0])) {
                throw new RuntimeException('Usage of unknown property ' . $matches[0]);
            }
        }

        return $data;
    }
}