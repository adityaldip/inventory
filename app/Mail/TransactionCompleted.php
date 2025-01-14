<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
    }

    public function build()
    {
        return $this->markdown('emails.transactions.completed')
                    ->subject('Transaction Completed')
                    ->with([
                        'transaction' => $this->transaction
                    ]);
    }
} 