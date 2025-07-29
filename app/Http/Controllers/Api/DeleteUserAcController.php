<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\DeleteAcModel;
use Exception;
use Illuminate\Support\Facades\Auth;

class DeleteUserAcController extends Controller
{
    public function index(Request $request)
    {
        try {
            $insQry = new DeleteAcModel();
            $insQry->status = 0;
            $insQry->uid    = Auth::user()->user_id;;
            if ($insQry->save()) {
                return $this->successRes('Deletation process started.');
            } else {
                return $this->failRes('Something went wrong, Account not deleted.');
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
