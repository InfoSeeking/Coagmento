<?php

use App\Services\Query;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueryTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp() {
        parent::setUp();

        $app = $this->createApplication();
        $this->queryService = $app->make('App\Services\QueryService');
        $this->testUrls = [
            [
                'url' => 'https://www.bing.com/search?q=this%20is%20a%20test&form=EDGEAR&qs=AS&cvid=e2678d71448940a592c68030040ec159',
                'query' => 'this is a test'
            ],
            [
                'url' => 'https://www.bing.com/search?q=wow!%20i%2C%20can%20parse%20%24%23%40&form=EDGENT&qs=PF&cvid=765beba93c4643d4b94c45bafc70eedf&pq=wow!%20i%2C%20can%20parse%20%24%23%40',
                'query' => 'wow! i, can parse $#@'
            ],
            [
                // TODO: Cover cases when + actually means plus (urldecode converts to spaces).
                'url' => 'https://www.bing.com/search?q=what%20is%201%2B1&form=EDGEAR&qs=CM&cvid=8d52f584d04344348135333fd3631ccc&pq=what%20is%201%2B1',
                'query' => 'what is 1 1'
            ],
            [
                'url' => 'https://www.google.com/search?q=test+of+google&oq=test+of+google&gs_l=serp.3..0j0i22i30l9.2886969.2888021.0.2888146.10.10.0.0.0.0.199.667.3j3.6.0....0...1c.1.64.serp..4.6.666.C52-7wthFuU',
                'query' => 'test of google'
            ],
            [
                'url' => 'https://search.yahoo.com/search;_ylt=AhIa9Q8Q_RNAUELys4Ir0hibvZx4?p=test+of+yahoo%21&toggle=1&cop=mss&ei=UTF-8&fr=yfp-t-901&fp=1',
                'query' => 'test of yahoo!'
            ],
            [
                'url' => 'https://duckduckgo.com/?q=test+of+duckduckgo',
                'query' => 'test of duckduckgo'
            ],
            [
                'url' => 'notaurl',
                'query' => false
            ],
            [
                'url' => 'http://google.com',
                'query' => false
            ],
            [
                'url' => 'http://google.com/search/no_url_params',
                'query' => false
            ]
        ];
    }

    public function testParse() {
        foreach ($this->testUrls as $test) {
            $queryStatus = $this->queryService->parseQuery($test['url']);
            if (!$test['query']) {
                $this->assertFalse($queryStatus->isOK());
            } else {
                $this->assertEquals($test['query'], $queryStatus->getResult()['text']);
            }
        }
    }
}
