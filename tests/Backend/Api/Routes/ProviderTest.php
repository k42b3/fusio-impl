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

namespace Fusio\Impl\Tests\Backend\Api\Routes;

use Fusio\Impl\Controller\SchemaApiController;
use Fusio\Impl\Table\Routes as TableRoutes;
use Fusio\Impl\Tests\Fixture;
use PSX\Api\Resource;
use PSX\Framework\Test\ControllerDbTestCase;
use PSX\Framework\Test\Environment;

/**
 * ProviderTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class ProviderTest extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getDataSet();
    }

    public function testDocumentation()
    {
        $response = $this->sendRequest('/doc/*/backend/routes/provider/testprovider', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "path": "\/backend\/routes\/provider\/:provider",
    "version": "*",
    "status": 1,
    "description": null,
    "schema": {
        "$schema": "http:\/\/json-schema.org\/draft-04\/schema#",
        "id": "urn:schema.phpsx.org#",
        "definitions": {
            "GET-query": {
                "type": "object",
                "title": "GetQuery",
                "properties": {
                    "class": {
                        "type": "string"
                    }
                }
            },
            "Input": {
                "type": "object",
                "title": "input",
                "properties": {
                    "element": {
                        "type": "string"
                    },
                    "name": {
                        "type": "string"
                    },
                    "title": {
                        "type": "string"
                    },
                    "help": {
                        "type": "string"
                    },
                    "type": {
                        "type": "string"
                    }
                }
            },
            "Select": {
                "type": "object",
                "title": "select",
                "properties": {
                    "element": {
                        "type": "string"
                    },
                    "name": {
                        "type": "string"
                    },
                    "title": {
                        "type": "string"
                    },
                    "help": {
                        "type": "string"
                    },
                    "options": {
                        "type": "array",
                        "items": {
                            "$ref": "#\/definitions\/Option"
                        }
                    }
                }
            },
            "Option": {
                "type": "object",
                "title": "option",
                "properties": {
                    "key": {
                        "type": "string"
                    },
                    "value": {
                        "type": "string"
                    }
                }
            },
            "Tag": {
                "type": "object",
                "title": "tag",
                "properties": {
                    "element": {
                        "type": "string"
                    },
                    "name": {
                        "type": "string"
                    },
                    "title": {
                        "type": "string"
                    },
                    "help": {
                        "type": "string"
                    }
                }
            },
            "Textarea": {
                "type": "object",
                "title": "textarea",
                "properties": {
                    "element": {
                        "type": "string"
                    },
                    "name": {
                        "type": "string"
                    },
                    "title": {
                        "type": "string"
                    },
                    "help": {
                        "type": "string"
                    },
                    "mode": {
                        "type": "string"
                    }
                }
            },
            "Container": {
                "type": "object",
                "title": "container",
                "properties": {
                    "element": {
                        "type": "array",
                        "items": {
                            "oneOf": [
                                {
                                    "$ref": "#\/definitions\/Input"
                                },
                                {
                                    "$ref": "#\/definitions\/Select"
                                },
                                {
                                    "$ref": "#\/definitions\/Tag"
                                },
                                {
                                    "$ref": "#\/definitions\/Textarea"
                                }
                            ]
                        }
                    }
                }
            },
            "Routes_Provider_Config": {
                "type": "object",
                "title": "Routes Provider Config",
                "additionalProperties": {
                    "oneOf": [
                        {
                            "type": "string"
                        },
                        {
                            "type": "number"
                        },
                        {
                            "type": "boolean"
                        },
                        {
                            "type": "null"
                        },
                        {
                            "type": "array",
                            "items": {
                                "oneOf": [
                                    {
                                        "type": "string"
                                    },
                                    {
                                        "type": "number"
                                    },
                                    {
                                        "type": "boolean"
                                    },
                                    {
                                        "type": "null"
                                    }
                                ]
                            },
                            "maxItems": 16
                        }
                    ]
                },
                "maxProperties": 16
            },
            "Routes_Provider": {
                "type": "object",
                "title": "Routes Provider",
                "properties": {
                    "path": {
                        "type": "string"
                    },
                    "scopes": {
                        "type": "array",
                        "items": {
                            "type": "string"
                        }
                    },
                    "config": {
                        "$ref": "#\/definitions\/Routes_Provider_Config"
                    }
                }
            },
            "Message": {
                "type": "object",
                "title": "Message",
                "properties": {
                    "success": {
                        "type": "boolean"
                    },
                    "message": {
                        "type": "string"
                    }
                }
            },
            "GET-200-response": {
                "$ref": "#\/definitions\/Container"
            },
            "POST-request": {
                "$ref": "#\/definitions\/Routes_Provider"
            },
            "POST-201-response": {
                "$ref": "#\/definitions\/Message"
            }
        }
    },
    "methods": {
        "GET": {
            "queryParameters": "#\/definitions\/GET-query",
            "responses": {
                "200": "#\/definitions\/GET-200-response"
            }
        },
        "POST": {
            "request": "#\/definitions\/POST-request",
            "responses": {
                "201": "#\/definitions\/POST-201-response"
            }
        }
    },
    "links": [
        {
            "rel": "openapi",
            "href": "\/export\/openapi\/*\/backend\/routes\/provider\/:provider"
        },
        {
            "rel": "swagger",
            "href": "\/export\/swagger\/*\/backend\/routes\/provider\/:provider"
        },
        {
            "rel": "raml",
            "href": "\/export\/raml\/*\/backend\/routes\/provider\/:provider"
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('/backend/routes/provider/testprovider', 'GET', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ));

        $body   = (string) $response->getBody();
        $expect = <<<JSON
{
    "element": [
        {
            "element": "http:\/\/fusio-project.org\/ns\/2015\/form\/input",
            "type": "text",
            "name": "table",
            "title": "Table"
        }
    ]
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
    }

    public function testPost()
    {
        $response = $this->sendRequest('/backend/routes/provider/testprovider', 'POST', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'path' => '/foo',
            'scopes' => ['foo'],
            'config' => [
                'table' => 'foobar'
            ],
        ]));

        $body   = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Route successful created"
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $body);
        $this->assertJsonStringEqualsJsonString($expect, $body, $body);
        
        // @TODO check routes etc.
    }

    public function testPut()
    {
        $response = $this->sendRequest('/backend/routes/provider/testprovider', 'PUT', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('/backend/routes/provider/testprovider', 'DELETE', array(
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
        ), json_encode([
            'foo' => 'bar',
        ]));

        $body = (string) $response->getBody();

        $this->assertEquals(405, $response->getStatusCode(), $body);
    }
}