<?php

namespace Zus1\Serializer\Providers;

use Illuminate\Support\ServiceProvider;
use Zus1\Serializer\Interface\NormalizerInterface;
use Zus1\Serializer\Normalizer\Normalizer;

class LaravelSerializerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(NormalizerInterface::class, Normalizer::class);

        $this->publishes([
            __DIR__.'/../../config/serializer.php' => config_path('serializer.php'),
        ]);
    }
}