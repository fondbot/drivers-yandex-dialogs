<?php

declare(strict_types=1);

namespace FondBot\Drivers\YandexDialogs;

use FondBot\Channels\ChannelManager;
use Illuminate\Support\ServiceProvider;

class YandexDialogsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var ChannelManager $manager */
        $manager = $this->app[ChannelManager::class];

        $manager->extend('yandex-dialogs', function () {
            return new YandexDialogsDriver();
        });
    }
}