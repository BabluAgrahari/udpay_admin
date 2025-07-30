<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniQrcode extends Model
{
    protected $table = 'uniqrcode';
    public $timestamps=false;
    use HasFactory;
}
