<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Entities\Setting;

class SettingController extends Controller
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
        $tmp = Setting::get()->toArray();

        $settings = [];
        foreach($tmp as $item) {
            $settings[$item['key']] = $item['value'];
        }

        return view('settings', compact('settings'));
    }

    public function update(Request $request) {
        $params = $request->input();

        
        $new_data = array(
            'token_price' => $params['token_price'],
            'wallet_address' => $params['wallet_address'],
            'private_key' => $params['private_key'],
            'contract_address' => $params['contract_address'],
            'contract_abi' => $params['contract_abi'],
            'sale_progress' => $params['sale_progress']
        );

        foreach($new_data as $key => $value) {
            Setting::setByKey($key, $value);
        }


        //// get updated value
        $tmp = Setting::get()->toArray();

        $settings = [];
        foreach($tmp as $item) {
            $settings[$item['key']] = $item['value'];
        }
        return view('settings', compact('settings'));
    }
}
