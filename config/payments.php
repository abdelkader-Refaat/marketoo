<?php

return [

    'active_gateway' => env('PAYMENT_GATEWAY', 'Myfatoorah'),

    'gateway' => [
        \App\Services\PaymentGateways\HyperpayService::class,
        \App\Services\PaymentGateways\MyfatoorahService::class,
        \App\Services\PaymentGateways\StripeService::class,
        \App\Services\PaymentGateways\PaypalService::class,
        \App\Services\PaymentGateways\PaymobService::class,
        \App\Services\PaymentGateways\ClickpayService::class,
        \App\Services\PaymentGateways\TelrService::class,
        \App\Services\PaymentGateways\AlrajhibankService::class,
    ],

    // modes TEST , LIVE

    'Myfatoorah' => [
        'MODE' => 'TEST',
        'TEST_BASE_URL' => 'https://apitest.myfatoorah.com',
        'TEST_API_TOKEN' => 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL',
        'LIVE_BASE_URL' => 'https://api-sa.myfatoorah.com/',
        'LIVE_API_TOKEN' => '',
    ],

    'Hyperpay' => [
        'MODE' => 'TEST',
        'TEST_BASE_URL' => 'https://eu-test.oppwa.com/',
        'TEST_API_TOKEN' => 'OGFjN2E0Yzc5Mzk0YmRjODAxOTM5NzM2ZjFhNzA2NDF8enlac1lYckc4QXk6bjYzI1NHNng=',
        'TEST_ENTITY_ID' => '8ac7a4c79394bdc801939736f17e063d',
        'LIVE_ENTITY_ID' => '',
        'CHECKOUT_URL' => 'https://eu-test.oppwa.com/v1/paymentWidgets.js',
        'LIVE_BASE_URL' => 'https://eu-prod.oppwa.com/',
        'LIVE_API_TOKEN' => '',
    ],

    'Stripe' => [
        'TEST_MODE' => env('STRIPE_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://eu-test.oppwa.com/',
    ],

    'Paypal' => [
        'TEST_MODE' => env('PAYPAL_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://eu-test.oppwa.com/',
        'TEST_API_KEY' => 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL
',
    ],

    'Paymob' => [
        'TEST_MODE' => env('PAYMOB_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://accept.paymob.com/api/',
        'TEST_INTEGRATION_ID' => '',
        'TEST_IFRAME_ID' => '',
        'TEST_IFRAME_LINK' => '',
        'BASE_URL' => 'https://accept.paymob.com/api/',
        'INTEGRATION_ID' => '',
        'IFRAME_ID' => '',
        'IFRAME_LINK' => '',
        'VERSION' => null,
        'API_KEY' => 'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2T0RReU1EWXhMQ0p1WVcxbElqb2lhVzVwZEdsaGJDSjkuR0FkZ2RrU3F6NDgwaWZPNk5wcmZWZUZlVUN4cnVxYkpPanptSFNPWWFMX3UxRXpMZkdoOThlX3MyOU1EdElvYWxsS1JURTMtXzU0ZWhqNU8zNnZjX3c=',
    ],
    'Clickpay' => [
        'TEST_MODE' => env('CLICKPAY_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://secure.clickpay.com.sa/',
        'BASE_URL' => 'https://secure.clickpay.com.sa/',
        'SERVER_KEY' => 'SKJNL9W26G-J6M9ZGLHJK-TRJL2HMB6W',
        'CLIENT_KEY' => 'CMKMDT-HDDM6G-NP9TBH-PRB2MD',
    ],
    'Telr' => [
        'TEST_MODE' => env('TELR_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://secure.telr.com/gateway/order.json',
        'BASE_URL' => 'https://secure.telr.com/gateway/order.json',
        'CURRENCY' => env('TELR_CURRENCY', 'SAR'),
        'IVP_AUTH_KEY' => env('TELR_IVP_AUTH_KEY', 'test'),
    ],
    'Alrajhibank' => [
        'TEST_MODE' => env('TELR_TEST_MODE', true),
        'TEST_BASE_URL' => 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm',
        'BASE_URL' => 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm',
        'CURRENCY' => env('CURRENCY_CODE', 682),
        'ENCRYPTION_KEY' => env('ENCRYPTION_KEY', null),
        'ACCOUNT_ID' => env('ACCOUNT_ID', null),
        'ACCOUNT_PASSWORD' => env('ACCOUNT_PASSWORD', null),
        'VIEW_URL' => env('ALRAJHIBANK_VIEW_URL', 'https://digitalpayments.alrajhibank.com.sa/pg/paymentpage.htm'),
    ],

];
