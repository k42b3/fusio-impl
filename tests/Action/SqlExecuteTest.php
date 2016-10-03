<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Impl\Tests\Action;

use Fusio\Engine\ResponseInterface;
use Fusio\Impl\Action\SqlExecute;
use Fusio\Impl\App;
use Fusio\Engine\Form\Builder;
use Fusio\Engine\Form\Container;
use Fusio\Engine\Test\EngineTestCaseTrait;
use Fusio\Impl\Tests\DbTestCase;
use PSX\Framework\Test\Environment;
use PSX\Record\Record;

/**
 * SqlExecuteTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SqlExecuteTest extends DbTestCase
{
    use EngineTestCaseTrait;

    public function testHandle()
    {
        $action = new SqlExecute();
        $action->setConnection(Environment::getService('connection'));
        $action->setConnector(Environment::getService('connector'));
        $action->setTemplateFactory(Environment::getService('template_factory'));
        $action->setResponse(Environment::getService('response'));

        $parameters = $this->getParameters([
            'connection' => 1,
            'sql'        => 'INSERT INTO app_news (title, content, date) VALUES ({{ body.get("title")|prepare }}, {{ body.get("content")|prepare }}, {{ "2015-02-27 19:59:15"|prepare }})',
        ]);

        $body = Record::fromArray([
            'title'   => 'lorem',
            'content' => 'ipsum'
        ]);

        $response = $action->handle($this->getRequest('POST', [], [], [], $body), $parameters, $this->getContext());

        $body = [];
        $body['success'] = true;
        $body['message'] = 'Execution was successful';

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $response->getHeaders());
        $this->assertEquals($body, $response->getBody());

        $row    = Environment::getService('connection')->fetchAssoc('SELECT * FROM app_news ORDER BY id DESC');
        $expect = [
            'id'      => 3,
            'title'   => 'lorem',
            'content' => 'ipsum',
            'date'    => '2015-02-27 19:59:15',
        ];

        $this->assertEquals($expect, $row);
    }

    public function testGetForm()
    {
        $action  = new SqlExecute();
        $builder = new Builder();
        $factory = Environment::getService('form_element_factory');

        $action->configure($builder, $factory);

        $this->assertInstanceOf(Container::class, $builder->getForm());
    }
}
