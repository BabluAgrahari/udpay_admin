<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\WebResponse;
use Exception;

class ReviewController extends Controller
{
    use WebResponse;

    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        try {
            $products = Product::where('status', '1')->get();
            return view('CRM.Review.index', compact('products'));
        } catch (Exception $e) {
            return $this->failMsg('Error loading reviews: ' . $e->getMessage());
        }
    }

    /**
     * Get reviews for DataTable
     */
    public function datatable(Request $request)
    {
        try {
            $query = ProductReview::with(['product', 'user']);

            // Filtering
            if ($request->search && $request->search['value']) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('review', 'like', "%{$search}%")
                      ->orWhereHas('product', function ($productQuery) use ($search) {
                          $productQuery->where('product_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($request->status !== null && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Product filter
            if ($request->product_id !== null && $request->product_id !== '') {
                $query->where('product_id', $request->product_id);
            }

            // Rating filter
            if ($request->rating !== null && $request->rating !== '') {
                $query->where('rating', $request->rating);
            }

            $total = $query->count();

            // Ordering
            $columns = $request->columns;
            if ($request->order && count($request->order)) {
                foreach ($request->order as $order) {
                    $colIdx = $order['column'];
                    $colName = $columns[$colIdx]['data'];
                    $dir = $order['dir'];
                    
                    // Handle special column ordering
                    if ($colName === 'product_name') {
                        $query->join('uni_products', 'users_review.product_id', '=', 'uni_products.id')
                              ->orderBy('uni_products.product_name', $dir);
                    } elseif ($colName === 'user_name') {
                        $query->join('users', 'users_review.uid', '=', 'users.id')
                              ->orderBy('users.name', $dir);
                    } elseif ($colName === 'created_at') {
                        $query->orderBy('users_review.created_at', $dir);
                    } else {
                        $query->orderBy('users_review.' . $colName, $dir);
                    }
                }
            } else {
                $query->orderBy('users_review.created_at', 'desc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $reviews = $query->skip($start)->take($length)->get();

            $data = $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'product_image' => $review->product->product_image ? asset($review->product->product_image) : asset('front_assets/images/no_image.jpeg'),
                    'product_name' => $review->product->product_name??'',
                    'user_name' => $review->user->name??'',
                    'user_email' => $review->user->email??'',
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'created_at' => date('M d, Y H:i', strtotime($review->created_at)),
                ];
            });

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show review details in popup
     */
    public function show($id)
    {
        try {
            $review = ProductReview::with(['product', 'user'])->findOrFail($id);
            
            return response()->json([
                'status' => true,
                'review' => [
                    'id' => $review->id,
                    'product_name' => $review->product->product_name??'',
                    'product_image' => $review->product->product_image??'',
                    'user_name' => $review->user->name??'',
                    'user_email' => $review->user->email??'',
                    'rating' => $review->rating,
                    'review' => $review->review,
                    'status' => $review->status,
                    'created_at' => date('M d, Y H:i', strtotime($review->created_at)),
                    'updated_at' => date('M d, Y H:i', strtotime($review->updated_at))
                ]
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Review not found'
            ]);
        }
    }

    /**
     * Update review status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $review = ProductReview::findOrFail($id);
            $review->status = $request->status;
            $review->save();

            return $this->successMsg('Review status updated successfully');

        } catch (Exception $e) {
            return $this->failMsg('Error updating review status: ' . $e->getMessage());
        }
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        try {
            $review = ProductReview::findOrFail($id);
            $review->delete();

            return $this->successMsg('Review deleted successfully');

        } catch (Exception $e) {
            return $this->failMsg('Error deleting review: ' . $e->getMessage());
        }
    }

    /**
     * Get review statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_reviews' => ProductReview::count(),
                'active_reviews' => ProductReview::where('status', '1')->count(),
                'inactive_reviews' => ProductReview::where('status', '0')->count(),
                'average_rating' => round(ProductReview::where('status', '1')->avg('rating'), 1),
                'rating_distribution' => [
                    '5_star' => ProductReview::where('rating', 5)->where('status', '1')->count(),
                    '4_star' => ProductReview::where('rating', 4)->where('status', '1')->count(),
                    '3_star' => ProductReview::where('rating', 3)->where('status', '1')->count(),
                    '2_star' => ProductReview::where('rating', 2)->where('status', '1')->count(),
                    '1_star' => ProductReview::where('rating', 1)->where('status', '1')->count(),
                ]
            ];

            return response()->json([
                'status' => true,
                'stats' => $stats
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error loading statistics'
            ]);
        }
    }
} 