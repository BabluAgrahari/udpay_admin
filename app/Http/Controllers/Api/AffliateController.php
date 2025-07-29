<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\InrCollection;
use App\Models\InrClick;
use Exception;
use Illuminate\Support\Facades\Auth;

class AffliateController extends Controller
{
	public function index()
	{
		try {
			$front = InrCollection::where('aff_type', 'front')->orderBy('priority', 'asc')->get();

			$records = [];
			$records['front'] = [];
			if ($front->isNotEmpty()) {
				foreach ($front as $res) {
					$records['front'][] = [
						'affliate_id'  => $res->id,
						'merchant'     => $res->Merchant,
						'category'     => $res->Category,
						'payout'       => ($res->Payout_type == 'cpa_percentage') ? round($res->payout / 4, 2) . '%' : round($res->payout / 4, 2) . ' Flat Rs.',
						'payout_type'  => $res->Payout_type,
						'urlTp'        => 'url_' . $res->aff_for,
						'img_link'     => $res->img,
						'status'       => ($res->status == '1') ? 'Active' : 'Pause',
					];
				}
			}
			$getResponse = InrCollection::get();
			$records['data'] = [];
			if ($getResponse->isNotEmpty()) {
				foreach ($getResponse as $res) {
					$records['data'][] = [
						'affliate_id'  => $res->id,
						'merchant'     => $res->Merchant,
						'category'     => $res->Category,
						'payout'       => ($res->Payout_type == 'cpa_percentage') ? round($res->payout / 4, 2) . '%' : round($res->payout / 4, 2) . ' Flat Rs.',
						'payout_type'  => $res->Payout_type,
						'url'          => $res->Link,
						'img_link'     => $res->img,
						'status'       => ($res->status == '1') ? 'Active' : 'Pause',
					];
				}
			}
			return $this->recordRes($records);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}


	public function save(Request $request)
	{
		try {
			$validator = Validator::make($request->all(), [
				'affliate_id' => 'required|numeric',
			]);
			if ($validator->fails())
				return validationRes($validator->messages());

			$aff_id = $request->affliate_id;
			$uid = Auth::user()->user_id;

			if ($aff_id == 1522) {
				$url       = 'https://tracking.vcommission.com/aff_c?offer_id=412&aff_id=1522&aff_sub=' . $uid . 'X' . strtotime(date('Y-m-d H:i:s'));
				return $this->recordRes(['new_url' => $url]);
			}

			$res = InrCollection::where('id', $aff_id)->first();
			if (empty($res))
				return $this->failRes('Record Not Found.');

			$sub_id = $uid . 'X' . strtotime(date('Y-m-d H:i:s'));
			$resRow = InrClick::where('click_date', date('Y-m-d H:i:s'))->where('sub_id', $sub_id)->where('merchant', $res->Merchant)->first();
			if (!empty($resRow))
				return $this->recordRes(['new_url' => $res->Link . '&subid=' . $resRow->sub_id]);

			$ins = new InrClick();
			$ins->uid            = $uid;
			$ins->aff_id         = $aff_id;
			$ins->merchant       = $res->Merchant;
			$ins->sub_id         = 'U' . $uid . 'X' . strtotime(date('Y-m-d H:i:s'));;
			$ins->click_date     = date('Y-m-d');
			$ins->transaction_id = '';
			$ins->payout         = 0;
			$ins->user_payout    = 0;
			$ins->sale_amount    = 0;
			$ins->conv_date      = '';
			$ins->sale_date      = '';
			$ins->status         = '';
			$ins->payout_status  = '';
			if (!$ins->save())
				return $this->failRes('Something Went Wrong.');

			$insert_id = $ins->id;
			$resRow    = InrClick::where('id', $insert_id)->first();
			$url       = $res->Link . '&subid=' . $resRow->sub_id;
			return $this->recordRes(['new_url' => $url]);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}


	public function getClickList()
	{
		try {
			$records = InrClick::with('allRes')->where('uid', Auth::User()->user_id)->orderBy('created_on', 'desc')->get()->map(function ($record) {
				return [
					'merchant' => $record->Merchant ?? '',
					'img_link' => $record->allRes->img ?? '',
					'date'     => $record->created_on,
					'status'   => $record->status == 0 ? 'Pending' : "Success",
					'payout'   => 0.00,
					'color'    => $record->status == 0 ? '#fffff' : '#1ed12d',
				];
			});
			if ($records->isEmpty())
				return $this->failRes('Record Not Found.');

			return $this->recordsRes($records);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}
}
