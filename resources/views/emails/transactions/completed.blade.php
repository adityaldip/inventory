@component('mail::message')
# Transaction Completed

Dear {{ $transaction->customer_name }},

Your transaction has been completed successfully.

Transaction Details:
- Transaction ID: {{ $transaction->id }}
- Total Amount: ${{ number_format($transaction->total_amount, 2) }}
- Date: {{ $transaction->created_at->format('Y-m-d H:i:s') }}

@component('mail::table')
| Product | Quantity | Amount |
|:--------|:---------|:-------|
@foreach($transaction->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ${{ number_format($item->total_amount, 2) }} |
@endforeach
@endcomponent

Thank you for your business!

Thanks,<br>
{{ config('app.name') }}
@endcomponent 