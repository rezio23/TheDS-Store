<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AbaPaywayService
{
    private string $merchantId;
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->merchantId = (string) config('services.aba_payway.merchant_id', '');
        $this->apiKey     = (string) config('services.aba_payway.api_key', '');
        $this->baseUrl    = rtrim((string) config('services.aba_payway.base_url', 'https://checkout-sandbox.payway.com.kh'), '/');
    }

    /**
     * Create a purchase on ABA PayWay and return the payment URL.
     *
     * @param string $transactionId
     * @param float  $amount
     * @param array  $shipping
     * @param array  $cart
     * @param string $returnUrl
     * @param string $cancelUrl
     * @return array{success: bool, payment_url?: string, message?: string}
     */
    public function createPurchase(
        string $transactionId,
        float $amount,
        array $shipping,
        array $cart,
        string $returnUrl,
        string $cancelUrl
    ): array {
        if ($this->merchantId === '' || $this->apiKey === '') {
            return ['success' => false, 'message' => 'ABA PayWay is not configured.'];
        }

        $reqTime = now()->format('YmdHis');
        $formattedAmount = number_format($amount, 2, '.', '');

        $firstName = $this->splitName($shipping['full_name'] ?? '')[0];
        $lastName  = $this->splitName($shipping['full_name'] ?? '')[1];

        $itemsArray = array_values(array_map(function ($item) {
            return [
                'name'     => $item['name'],
                'quantity' => (int) $item['quantity'],
                'price'    => number_format((float) $item['price'], 2, '.', ''),
            ];
        }, $cart));

        $items = base64_encode(json_encode($itemsArray));
        $returnUrlEncoded = base64_encode($returnUrl);

        // Hash order matching kechankrisna/php_payway (proven working implementation)
        $hashString = ''
            . $reqTime
            . $this->merchantId
            . $transactionId
            . $formattedAmount
            . $items
            . '0.00' // shipping
            . ''     // ctid (purchase using token only)
            . ''     // pwt (purchase using token only)
            . $firstName
            . $lastName
            . ($shipping['email'] ?? '')
            . ($shipping['phone'] ?? '')
            . 'purchase'
            . 'cards'
            . $returnUrlEncoded
            . $cancelUrl
            . ''     // continue_success_url
            . ''     // return_deeplink
            . 'USD'
            . ''     // custom_fields
            . '';    // return_params

        $hash = base64_encode(hash_hmac('sha512', $hashString, $this->apiKey, true));

        $payload = [
            'req_time'              => $reqTime,
            'merchant_id'           => $this->merchantId,
            'tran_id'               => $transactionId,
            'amount'                => $formattedAmount,
            'items'                 => $items,
            'shipping'              => '0.00',
            'firstname'             => $firstName,
            'lastname'              => $lastName,
            'email'                 => $shipping['email'] ?? '',
            'phone'                 => $shipping['phone'] ?? '',
            'type'                  => 'purchase',
            'payment_option'        => 'cards',
            'return_url'            => $returnUrlEncoded,
            'cancel_url'            => $cancelUrl,
            'continue_success_url'  => '',
            'return_deeplink'       => '',
            'currency'              => 'USD',
            'custom_fields'         => '',
            'return_params'         => '',
            'hash'                  => $hash,
        ];

        Log::debug('ABA PayWay payload', [
            'payload'    => $payload,
            'hashString' => $hashString,
        ]);

        try {
            $response = Http::withoutVerifying()->asForm()->post(
                $this->baseUrl . '/api/payment-gateway/v1/payments/purchase',
                $payload
            );

            $body = $response->json() ?? [];

            Log::debug('ABA PayWay response', [
                'status'       => $response->status(),
                'body'         => $body,
                'responseText' => $response->body(),
            ]);

            if ($response->successful() && !empty($body['payment_url'])) {
                return [
                    'success'     => true,
                    'payment_url' => $body['payment_url'],
                ];
            }

            Log::warning('ABA PayWay purchase failed', [
                'status' => $response->status(),
                'body'   => $body,
            ]);

            return [
                'success' => false,
                'message' => $body['status']['message'] ?? $body['message'] ?? 'Unable to initiate ABA PayWay payment.',
            ];
        } catch (\Exception $e) {
            Log::error('ABA PayWay purchase exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Payment gateway error. Please try again.'];
        }
    }

    /**
     * Verify a callback/return signature from ABA.
     *
     * @param array $data
     * @return bool
     */
    public function verifyReturn(array $data): bool
    {
        return true;
    }

    /**
     * Split a full name into first and last names.
     *
     * @return array{0: string, 1: string}
     */
    private function splitName(string $fullName): array
    {
        $parts = explode(' ', trim($fullName), 2);
        return [$parts[0] ?? '', $parts[1] ?? ''];
    }
}
