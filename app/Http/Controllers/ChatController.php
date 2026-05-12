<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $message = trim($request->input('message'));

        // Simple keyword-based responses when no AI key is configured
        $replies = [
            'order' => 'You can place an order by browsing the Shop, adding items to your cart, and proceeding to checkout.',
            'ship' => 'We process orders within 1-3 business days. Delivery times vary based on your location and selected shipping method.',
            'track' => 'Once your order ships, you will receive tracking details via email.',
            'return' => 'We accept returns within 30 days of delivery for unused items in original packaging.',
            'payment' => 'We accept KHQR, debit card, and other secure payment methods at checkout.',
            'authentic' => 'Yes, The DS guarantees 100% authentic products sourced directly from brand-authorized distributors.',
            'contact' => 'You can reach us via email at thedaservice@store.com or by phone at +855 112 233.',
            'help' => 'I can help with orders, shipping, returns, sizing, and product questions. What would you like to know?',
        ];

        $lower = strtolower($message);
        $reply = null;

        foreach ($replies as $keyword => $text) {
            if (str_contains($lower, $keyword)) {
                $reply = $text;
                break;
            }
        }

        if (!$reply) {
            $reply = "Thanks for reaching out! For detailed assistance, please email us at thedaservice@store.com or call +855 112 233.";
        }

        return response()->json(['reply' => $reply]);
    }
}
