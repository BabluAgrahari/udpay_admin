<?php

namespace App\Services;

use App\Models\LevelCount;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    private function distributePayout($totalAmount, $orderId)
    {
        $bv = 1;
        $levelRules = [
            1 => ['direct' => 0, 'percent' => 0.4],
            2 => ['direct' => 1, 'percent' => 0.08],
            3 => ['direct' => 1, 'percent' => 0.05],
            4 => ['direct' => 2, 'percent' => 0.04],
            5 => ['direct' => 2, 'percent' => 0.03],
            6 => ['direct' => 2, 'percent' => 0.02],
            7 => ['direct' => 3, 'percent' => 0.02],
            8 => ['direct' => 3, 'percent' => 0.02],
            9 => ['direct' => 3, 'percent' => 0.02],
            10 => ['direct' => 4, 'percent' => 0.02],
            11 => ['direct' => 4, 'percent' => 0.01],
            12 => ['direct' => 4, 'percent' => 0.01],
            13 => ['direct' => 5, 'percent' => 0.01],
            14 => ['direct' => 5, 'percent' => 0.01],
            15 => ['direct' => 5, 'percent' => 0.01],
        ];
        $levelMembers = LevelCount::where('child_id', Auth::user()->user_nm)
            ->whereBetween('level', [2, 15])
            ->get();
        if ($levelMembers->isEmpty()) {
            Log::info('No level members found for user: ' . Auth::user()->user_nm);

            return;
        }

        $parentIds = $levelMembers->pluck('parent_id')->unique()->values();
        $users = User::whereIn('user_nm', $parentIds)->get()->keyBy('user_nm');

        $directCounts = User::select('refid', DB::raw('COUNT(*) as count'))
            ->whereIn('refid', $parentIds)
            ->where('isactive', 1)
            ->groupBy('refid')
            ->pluck('count', 'refid');

        foreach ($levelMembers as $lvl) {
            $level = $lvl->level;

            if (!isset($levelRules[$level])) {
                continue;
            }

            $rule = $levelRules[$level];
            $uidDisplay = 'UNI' . $lvl->child_id;

            $user = $users->get($lvl->parent_id);
            if (!$user) {
                Log::debug("User not found for parent_id: {$lvl->parent_id}");
                continue;
            }

            if ($user->isactive != 1 || $user->restricted != 0) {
                Log::info("User {$lvl->parent_id} is inactive or restricted at level $level.");
                continue;
            }

            $directCount = $directCounts->get($lvl->parent_id, 0);

            if ($directCount < $rule['direct']) {
                Log::info("User {$lvl->parent_id} missed Level $level bonus. Required directs: {$rule['direct']}, Found: $directCount");
                continue;
            }

            $amount = $totalAmount * $rule['percent'] * $bv;
            $inType = "Start Bonus from {$uidDisplay} - level {$level}";

            if (insertPayout($amount, $lvl->parent_id, $inType, $lvl->child_id, $orderId, $totalAmount, $level)) {
                addWallet1(1, $lvl->parent_id, $amount, $orderId, 'gen_payout');
            } else {
                Log::info("$amount not inserted at Level $level >>> Payout user id: {$lvl->parent_id}");
            }
        }
    }

    private function checkWallet($amount, $orderId, $types, $totDiscount, $checkBalance = false)
    {
        $wallet = Wallet::where('userid', Auth::user()->user_id)->first();
        if (!$wallet) {
            return ['status' => false, 'msg' => 'Wallet not found.'];
        }

        $point = $totDiscount;
        $bpAfter = $wallet->bp - $point;

        $totalAvailable = $wallet->earning + $wallet->unicash + $wallet->amount;

        if ($totalAvailable < $amount) {
            return ['status' => false, 'msg' => 'Insufficient Amount.'];
        }

        if ($checkBalance) {
            return ['status' => true, 'msg' => 'For check balance'];
        }

        $amtFromAmount = 0;
        $amtFromUnicash = 0;
        $amtFromEarning = 0;
        $description = '';

        $remaining = $amount;

        if ($wallet->earning >= $remaining) {
            $amtFromEarning = $remaining;
            $wallet->earning -= $remaining;
            $remaining = 0;
        } else {
            $amtFromEarning = $wallet->earning;
            $remaining -= $wallet->earning;
            $wallet->earning = 0;
        }

        if ($remaining > 0) {
            if ($wallet->unicash >= $remaining) {
                $amtFromUnicash = $remaining;
                $wallet->unicash -= $remaining;
                $remaining = 0;
            } else {
                $amtFromUnicash = $wallet->unicash;
                $remaining -= $wallet->unicash;
                $wallet->unicash = 0;
            }
        }

        if ($remaining > 0) {
            $amtFromAmount = $remaining;
            $wallet->amount -= $remaining;
            $remaining = 0;
        }

        $wallet->bp = $bpAfter;

        if (!$wallet->save()) {
            return ['status' => false, 'msg' => 'Something went wrong, wallet not updated.'];
        }

        $balanceAfter = $wallet->earning + $wallet->unicash + $wallet->amount;

        $transPayload = [
            'unm' => $wallet->unm,
            'user_id' => Auth::user()->user_id,
            'transition_type' => $types,
            'debit' => $amount,
            'balance' => $balanceAfter,
            'in_type' => "Your Wallet is Debited {$amount} for {$types} to {$orderId}",
            'description' => "Order Amount Deducted from amount: {$amtFromAmount} unicash: {$amtFromUnicash} earning: {$amtFromEarning} bp: {$bpAfter}",
            'amount' => $amtFromAmount,
            'unicash' => $amtFromUnicash,
            'earning' => $amtFromEarning,
            'unipoint' => $point,
            'order_id' => $orderId
        ];

        if (walletTransaction($transPayload)) {
            return ['status' => true, 'msg' => 'Wallet updated Successfully.'];
        }

        return ['status' => false, 'msg' => 'Amount not debited.'];
    }
}
