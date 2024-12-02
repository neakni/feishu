<?php

namespace Larafly\Feishu;

use Illuminate\Support\ServiceProvider;

class FeishuServiceProvider extends ServiceProvider
{
    public function register()
    {
        $source = realpath(__DIR__.'/../config/feishu.php');
        $this->mergeConfigFrom($source, 'feishu');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/feishu.php' => config_path('feishu.php'),
        ]);
    }
}
