<?php

namespace App\Services\Feeds\Providers;

class NewsAPI extends Provider
{
    public string $name;
    public string $url;
    public string $key;

    function __contruct()
    {
        $this->name = 'NewsApi';
        $this->url = 'NewsApi';
        $this->key = env('NEWS_API_KEY', '');
    }

    function query(string $country, string $category, string $search = ''): string
    {
        return json_encode([]);
    }
}
