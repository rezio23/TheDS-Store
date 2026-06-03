<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class BakongService
{
    private ?string $apiKey;
    private ?string $baseUrl;
    private ?string $accountId;
    private ?string $merchantName;
    private ?string $merchantCity;

    public function __construct()
    {
        $this->apiKey = config('services.bakong.api_key');
        $this->baseUrl = rtrim((string) config('services.bakong.base_url', ''), '/');
        $this->accountId = config('services.bakong.account_id');
        $this->merchantName = config('services.bakong.merchant_name', 'the DS');
        $this->merchantCity = config('services.bakong.merchant_city', 'PHNOM PENH');
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey)
            && !empty($this->baseUrl)
            && !empty($this->accountId)
            && $this->accountId !== 'your_bakong_id@nbcq';
    }

    public function isAccountConfigured(): bool
    {
        return !empty($this->accountId) && $this->accountId !== 'your_bakong_id@nbcq';
    }

    /**
     * Generate KHQR. Uses API if configured, otherwise falls back to local library.
     */
    public function generateKhqr(float $amount, string $currency = 'USD'): array
    {
        if ($this->isConfigured()) {
            $apiResult = $this->generateApiKhqr($amount, $currency);
            if ($apiResult['success']) {
                return $apiResult;
            }

            Log::warning('Bakong API generation failed, falling back to local: ' . ($apiResult['message'] ?? 'unknown'));
        }

        return $this->generateLocalKhqr($amount, $currency);
    }

    /**
     * Create a payment request via Bakong API.
     */
    private function generateApiKhqr(float $amount, string $currency = 'USD'): array
    {
        $payload = [
            'merchant_id' => $this->accountId,
            'merchant_name' => $this->merchantName,
            'amount' => number_format($amount, 2, '.', ''),
            'currency' => $currency,
            'callback_url' => route('payment.bakong.callback'),
        ];

        Log::debug('Bakong API payment request', ['payload' => $payload]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/v1/payment-requests', $payload);

            $body = $response->json() ?? [];

            Log::debug('Bakong API response', [
                'status' => $response->status(),
                'body' => $body,
            ]);

            if ($response->successful()) {
                $khqrString = $body['qr_string'] ?? $body['qr'] ?? $body['data']['qr'] ?? null;
                $transactionId = $body['transaction_id'] ?? $body['txn_id'] ?? $body['id'] ?? null;

                if ($khqrString) {
                    return [
                        'success' => true,
                        'khqr_string' => $khqrString,
                        'transaction_id' => $transactionId,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $body['message'] ?? $body['error'] ?? 'Unable to generate Bakong KHQR. Status: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Bakong API exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Bakong API error. Please try again.'];
        }
    }

    /**
     * Generate KHQR locally using the Bakong library.
     * Uses a test account ID if none is configured so the QR remains scannable in bank apps for development.
     */
    public function generateLocalKhqr(float $amount, string $currency = 'USD'): array
    {
        $accountId = $this->accountId;
        if (empty($accountId) || $accountId === 'your_bakong_id@nbcq') {
            $accountId = 'test_merchant@nbcq';
        }

        try {
            $individualInfo = IndividualInfo::withOptionalArray(
                $accountId,
                $this->merchantName,
                $this->merchantCity,
                [
                    'currency' => $currency === 'KHR' ? KHQRData::CURRENCY_KHR : KHQRData::CURRENCY_USD,
                    'amount' => $amount,
                ]
            );

            $response = BakongKHQR::generateIndividual($individualInfo);
            $qr = $response->data['qr'] ?? null;

            if ($qr) {
                return [
                    'success' => true,
                    'khqr_string' => $qr,
                    'transaction_id' => null,
                ];
            }

            return ['success' => false, 'message' => 'Local KHQR generation failed.'];
        } catch (\Exception $e) {
            Log::error('Bakong local KHQR exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'KHQR generation error.'];
        }
    }

    /**
     * Check transaction status via Bakong API.
     */
    public function checkTransactionStatus(string $transactionId): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'message' => 'Bakong API not configured.'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ])->timeout(30)->get($this->baseUrl . '/v1/transactions/' . $transactionId);

            $body = $response->json() ?? [];

            if ($response->successful()) {
                return ['success' => true, 'data' => $body];
            }

            return ['success' => false, 'message' => $body['message'] ?? $body['error'] ?? 'Unable to check status.'];
        } catch (\Exception $e) {
            Log::error('Bakong status check exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Status check error.'];
        }
    }
}
