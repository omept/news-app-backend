<?php

namespace App\Services\Feeds\Providers;

interface ProviderInterface {
    public function query(string $country, string $category, string $search): string;
}