<?php

namespace App\Models;

use App\Models\BaseModel;

class ProductReel extends BaseModel
{
    protected $table = 'uni_product_reels';
    
    protected $fillable = [
        'product_id',
        'path',
        'status',
        'is_video',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

   
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

   
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    public function getIsVideoAttribute()
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv'];
        return in_array(strtolower($this->file_extension), $videoExtensions);
    }

    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }
} 