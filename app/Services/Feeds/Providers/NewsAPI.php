<?php

namespace App\Services\Feeds\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPI extends Provider
{
    public string $name;
    public string $url;
    public string $key;

    function __construct()
    {
        $this->name = 'NewsApi';
        $key = $this->key = env('NEWS_API_KEY', '');
        $this->url = "https://newsapi.org/v2/everything?apiKey=$key&";
    }

    function query(string $country, string $category, string $search = ''): array
    {
        $search = urlencode($search);
        $lastWeak = Carbon::now()->subDays(7)->format("Y-m-d");
        $country = strtoupper(self::$supportedCountriesAbbr[$country] ?? $country);
        $uri = $this->url . "q=$search&country=$country&from=$lastWeak&sortBy=publishedAt";
        $response = Http::get($uri);

        try {
            $articles = $response->json();
            $articles = $articles['articles'];
        } catch (\Exception $e) {
            Log::error([$e->getMessage(), $e->getTraceAsString()]);
            $articles = [];
        }
        return $this->collect($articles);
    }

    public function transform(array $feed): array
    {

        return [
            'image' => $feed['urlToImage'] ?? '',
            'title' => $feed['title'] ?? '',
            'description' => $feed['description'] ?? '',
            'author' => $feed['author'] ?? '',
            'date' => $feed['publishedAt'] ?? '',
            'link' => $feed['url'] ?? '',
        ];
    }

    public function collect(array $collection): array
    {
        return collect($collection)->map(function ($model) {
            return $this->transform($model);
        })->toArray();
    }
}
