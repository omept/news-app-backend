<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Feeds\Adapter;
use App\Services\Feeds\Providers\NewscatcherApi;
use App\Services\Feeds\Providers\NewsData;
use Database\Seeders\DefaultCategoriesSeed;
use  \Tests\TestCase;
use  \Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProviderTest extends TestCase
{

  /**
   * Test that News API query
   *
   * @return void
   */
  public function testNewscatcherApiQuery()
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

    $newscatcherApi = new NewscatcherApi();
    $queryResults = $newscatcherApi->query('usa', 'business');
    $this->assertArrayHasKey('image', $queryResults[0]);
    $this->assertArrayHasKey('title', $queryResults[0]);
    $this->assertArrayHasKey('description', $queryResults[0]);
    $this->assertArrayHasKey('author', $queryResults[0]);
    $this->assertArrayHasKey('date', $queryResults[0]);
    $this->assertArrayHasKey('link', $queryResults[0]);
  }


  /**
   * Test that News API query
   *
   * @return void
   */
  public function testNewsDataQuery()
  {

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

    // dd($demoData);
    // dd(json_decode($demoData, true));
    $newscatcherApi = new NewsData();
    $queryResults = $newscatcherApi->query('usa', 'business');
    $this->assertArrayHasKey('image', $queryResults[0]);
    $this->assertArrayHasKey('title', $queryResults[0]);
    $this->assertArrayHasKey('description', $queryResults[0]);
    $this->assertArrayHasKey('author', $queryResults[0]);
    $this->assertArrayHasKey('date', $queryResults[0]);
    $this->assertArrayHasKey('link', $queryResults[0]);
  }
}
