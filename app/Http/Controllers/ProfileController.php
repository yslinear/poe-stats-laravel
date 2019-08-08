<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DateTime;
use function GuzzleHttp\json_encode;

class ProfileController extends Controller
{
    //
    public function index(Request $request)
    {
        $account = $request->route('account');
        // echo $account;

        $ret = '<button onclick="myFunction()>' . $account . '</button>';
        return view('profile', compact('account'));
    }

    public function GetChartData(Request $request)
    {
        $account = Request('account');
        $timezone  = Request('timezone');
        $result = array();
        for ($i = 0; $i  < 2; $i++) {

            if ($i == 0) $table = 'ggg_ladder_history';
            else $table = 'ggg_ssfladder_history';
            $character_name = DB::table($table)->select('character_id')->where('account_name', '=', $account)
                ->groupby('character_id')->orderby(DB::raw('max(rank)'))->get();

            $data = DB::table($table)->select('cached_since',  'online')->where('account_name', '=', $account)
                ->groupby('cached_since',  'online')->orderby('cached_since')->get();
            $data_dec = json_decode(json_encode($data), true);
            foreach ($data_dec as $json_e) {
                $ntime = new DateTime($json_e['cached_since'], new \DateTimeZone($timezone));

                $time = array((int) $ntime->format('H'), (int) $ntime->format('i'), (int) $ntime->format('s'));
                if ($json_e['online'] == true) $insert2array = array((int) $ntime->format('w'), $time, null);
                else $insert2array = array((int) $ntime->format('w'),  null, $time);
                array_push($result, $insert2array);
            }
            if ($result != null) return $result;
        }

        return $result;
    }
}