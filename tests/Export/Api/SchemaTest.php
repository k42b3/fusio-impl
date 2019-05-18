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

namespace Fusio\Impl\Tests\Export\Api;

use Fusio\Impl\Tests\Fixture;
use PSX\Framework\Test\ControllerDbTestCase;
use PSX\Framework\Test\Environment;

/**
 * SchemaTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class SchemaTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testDocumentation()
    {
        $response = $this->sendRequest('/doc/*/export/schema/Entry-Schema', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "path": "\/export\/schema\/:name",
    "version": "*",
    "status": 1,
    "description": null,
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "definitions": {
            "path-template": {
                "type": "object",
                "title": "path",
                "properties": {
                    "name": {
                        "type": "string"
                    }
                }
            },
            "Export_Schema_JsonSchema": {
                "type": "object",
                "title": "Export Schema JsonSchema",
                "description": "Contains a JsonSchema"
            },
            "Export_Schema_Form": {
                "type": "object",
                "title": "Export Schema Form",
                "description": "Contains a ui vocabulary to augment the request JsonSchema"
            },
            "Export_Schema": {
                "type": "object",
                "title": "Export Schema",
                "properties": {
                    "schema": {
                        "$ref": "#\/definitions\/Export_Schema_JsonSchema"
                    },
                    "form": {
                        "$ref": "#\/definitions\/Export_Schema_Form"
                    }
                }
            },
            "GET-200-response": {
                "$ref": "#\/definitions\/Export_Schema"
            }
        }
    },
    "pathParameters": "#\/definitions\/path-template",
    "methods": {
        "GET": {
            "responses": {
                "200": "#\/definitions\/GET-200-response"
            }
        }
    },
    "links": [
        {
            "rel": "openapi",
            "href": "\/export\/openapi\/*\/export\/schema\/:name"
        },
        {
            "rel": "swagger",
            "href": "\/export\/swagger\/*\/export\/schema\/:name"
        },
        {
            "rel": "raml",
            "href": "\/export\/raml\/*\/export\/schema\/:name"
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('/export/schema/Entry-Schema', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "type": "object",
        "title": "entry",
        "properties": {
            "id": {
                "type": "integer"
            },
            "title": {
                "type": "string"
            },
            "content": {
                "type": "string"
            },
            "date": {
                "type": "string",
                "format": "date-time"
            }
        }
    },
    "form": {
        "title": {
            "ui:autofocus": true,
            "ui:emptyValue": ""
        },
        "content": {
            "ui:widget": "textarea"
        },
        "date": {
            "ui:widget": "alt-datetime"
        }
    }
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testGetWithId()
    {
        $response = $this->sendRequest('/export/schema/3', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "type": "object",
        "title": "entry",
        "properties": {
            "id": {
                "type": "integer"
            },
            "title": {
                "type": "string"
            },
            "content": {
                "type": "string"
            },
            "date": {
                "type": "string",
                "format": "date-time"
            }
        }
    },
    "form": {
        "title": {
            "ui:autofocus": true,
            "ui:emptyValue": ""
        },
        "content": {
            "ui:widget": "textarea"
        },
        "date": {
            "ui:widget": "alt-datetime"
        }
    }
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testGetNotFound()
    {
        Environment::getContainer()->get('config')->set('psx_debug', false);

        $response = $this->sendRequest('/export/schema/not_available', 'GET', array(
            'User-Agent' => 'Fusio TestCase',
        ));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "Could not find schema"
}
JSON;

        $this->assertEquals(404, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('/export/schema/Entry-Schema', 'POST', array(
            'User-Agent' => 'Fusio TestCase',
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testPut()
    {
        $response = $this->sendRequest('/export/schema/Entry-Schema', 'PUT', array(
            'User-Agent' => 'Fusio TestCase',
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('/export/schema/Entry-Schema', 'DELETE', array(
            'User-Agent' => 'Fusio TestCase',
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}
