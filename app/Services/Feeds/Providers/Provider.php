<?php

namespace App\Services\Feeds\Providers;

abstract class Provider implements ProviderInterface {
    public string $name;
    public string $url;
    public string $key;
}