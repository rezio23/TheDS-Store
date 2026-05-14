<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        $termsSections = [
            [
                'title' => 'Introduction',
                'content' => 'Welcome to The DS. These Terms and Conditions govern your use of our website and the purchase of products from our online store. By accessing or using our website, you agree to be bound by these terms. If you do not agree with any part of these terms, please do not use our services.',
            ],
            [
                'title' => 'Orders and Acceptance',
                'content' => 'All orders placed through our website are subject to acceptance and availability. We reserve the right to refuse or cancel any order for any reason, including limitations on quantities available for purchase, inaccuracies in product descriptions or pricing, or issues identified by our fraud detection systems. Once an order is placed, you will receive an order confirmation email, but this does not constitute acceptance of your order.',
            ],
            [
                'title' => 'Pricing and Payment',
                'content' => 'All prices listed on our website are in US Dollars and are subject to change without notice. We accept payments via KHQR, debit card, and other methods as indicated at checkout. You agree to provide current, complete, and accurate purchase and account information for all purchases made through our store.',
            ],
            [
                'title' => 'Shipping and Delivery',
                'content' => 'We aim to process and ship orders within 1-3 business days. Delivery times vary based on your location and selected shipping method. We are not responsible for delays caused by customs, carrier issues, or other circumstances beyond our control. Risk of loss and title for items purchased pass to you upon delivery to the carrier.',
            ],
            [
                'title' => 'Returns and Refunds',
                'content' => 'We accept returns within 30 days of delivery for items that are unused, unworn, and in their original packaging with all tags attached. Refunds will be processed to the original payment method within 5-10 business days after we receive and inspect the returned item. Shipping costs for returns are the responsibility of the customer unless the return is due to our error.',
            ],
            [
                'title' => 'Product Authenticity',
                'content' => 'The DS guarantees that all products sold are 100% authentic and sourced directly from brand-authorized distributors. Every item undergoes a rigorous verification process before being listed for sale. We do not sell counterfeit or replica products.',
            ],
            [
                'title' => 'Intellectual Property',
                'content' => 'All content on this website, including text, graphics, logos, images, and software, is the property of The DS or its content suppliers and is protected by copyright and other intellectual property laws. You may not reproduce, distribute, or create derivative works from any content without our express written permission.',
            ],
            [
                'title' => 'Limitation of Liability',
                'content' => 'To the fullest extent permitted by law, The DS shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or relating to your use of our website or products. Our total liability shall not exceed the amount you paid for the specific product giving rise to the claim.',
            ],
            [
                'title' => 'Governing Law',
                'content' => 'These Terms and Conditions are governed by and construed in accordance with the laws of the Kingdom of Cambodia. Any disputes arising under these terms shall be subject to the exclusive jurisdiction of the courts of Phnom Penh, Cambodia.',
            ],
            [
                'title' => 'Changes to Terms',
                'content' => 'We reserve the right to update or modify these Terms and Conditions at any time without prior notice. Your continued use of the website following any changes constitutes acceptance of those changes. We encourage you to review these terms periodically.',
            ],
            [
                'title' => 'Contact Us',
                'content' => 'If you have any questions about these Terms and Conditions, please contact us via email at thedaservice@store.com or by phone at +855 112 233.',
            ],
        ];

        return view('terms', compact('termsSections'));
    }

    public function helpCenter(Request $request)
    {
        $requestSuccess = false;
        $requestError = '';

        if ($request->isMethod('post') && $request->has('personal_request')) {
            $requestText = trim($request->input('request_text', ''));
            $requestEmail = trim($request->input('request_email', ''));
            $requestPhone = trim($request->input('request_phone', ''));

            if ($requestText === '') {
                $requestError = 'Please describe your request.';
            } elseif ($requestEmail === '' || !filter_var($requestEmail, FILTER_VALIDATE_EMAIL)) {
                $requestError = 'Please enter a valid email address.';
            } elseif ($requestPhone === '') {
                $requestError = 'Please enter your phone number.';
            } else {
                $uploadDir = storage_path('app/public/uploads/support');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $uploadedFile = '';
                if ($request->hasFile('request_file') && $request->file('request_file')->isValid()) {
                    $file = $request->file('request_file');
                    $mimeType = $file->getMimeType();
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf', 'text/plain'];

                    if (in_array($mimeType, $allowedTypes, true)) {
                        $originalName = $file->getClientOriginalName();
                        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                        $safeName = time() . '_' . $safeName;
                        $file->move($uploadDir, $safeName);
                        $uploadedFile = $safeName;
                    } else {
                        $requestError = 'Invalid file type or upload failed.';
                    }
                }

                if ($requestError === '') {
                    $logFile = $uploadDir . '/requests.json';
                    $requests = [];
                    if (file_exists($logFile)) {
                        $requests = json_decode(file_get_contents($logFile), true) ?: [];
                    }
                    $requests[] = [
                        'id' => uniqid('req_', true),
                        'email' => $requestEmail,
                        'phone' => $requestPhone,
                        'text' => $requestText,
                        'file' => $uploadedFile,
                        'status' => 'pending',
                        'created_at' => now()->format('Y-m-d H:i:s'),
                    ];
                    file_put_contents($logFile, json_encode($requests, JSON_PRETTY_PRINT));
                    $requestSuccess = true;
                }
            }
        }

        $helpFaqs = [
            [
                'question' => 'How do I place an order?',
                'answer' => 'Browse our Shop page, select a product, choose your size, and click Add to Cart. When you are ready, go to your Cart and proceed to Checkout. You can pay with KHQR or debit card.',
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept KHQR, debit card, and other secure payment methods available at checkout. All transactions are processed safely.',
            ],
            [
                'question' => 'How long does shipping take?',
                'answer' => 'We process orders within 1-3 business days. Delivery times vary based on your location and selected shipping method. You will receive tracking details once your order ships.',
            ],
            [
                'question' => 'What is your return policy?',
                'answer' => 'We accept returns within 30 days of delivery for items that are unused, unworn, and in their original packaging with all tags attached. Refunds are processed within 5-10 business days.',
            ],
            [
                'question' => 'Are your products authentic?',
                'answer' => 'Yes. The DS guarantees 100% authentic products sourced directly from brand-authorized distributors. Every item undergoes rigorous verification before listing.',
            ],
            [
                'question' => 'How do I contact customer support?',
                'answer' => 'You can reach us via email at thedaservice@store.com or by phone at +855 112 233. Our team is based in Phnom Penh, Cambodia. You can also use the AI chat below for instant answers.',
            ],
        ];

        $helpTickerBrands = ['POLO', 'BALENCIAGA', 'ADIDAS', 'NIKE', 'PUMA', 'GUCCI', 'PRADA', 'CHANEL'];

        return view('help-center', compact('helpFaqs', 'helpTickerBrands', 'requestSuccess', 'requestError'));
    }

    public function storeHelpRequest(Request $request)
    {
        return $this->helpCenter($request);
    }
}
