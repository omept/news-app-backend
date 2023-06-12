<?php

namespace App\Services\Feeds\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsData extends Provider
{
    public string $name;
    public string $url;
    public string $key;

    function __construct()
    {
        $this->name = 'NewsData';
        $key =  $this->key = env('NEWS_DATA_API_KEY', '');
        $this->url = "https://newsdata.io/api/1/news?apikey=$key";
    }

    function query(string $country, string $category, string $search = ''): array
    {
        $search = urlencode($search);
        $country = strtoupper(self::$supportedCountriesAbbr[$country] ?? $country);
        $uri = $this->url . "&country=$country&category=$category&q=$search";
        $response = Http::get($uri);

        try {
            $articles = $response->json();
            $articles = $articles['results'];
        } catch (\Exception $e) {
            Log::error([$e->getMessage(), $e->getTraceAsString()]);
            $articles = [];
        }
        return $this->collect($articles);
    }

    public function transform(array $feed): array
    {
        return [
            'image' => $feed['image_url'] ?? '',
            'title' => $feed['title'] ?? '',
            'description' => $feed['description'] ?? '',
            'author' =>  $feed['source_id'] ?? '',
            'date' => $feed['pubDate'] ?? '',
            'link' => $feed['link'] ?? '',
        ];
    }

    public function collect(array $collection): array
    {
        return collect($collection)->map(function ($model) {
            return $this->transform($model);
        })->toArray();
    }
}
