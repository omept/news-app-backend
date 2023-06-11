<?php

namespace Tests\Unit;

use App\Services\Feeds\Providers\NewsAPI;
use App\Services\Feeds\Providers\NewscatcherApi;
use App\Services\Feeds\Providers\NewsData;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use  \Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ProviderTest extends TestCase
{
  use DatabaseTransactions;

  /**
   * Test that News API query
   *
   * @return void
   */
  public function testNewscatcherApiQuery()
  {

    $this->mockExternalApiCalls();

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

    $this->mockExternalApiCalls();

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

  /**
   * Test that News API query
   *
   * @return void
   */
  public function testNewsAPIQuery()
  {

    $this->mockExternalApiCalls();

    $newscatcherApi = new NewsAPI();
    $queryResults = $newscatcherApi->query('usa', 'business');
    $this->assertArrayHasKey('image', $queryResults[0]);
    $this->assertArrayHasKey('title', $queryResults[0]);
    $this->assertArrayHasKey('description', $queryResults[0]);
    $this->assertArrayHasKey('author', $queryResults[0]);
    $this->assertArrayHasKey('date', $queryResults[0]);
    $this->assertArrayHasKey('link', $queryResults[0]);
  }
}
