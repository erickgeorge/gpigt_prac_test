<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UserRating;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index()
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Retrieve products
        $products = Product::with('ratings')->get();

        // Calculate average ratings, user ratings, time passed, and active time for each product
        foreach ($products as $product) {
            $totalRatings = $product->ratings()->count();
            $sumRatings = $product->ratings()->sum('rating');
            $averageRating = $totalRatings > 0 ? $sumRatings / $totalRatings : 0;
            $product->average_rating = round($averageRating, 2);

            // Calculate user's rating for the product
            $latestRating = $product->ratings()->latest()->first();

            if ($latestRating) {
                $product->time_passed = Carbon::parse($latestRating->rating_datetime)->diffInMinutes();
            } else {
                $product->time_passed = null;
            }


            // Calculate time passed for each rating
            $latestRating = $product->ratings()->latest()->first();
            $product->time_passed = $latestRating ? Carbon::parse($latestRating->rating_datetime)->diffInMinutes() : null;

            // Calculate active time based on time passed
            $product->active_time = $product->time_passed > 30 ? 'active' : 'inactive';
        }

        return response()->json(['products' => $products]);
    }
}
