<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = $request->header('X-CSRF-TOKEN') ?? $request->input('csrf_token', '');
        if (!hash_equals(session()->token(), (string) $token)) {
            return response()->json(['error' => 'Invalid CSRF token.'], 403);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'min:1', 'max:500'],
            'csrf_token' => ['required', 'string'],
        ]);

        $message = $validated['message'];

        $apiKey = env('AI_API_KEY', '');
        if ($apiKey === '') {
            return response()->json(['error' => 'AI service is not configured. Please add an API key to the environment.'], 503);
        }

        $chatCount = session('chat_count', 0) + 1;
        if ($chatCount > 20) {
            return response()->json(['error' => 'Message limit reached. Please start a new session.'], 429);
        }
        session(['chat_count' => $chatCount]);

        $chatHistory = session('chat_history', []);
        $chatHistory[] = ['role' => 'user', 'content' => $message];
        $messages = array_slice($chatHistory, -10);

        $systemPrompt = <<<'PROMPT'
You are the AI support assistant for The DS, a premium e-commerce store based in Phnom Penh, Cambodia.

Store facts:
- Name: The DS
- Location: Phnom Penh, Cambodia
- Contact: +855 112 233, thedaservice@store.com
- Brands sold: Nike, Prada, Balenciaga, Ralph Lauren, Puma, Chanel, Gucci, Adidas, Venezianico
- Product categories: Clothes, Perfumes, Accessories, Bags, Sneakers, Premium
- Return policy: 30 days, unused/unworn, original packaging, tags attached. Refunds in 5-10 business days.
- Shipping: Orders ship within 1-3 business days. Delivery time varies by location.
- Payments: KHQR, debit card, and other checkout methods.
- Authenticity: 100% authentic products sourced from brand-authorized distributors.
- Terms page: /pages/terms.php

Guidelines:
- Be concise, friendly, and professional.
- Answer questions about products, shipping, returns, sizing, payments, and account issues.
- If you do not know the answer, direct the user to email thedaservice@store.com or call +855 112 233.
- Do not make up specific product prices or stock levels. General info only.
- Keep responses short (2-4 sentences when possible).
PROMPT;

        $payload = [
            'model' => 'claude-3-5-sonnet-20241022',
            'max_tokens' => 512,
            'system' => $systemPrompt,
            'messages' => $messages,
        ];

        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError !== '') {
            return response()->json(['error' => 'Unable to reach AI service. Please try again later.'], 502);
        }

        $data = json_decode($response, true);

        if ($httpCode !== 200 || empty($data['content'][0]['text'])) {
            $errorMessage = $data['error']['message'] ?? 'AI service returned an error. Please try again later.';
            return response()->json(['error' => $errorMessage], 502);
        }

        $reply = $data['content'][0]['text'];
        $chatHistory[] = ['role' => 'assistant', 'content' => $reply];
        session(['chat_history' => $chatHistory]);

        return response()->json(['reply' => $reply]);
    }
}
