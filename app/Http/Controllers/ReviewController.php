<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Pastikan user memiliki customer
        if (!Auth::user()->customer) {
            return redirect()->route('profile.edit')->with('error', 'Profil customer belum lengkap.');
        }

        $review = new ProductReview();
        $review->product_id = $request->product_id;
        $review->customer_id = Auth::user()->customer->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->review_date = Carbon::now();
        $review->save();

        return redirect()->back()->with('success', 'Ulasan berhasil ditambahkan');
    }

    public function destroy(ProductReview $review)
    {
        // Pastikan user hanya bisa menghapus review miliknya
        if (!Auth::user()->customer || $review->customer_id != Auth::user()->customer->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus ulasan ini');
        }

        $review->delete();
        return redirect()->back()->with('success', 'Ulasan berhasil dihapus');
    }
}
