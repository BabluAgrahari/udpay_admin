<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class Timestamp
{
    public function saving($model)
    {

        if (empty($model->_id)) {
            // $model->user_id = Auth::user()->_id ?? 0;
            // $model->parent_id = Auth::user()->parent_id ?? 0;
            $model->created = time();
        }

        $model->updated = time();
    }
}
