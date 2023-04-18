<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\AccountCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountsController extends Controller
{
    public function get(Request $request)
    {
        $list = Account::query()->where('user_id', $request->user()->id)->get();

        foreach ($list as $item) {
            if ($item->type === 'credit') {
                $item->summary = AccountCharge::query()->select(
                    'who',
                    DB::raw("SUM(CASE WHEN msi IS NULL OR msi = 1 THEN amount ELSE amount/msi END) AS total")
                )->where('account_id', $item->id)
                ->groupBy('who')
                ->get();

                $item->payment = AccountCharge::query()->select(
                    DB::raw("SUM(CASE WHEN msi IS NULL OR msi = 1 THEN amount ELSE amount/msi END) AS total")
                )->where('account_id', $item->id)
                ->first();
            }
        }

        Log::info(json_encode($list));

        return response()->json($list);
    }

    public function persist(Request $request)
    {
        $account = new Account();
        $account->name = $request->get('name');
        $account->type = $request->get('type');
        $account->amount = $request->get('amount');
        $account->user_id = $request->user()->id;
        $account->save();

        return response()->json($account);
    }
}
