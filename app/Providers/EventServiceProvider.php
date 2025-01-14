<?php

namespace App\Providers;

use App\Events\TransactionCompleted;
use App\Listeners\SendTransactionEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $listen = [
        TransactionCompleted::class => [
            SendTransactionEmail::class,
        ],
    ];
} 