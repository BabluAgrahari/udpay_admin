<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Traits\WebResponse;

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
                return $this->validationMsg($validator->errors());
            }
           
            $userId = Auth::user()->user_id;
            $productId = $request->product_id;

            $existingReview = ProductReview::where('uid', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existingReview) {
                return $this->failMsg('You have already reviewed this product');
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
                $product = Product::find($productId);
                
                return $this->successMsg('Review submitted successfully!', [
                    'review' => [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'review' => $review->review,
                        'created_at' => date('M d, Y', strtotime($review->created_at)),
                        'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name
                    ],
                    'product' => [
                        'id' => $product->id,
                        'name' => $product->product_name,
                        'slug' => $product->slug_url
                    ]
                ]);
            }
            return $this->failMsg('Failed to submit review. Please try again.');
        } catch (Exception $e) {
            return $this->failMsg('An error occurred while submitting your review: ' . $e->getMessage());
        }
    }

   
    public function getProductReviews(Request $request, $productId)
    {
        try {
            $reviews = ProductReview::with('user')
                ->where('product_id', $productId)
                // ->where('status', '1')
                ->orderBy('created_at', 'desc')
                ->get();

            $reviewData = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'created_at' => $review->created_at->format('M d, Y'),
                    'user_name' => $review->user ? ($review->user->first_name . ' ' . $review->user->last_name) : 'Anonymous'
                ];
            });

            return response()->json([
                'status' => true,
                'reviews' => $reviewData,
                'total_reviews' => $reviews->count(),
                'average_rating' => $reviews->avg('rating') ? round($reviews->avg('rating'), 1) : 0
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to load reviews'
            ]);
        }
    }

    public function checkUserReview(Request $request, $productId)
    {
        try {
            if (!Auth::check()) {
                return $this->failMsg('Please login to review this product');
            }

            $existingReview = ProductReview::where('uid', Auth::user()->user_id)
                ->where('product_id', $productId)
                ->first();

            return $this->successMsg('Review status checked successfully!', [
                'has_reviewed' => $existingReview ? true : false,
                'review' => $existingReview ? [
                    'id' => $existingReview->id,
                    'rating' => $existingReview->rating,
                    'review' => $existingReview->review,
                    'created_at' => $existingReview->created_at->format('M d, Y')
                ] : null
            ]);

        } catch (Exception $e) {
            return $this->failMsg('Failed to check review status: ' . $e->getMessage());
        }
    }
} 