<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Traits\WebResponse;

class ProductReelController extends Controller
{
    use WebResponse;

    public function index($productId)
    {
        try {
            $data['product'] = Product::findOrFail($productId);
            $data['reels'] = ProductReel::where('product_id', $productId)->orderBy('created_at', 'desc')->get();

            return view('CRM.Product.reels', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(Request $request, $productId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files.*' => 'required|file|mimes:jpg,jpeg,png,gif,bmp,webp,mp4,avi,mov,wmv,flv,webm,mkv|max:102400', // 100MB max
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }

            $product = Product::findOrFail($productId);
            $uploadedFiles = [];

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filePath = singleFile($file, 'product_reels');

                    if ($filePath) {
                        $reel = ProductReel::create([
                            'product_id' => $productId,
                            'path' => $filePath,
                            'status' => 1,
                            'is_video' => $file->getClientOriginalExtension() == 'mp4' ? 1 : 0,
                           
                        ]);

                        $uploadedFiles[] = [
                            'id' => $reel->id,
                            'path' => $reel->path,
                            'is_video' => $reel->is_video,
                            'is_image' => $reel->is_image,
                            'file_extension' => $reel->file_extension
                        ];
                    }
                }
            }

            return $this->successMsg('Files uploaded successfully', $uploadedFiles);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request, $reelId)
    {
        try {
            $reel = ProductReel::findOrFail($reelId);
            $reel->status = $request->status;
            $reel->save();

            return $this->successMsg('Status updated successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($reelId)
    {
        try {
            $reel = ProductReel::findOrFail($reelId);

            // Delete physical file
            if ($reel->path) {
                $filePath = public_path(str_replace(asset(''), '', $reel->path));
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $reel->delete();

            return $this->successMsg('File deleted successfully');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function getReels($productId)
    {
        try {
            $reels = ProductReel::where('product_id', $productId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($reel) {
                    return [
                        'id' => $reel->id,
                        'path' => $reel->path,
                        'status' => $reel->status,
                        'is_video' => $reel->is_video,
                        'is_image' => $reel->is_image,
                        'file_extension' => $reel->file_extension,
                        'created_at' => !empty($reel->created_at) ? date('d-m-Y H:i:s', strtotime($reel->created_at)) : null
                    ];
                });

            return $this->successMsg('Reels fetched successfully', $reels);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}
