<?php

namespace App\Models;

use App\Observers\Timestamp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use MongoDB\Laravel\Eloquent\Model;
// use MongoDB\Laravel\Eloquent\SoftDeletes;


class BaseModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    const DELETED_AT = 'deleted';


    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();
        self::observe(Timestamp::class);
    }


    public function scopeStatus($query)
    {
        $query->where('status', 1);
    }

    public function scopeDateRang($query, $dateRange = '')
    {
        if (!empty($dateRange)) {
            $date = explode('-', $dateRange);
            list($start_date, $end_date) = $date;
           
            $start_date = strtotime(trim($start_date) . " 00:00:00");
            $end_date = strtotime(trim($end_date) . " 23:59:59");
            $query->whereBetween('created', [$start_date, $end_date]);
        } else {
            $crrMonth = (date('Y-m-d'));
            $start_date = strtotime(trim(date("d-m-Y", strtotime('-29 days', strtotime($crrMonth)))) . " 00:00:00");
            $end_date = strtotime(trim(date('Y-m-d')) . " 23:59:59");
        }

        // $query->whereBetween('created', [$start_date, $end_date]);
    }

    public function scopeDateRange($query, $dateRange = '', $type = false)
    {
        if (!empty($dateRange)) {
            $date = explode('-', $dateRange);
            list($start_date, $end_date) = $date;
            // echo $start_date;
            // echo "/";
            // echo $end_date;die;
            $start_date = strtotime(trim($start_date) . " 00:00:00");
            $end_date = strtotime(trim($end_date) . " 23:59:59");
            $query->whereBetween('created', [$start_date, $end_date]);
        } else {
            $crrMonth = (date('Y-m-d'));
            $days = '-29';
            if ($type)
                $days = '-365';
            $start_date = strtotime(trim(date("d-m-Y", strtotime($days . ' days', strtotime($crrMonth)))) . " 00:00:00");
            $end_date = strtotime(trim(date('Y-m-d')) . " 23:59:59");
        }

        // $query->whereBetween('created', [$start_date, $end_date]);
    }


    public function dFormat($date)
    {
        if (empty($date))
            return false;

        return date('d M,Y', (int)$date);
    }
}
