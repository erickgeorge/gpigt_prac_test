<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRating;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function rateProduct(Request $request, $productId)
    {
        // Validate request data
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Check if the user has already rated the product
        $userId = Auth::id();
        $existingRating = UserRating::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        // If the user has already rated the product, update the rating
        if ($existingRating) {
            $existingRating->rating = $request->rating;
            $existingRating->save();
        } else {
            // Otherwise, create a new rating record
            UserRating::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'rating' => $request->rating,
            ]);
        }

        return response()->json(['message' => 'Product rated successfully']);
    }

    public function removeRating($productId)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Find and delete the user's rating for the specified product
        UserRating::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Rating removed successfully']);
    }

    public function changeRating(Request $request, $productId)
    {
        // Validate request data
        $request->validate([
            'rating' => 'required|integer|between:1,5',
        ]);

        // Get the authenticated user's ID
        $userId = Auth::id();

        // Find and update the user's rating for the specified product
        UserRating::where('user_id', $userId)
            ->where('product_id', $productId)
            ->update(['rating' => $request->rating]);

        return response()->json(['message' => 'Rating changed successfully']);
    }
}
