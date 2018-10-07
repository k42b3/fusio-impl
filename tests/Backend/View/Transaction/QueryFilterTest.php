<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2018 Christoph Kappestein <christoph.kappestein@gmail.com>
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

namespace Fusio\Impl\Tests\Backend\View\Transaction;

use Fusio\Impl\Backend\View\Transaction\QueryFilter;

/**
 * QueryFilterTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class QueryFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $filter = QueryFilter::create([
            'from'     => '2015-08-20',
            'to'       => '2015-08-30',
            'planId'   => 1,
            'userId'   => 1,
            'appId'    => 1,
            'status'   => 1,
            'provider' => 'paypal',
        ]);

        $this->assertEquals('2015-08-20', $filter->getFrom()->format('Y-m-d'));
        $this->assertEquals('2015-08-30', $filter->getTo()->format('Y-m-d'));
        $this->assertEquals(1, $filter->getPlanId());
        $this->assertEquals(1, $filter->getUserId());
        $this->assertEquals(1, $filter->getAppId());
        $this->assertEquals(1, $filter->getStatus());
        $this->assertEquals('paypal', $filter->getProvider());

        $condition = $filter->getCondition();

        $this->assertEquals('WHERE (insert_date >= ? AND insert_date <= ? AND plan_id = ? AND user_id = ? AND app_id = ? AND status = ? AND provider LIKE ?)', $condition->getStatment());
        $this->assertEquals([
            '2015-08-20 00:00:00',
            '2015-08-30 23:59:59',
            1,
            1,
            1,
            1,
            'paypal',
        ], $condition->getValues());
    }
}