<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ReviewController extends Controller
{    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|exists:uni_products,id',
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'required|string|min:10|max:1000'
            ], [
                'product_id.required' => 'Product is required',
                'product_id.exists' => 'Product not found',
                'rating.required' => 'Rating is required',
                'rating.integer' => 'Rating must be a number',
                'rating.min' => 'Rating must be at least 1',
                'rating.max' => 'Rating cannot exceed 5',
                'review.required' => 'Review is required',
                'review.min' => 'Review must be at least 10 characters',
                'review.max' => 'Review cannot exceed 1000 characters'
            ]);

            if ($validator->fails()) {
                return $this->validationRes($validator->errors());
            }
           
            $userId = Auth::user()->user_id;
            $productId = $request->product_id;

            $existingReview = ProductReview::where('uid', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                return $this->failRes('You have already reviewed this product');
            }

            // Check if user has purchased this product (optional validation)
            // You can add this validation if you want to ensure only buyers can review
            // $hasPurchased = Order::where('user_id', $userId)
            //     ->whereHas('orderItems', function($query) use ($productId) {
            //         $query->where('product_id', $productId);
            //     })
            //     ->where('order_status', 'delivered')
            //     ->exists();

            // if (!$hasPurchased) {
            //     return $this->failMsg('You can only review products you have purchased');
            // }

            $review = new ProductReview();
            $review->product_id = $productId;
            $review->uid = $userId;
            $review->rating = $request->rating;
            $review->review = trim($request->review);
            $review->save();

            if ($review) {
                return $this->successRes('Review submitted successfully!');
            }
            return $this->failRes('Failed to submit review. Please try again.');
        } catch (Exception $e) {
            return $this->failRes('An error occurred while submitting your review: ' . $e->getMessage());
        }
    }
}