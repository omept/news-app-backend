<?php

namespace App\Services\Feeds\Providers;

class NewscatcherApi extends Provider {
    public string $name;
    public string $url;
    public string $key;

    function __contruct(){
        $this->name = 'NewscatcherApi';
        $this->url = 'NewscatcherApi';
        $this->key = env('NEWS_CATCHER_API_KEY','');
    }

    function query(string $country, string $category, string $search=''):string{
        return json_encode([]);
    }
}