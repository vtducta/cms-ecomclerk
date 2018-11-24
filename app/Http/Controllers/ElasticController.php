<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;

class ElasticController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function elastic(Request $request){
                    $hosts = [
                        [
                        'host' => '4910e4ced04e43cba7f3af1bb9c6c605.us-east-1.aws.found.io',
                        'port' => '9243',
                        'scheme' => 'https',
                        'user' => 'elastic', 
                        'pass' => 'ZE3u8jPcx8L0mX5rpdY94kSc'
                        ]
                    ];
                    $client = ClientBuilder::create() 
                    ->setHosts($hosts) 
                    ->build();


                        $params = [
                            'body' => [
                                'query' => [
                                            'match' =>[
                                                'team_id' =>[
                                                    'query' => '1fa86701af05a863f59dd0f4b6546b32'
                                                ]
                                            ]
                                ],
                                'aggs' => [
                                    'intraday_return' => [
                                        'sum' => [
                                            'field' => 'sent'   
                                        ]
                                    ]
                                ]
                            ]   
                        ];


 

                    $response = $client->search($params);



    }
}
