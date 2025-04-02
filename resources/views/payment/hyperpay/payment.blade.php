@extends('layouts.app')

@section('content')
    <form action="{{ route('payment.callback') }}" class="paymentWidgets" data-brands="{{ $brand_type }}"></form>

    <script>
        var wpwlOptions = {
            locale: 'ar'
        };
    </script>

    <script src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $transaction_id }}"></script>
@endsection
