<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;
    use WithFaker;
    /**
     */
    public function headers(User $user = null)
    {
        $headers = ['Accept' => 'application/json'];

        if (!is_null($user)) {
            $token = JWTAuth::fromUser($user);
            JWTAuth::setToken($token);
            $headers['Authorization'] = 'Bearer ' . $token;
            $headers['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        }

        return $headers;
    }

    function mockExternalApiCalls()
    {
        $demoData = '{
            "status": "ok",
            "articles": [
              {
                "title": "Mancity Wins Champions League – Voice of Nigeria",
                "author": "",
                "published_date": "2023-06-11 07:31:46",
                "published_date_precision": "full",
                "link": "https://von.gov.ng/mancity-wins-champions-league",
                "clean_url": "von.gov.ng",
                "excerpt": null,
                "summary": "Page Not Found!\n\nWe\'re sorry, but we can\'t find the page you were looking for. It\'s probably some thing we\'ve done wrong but now we know about it and we\'ll try to fix it. In the meantime, try one of these options:",
                "rights": "von.gov.ng",
                "rank": 160283,
                "topic": "business",
                "country": "NG",
                "language": "en",
                "authors": "",
                "media": "https://von.gov.ng/wp-content/uploads/2022/04/cropped-mng3nzaekusp-32x32.jpeg",
                "is_opinion": false,
                "twitter_account": null,
                "_score": null,
                "_id": "df43f30d0d84912fbdf46561c7598453"
              }
            ]
          }';
        Http::fake([
            // Stub a JSON response for NewscatcherApi
            'https://api.newscatcherapi.com/*' => Http::response(json_decode($demoData, true), 200,)
        ]);

        $demoData = '{
            "results": [
              {
                "title": "Mancity Wins Champions League – Voice of Nigeria",
                "description": "Mancity Wins Champions League – Voice of Nigeria",
                "image_url": "Mancity Wins Champions League – Voice of Nigeria",
                "source_id": "Mancity Wins Champions League – Voice of Nigeria",
                "pubDate": "Mancity Wins Champions League – Voice of Nigeria",
                "link": "Mancity Wins Champions League – Voice of Nigeria"
              }
            ]
          }';

        Http::fake([
            // Stub a JSON response for NewsDataApi
            'https://newsdata.io/api/1/*' => Http::response(json_decode($demoData, true), 200,)
        ]);



        $demoData = '{
        "articles": [
          {
            "title": "Mancity Wins Champions League – Voice of Nigeria",
            "author": "Mancity Wins Champions League – Voice of Nigeria",
            "description": "Mancity Wins Champions League – Voice of Nigeria",
            "url": "Mancity Wins Champions League – Voice of Nigeria",
            "urlToImage": "Mancity Wins Champions League – Voice of Nigeria",
            "publishedAt": "Mancity Wins Champions League – Voice of Nigeria",
            "url": "Mancity Wins Champions League – Voice of Nigeria"
          }
        ]
      }';

        Http::fake([
            // Stub a JSON response for NewsDataApi
            'https://newsapi.org/v2/*' => Http::response(json_decode($demoData, true), 200,)
        ]);
    }
}
