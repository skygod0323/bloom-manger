<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Transaction;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transactions = Transaction::with('user')->orderBy('time', 'desc')->get();
        return view('transactions', compact('transactions'));
    }

    public function punch_chains() {
        $transactions = Transaction::with('user')->whereNotNull('block')->get()->toArray();

        $punchs = [];
        foreach($transactions as $tx) {
            if(isset($punchs[$tx['user_id']])) {
                $punchs[$tx['user_id']]['user']['total'] += (float)$tx['token_amount'];
            } else {
                $punchs[$tx['user_id']] = [];
                $punchs[$tx['user_id']]['user'] = $tx['user'];
                $punchs[$tx['user_id']]['user']['total'] = (float)$tx['token_amount'];
                $punchs[$tx['user_id']]['punches'] = [];
            }
            unset($tx['user']);
            array_push($punchs[$tx['user_id']]['punches'], $tx);
        }

        return view('punch_chains', compact('punchs'));
    }
}
