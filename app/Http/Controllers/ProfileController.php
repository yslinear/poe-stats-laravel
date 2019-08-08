<?php

namespace App\Http\Controllers;

use DateTime;
use DB;
use function GuzzleHttp\json_encode;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->route('account');

        $ret = '<button onclick="myFunction()>' . $account . '</button>';
        return view('profile', compact('account'));
    }

    public function GetChartData(Request $request)
    {
        $account = Request('account');
        $timezone = Request('timezone');

        $result = array();
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 24; $j++) {
                array_push($result, [$i, $j, 0]);
            }
        }

        for ($i = 0; $i < 2; $i++) {
            if ($i == 0) {
                $table = 'ggg_ladder_history';
            } else {
                $table = 'ggg_ssfladder_history';
            }

            $character_name = DB::table($table)->select('character_id')->where('account_name', '=', $account)
                ->groupby('character_id')->orderby(DB::raw('max(rank)'))->get();

            $data = DB::table($table)->select('cached_since', 'online')->where('account_name', '=', $account)
                ->groupby('cached_since', 'online')->orderby('cached_since')->get();
            $data_dec = json_decode(json_encode($data), true);
            foreach ($data_dec as $json_e) {
                $ntime = new DateTime($json_e['cached_since'], new \DateTimeZone($timezone));
                $day = (int) $ntime->format('w');
                $hour = (int) $ntime->format('H');
                // $time = array((int) , (int) $ntime->format('i'), (int) $ntime->format('s'));
                if ($json_e['online'] == true) {
                    foreach ($result as $key => $field) {
                        if ($result[$key][0] == $day && $result[$key][1] == $hour) {
                            $result[$key][2]++;
                        }

                    }
                }
            }
            if ($result != null) {
                return $result;
            }
        }
        return $result;
    }
}
