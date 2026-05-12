<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return redirect()->back()->with('success', 'Removed from favorites.');
        }

        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return redirect()->back()->with('success', 'Added to favorites.');
    }
}
