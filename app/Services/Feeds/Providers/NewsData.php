<?php

namespace App\Services\Feeds\Providers;

class NewsData extends Provider {
    public string $name;
    public string $url;
    public string $key;

    function __contruct(){
        $this->name = 'NewsData';
        $this->url = 'NewsData';
        $this->key = env('NEWS_DATA_API_KEY','');
    }

    function query(string $country, string $category, string $search=''): string{
        return json_encode([]);
    }
}