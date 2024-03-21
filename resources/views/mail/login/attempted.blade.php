<x-mail::message>
# Order Shipped

Your order has been shipped!

<x-mail::button :url="$url">
Reset password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
