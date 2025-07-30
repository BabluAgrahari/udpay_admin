<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelCount extends Model
{
    protected $table = 'level_count';
    public $timestamps=false;
    use HasFactory;
}
