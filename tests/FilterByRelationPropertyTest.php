<?php

namespace App\Tests;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FilterByRelationPropertyTest extends WebTestCase
{
    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSomething(): void
    {
        $baseUrl = 'http://api.thanks.local';

        $client = new Client();

        $meta = [
            'filter' => [
                "operator"=> "AND",
                "filters"=> [
                    [
                        "property"=> "role.roleKey",
                        "operator"=> "RELATION.EQ",
                        "value"=> "ROLE_SUPER_ADMIN"
                    ]
                ]
            ]
        ];

        $response = $client->get(
            $baseUrl.'/users',
            ['meta'=> $meta]
        );

        dd(json_decode($response->getBody()->getContents(), true)['meta']);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
