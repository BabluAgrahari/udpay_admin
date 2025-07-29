<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\AppPopup;
use Exception;

class AppPopupController extends Controller
{
    public function getList()
    {
        try {
            $records = AppPopup::where('status', '1')->orderBy('id', 'desc')->get()->map(function ($record) {
                return [
                    'title'             => $record->title,
                    'detail_msg'        => $record->details,
                    'screen_name'       => $record->screen_name,
                    'amount'            => $record->amount,
                    'image'             => $record->img,
                    'popup_type'        => $record->popup_type,
                    'redirect_url'      => $record->redirect_url ?? '',
                ];
            });
            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
