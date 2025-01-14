<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Mail\TransactionCompleted as TransactionCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTransactionEmail implements ShouldQueue
{
    public function handle(TransactionCompleted $event): void
    {
        if ($event->transaction->customer_email) {
            Mail::to($event->transaction->customer_email)
                ->queue(new TransactionCompletedMail($event->transaction));
        }
    }
} 