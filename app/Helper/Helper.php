<?php

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (!function_exists('uniqCode')) {
    function uniqCode($lenght)
    {
        // uniqCode
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return strtoupper(substr(bin2hex($bytes), 0, $lenght));
    }
}

if (!function_exists('singleFile')) {
    function singleFile($file, $folder)
    {
        $folder = strtolower($folder);
        if ($file) {
            if (!file_exists($folder))
                mkdir($folder, 0777, true);

            $destinationPath = public_path() . '/' . $folder;
            $profileImage = date('YmdHis') . rand(111, 999) . "." . $file->getClientOriginalExtension();
            $file->move($destinationPath, $profileImage);
            $fileName = "$profileImage";

            return asset($folder) . '/' . $fileName;
        }
        return false;
    }
}


if (!function_exists('multiFile')) {

    function multiFile($files, $folder)
    {
        $fileNames = [];
        foreach ($files as $key => $file) {
            if ($file) {
                if (!file_exists($folder))
                    mkdir($folder, 0777, true);

                $filename = date('YmdHis') . rand(111, 999) . "." . $file->getClientOriginalExtension();
                $file->move(public_path() . '/' . $folder, $filename);
                $fileNames[$key] =  asset($folder) . '/' . $filename; //$filename;
            }
        }
        return $fileNames;
    }
}


if (!function_exists('pr')) {
    function pr($data)
    {
        echo "<pre>";
        print_r($data);
        echo '</pre>';
        die;
    }
}

function ip_address()
{
    return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
}


function status($stauts)
{

    if ($stauts) {
        return '<span class="badge bg-label-success">Active</span>';
    } else {
        return '<span class="badge bg-label-danger">Inactive</span>';
    }
}


function daterange($data)
{

    if (!empty($data['date_range'])) {
        $dateRange = $data['date_range'];
        $date = explode('-', $dateRange);
        list($start_date, $end_date) = $date;
        $start_date = date('m/d/Y', strtotime(trim($start_date)));
        $end_date   = date('m/d/Y', strtotime(trim($end_date)));
    } else {
        // strtotime('-29 days', 
        $crrMonth = date('2023-01-01'); //(date('Y-m-d'));
        $start_date = (trim(date("m/d/Y", strtotime($crrMonth))));
        $end_date   = (trim(date('m/d/Y')));
    }

    return '
    <input type="text" name="date_range" id="daterange-btn" class=" form-control" value="' . $start_date . ' - ' . $end_date . '" />';
}



function user()
{

    $user = User::where('role', 'supperadmin')->first();
    return $user ?? [];
}

function mSign($amount)
{

    $amount = number_format($amount, 2);
    return '₹ ' . $amount;
}

function getPerValue($_per, $amount)
{
    return ($_per / 100) * $amount;
}

if (!function_exists('generateSlug')) {
    function generateSlug($name, $collection = null, $column = 'slug')
    {
        $slug = Str::slug($name);
        
        if ($collection) {
            $originalSlug = $slug;
            $count = 1;
        
            while (\DB::connection('mongodb')->collection($collection)->where($column, $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }
        
        return $slug;
    }
}
