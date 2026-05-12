<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms');
    }

    public function helpCenter()
    {
        return view('help-center');
    }

    public function storeHelpRequest(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120',
        ]);

        // Store help request logic here (can be extended to save to DB)
        // For now, just flash a success message

        return redirect()->route('help-center')->with('success', 'Your request has been submitted. We will contact you soon.');
    }
}
