<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ladder;
use View;
use DB;

class LadderController extends Controller
{
    public function index()
    {
        $table = 'ggg_ladder';
        $page = '1';
        $SCorHC = 'sc';
        $ladder = DB::table('ggg_ladder')
            ->select('rank', 'character_name',  'account_name', 'character_level', 'character_class')
            ->where('league', '=',  $SCorHC)
            ->whereBetween('rank', [$page * 100 - 99, $page * 100])
            ->groupBy('rank', $table . '.character_name', $table . '.account_name', 'character_level', 'character_class', $table . '.cached_since')
            ->orderBy('rank')
            ->get();

        return view('ladder', compact('ladder'));
    }

    public static function createPageButton($totalPage)
    {
        $output = '';
        if ($totalPage == 1) {
            $output .= '<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                . 'No Data' . '</button>';
            return $output;
        }
        $output .= '<button type="button" class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            .   'page' .
            '</button>';
        $output .= '<div class="dropdown-menu" style="height: auto;max-height: 500px;overflow-x: hidden;">';
        for ($i = 1; $i < $totalPage; $i++) {
            $p = $i * 100;
            $p1 = $p - 99;
            $output .= '<a class="dropdown-item dpitem' . $i . '" onclick="changePage(' . $i . ')">' . $p1 . '-' . $p . '</a>';
        }
        $output .= '</div></div>';
        return $output;
    }

    public function ajaxPost(Request $request)
    {
        $league = Request('league');
        $page = Request('page');

        if ($league == 'ggg_tmpsc' || $league == 'ggg_tmphc')
            $table = 'ggg_ladder';
        else
            $table = 'ggg_ssfladder';

        if ($league == 'ggg_tmpsc' || $league == 'ggg_tmpssfsc')
            $SCorHC = 'sc';
        else
            $SCorHC = 'hc';

        if (request()->ajax()) {
            $output = "";

            $cap = '<tr>
                <th scope="row">rank</th>
                <th scope="col">character</th>
                <th scope="col">account</th>
                <th scope="col">level</th>
                <th scope="col" class="d-none d-sm-table-cell">class</th>
            </tr>';

            $ladder = DB::table($table)
                ->select('rank', 'character_name',  'account_name', 'character_level', 'character_class', 'dead', 'online')
                ->where('league', '=',  $SCorHC)
                ->whereBetween('rank', [$page * 100 - 99, $page * 100])
                ->groupBy('rank', 'character_name', 'account_name', 'character_level', 'character_class',  'dead', 'online')
                ->orderBy('rank')
                ->get();

            $ladder_dec = json_decode(json_encode($ladder), true);

            foreach ($ladder_dec as $json_d) {
                $output .= '<tr>' .
                    '<th scope="row">' . $json_d['rank'] . '</th>';
                if ($json_d['dead']) $output .= '<td class="text-muted">' . $json_d['character_name'] . '</td>';
                else $output .= '<td>' . $json_d['character_name'] . '</td>';
                $output .= '<td>';
                if ($json_d['online'] == true) $output .= '<i class="fas fa-xs fa-circle fa-fw" style="color: Green;text-align: center;"></i>';
                else $output .= '<i class="fas fa-xs fa-circle fa-fw" style="color: Lightgray;text-align: center;"></i>';
                $output .= '<a href="/profile/' . $json_d['account_name'] . '" target="_blank" class="text-info">' .
                    $json_d['account_name'] . '</a></td>' .
                    '<td>' . $json_d['character_level'] . '</td>' .
                    '<td class="d-none d-sm-table-cell">' . $json_d['character_class'] . '</td>' .
                    '</tr>';
            }

            $totalPage = DB::table($table)
                ->max('rank') / 100 + 1;

            return Response()->json(['datatable' => $cap . $output, 'pagination' => self::createPageButton($totalPage)]);
        }
    }

    public function ajaxSearch(Request $request)
    {
        $league = Request('league');
        $page = Request('page');

        if ($league == 'ggg_tmpsc' || $league == 'ggg_tmphc')
            $table = 'ggg_ladder';
        else
            $table = 'ggg_ssfladder';

        if ($league == 'ggg_tmpsc' || $league == 'ggg_tmpssfsc')
            $SCorHC = 'sc';
        else
            $SCorHC = 'hc';

        if (request()->ajax()) {

            $output = "";

            $cap = '<tr>
                <th scope="row">rank</th>
                <th scope="col">character</th>
                <th scope="col">account</th>
                <th scope="col">level</th>
                <th scope="col" class="d-none d-sm-table-cell">class</th>
            </tr>';

            $searchItem = Request('searchItem');
            $temp = '';
            $temp = DB::table($table)
                ->select('rank',  'character_name', 'account_name', 'character_level', 'character_class', 'dead', 'online')
                ->where('league', '=',  $SCorHC)
                ->where('character_name', 'ilike', "%$searchItem%")
                ->orWhere('account_name', 'ilike', "%$searchItem%")
                // ->whereBetween('rank', [$page * 100 - 99, $page * 100])
                ->groupBy('rank',  'character_name', 'account_name', 'character_level', 'character_class', 'dead', 'online')
                ->orderBy('rank')
                ->get();

            $temp_dec = json_decode(json_encode($temp), true);

            foreach ($temp_dec as $json_d) {
                $output .= '<tr>' .
                    '<th scope="row">' . $json_d['rank'] . '</th>';
                if ($json_d['dead']) $output .= '<td class="text-muted">' . $json_d['character_name'] . '</td>';
                else $output .= '<td>' . $json_d['character_name'] . '</td>';
                $output .= '<td>';
                if ($json_d['online'] == true) $output .= '<i class="fas fa-xs fa-circle fa-fw" style="color: Green;text-align: center;"></i>';
                else $output .= '<i class="fas fa-xs fa-circle fa-fw" style="color: Lightgray;text-align: center;"></i>';
                $output .= '<a href="/profile/' . $json_d['account_name'] . '" target="_blank" class="text-info">' .
                    $json_d['account_name'] . '</a></td>' .
                    '<td>' . $json_d['character_level'] . '</td>' .
                    '<td class="d-none d-sm-table-cell">' . $json_d['character_class'] . '</td>' .
                    '</tr>';
            }
            $pagination = '<button type="button" class="btn btn-sm btn-danger" aria-haspopup="true" aria-expanded="false" onclick="changePage(1)">'
                . 'clean search' . '</button>';
            return Response()->json(['datatable' => $cap . $output, 'pagination' => $pagination]);
        }
    }
}