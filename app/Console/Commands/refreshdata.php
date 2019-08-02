<?php

namespace App\Console\Commands;

// ini_set('memory_limit',  '1024M');

use Illuminate\Console\Command;
use DB;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;

class refreshdata extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:refreshdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {

        function saveData($x, $value_d, $cached_since, $start_time)
        {
            if ($x == 0 || $x == 3) {
                $table = 'ggg_ladder';
                $table_h = 'ggg_ladder_history';
            } else if ($x == 1 || $x == 2) {
                $table = 'ggg_ssfladder';
                $table_h = 'ggg_ssfladder_history';
            }

            if ($x == 0 || $x == 1)
                $league = 'hc';
            else if ($x == 2 || $x == 3)
                $league = 'sc';

            $character = json_decode(json_encode($value_d['character']), true);
            $account = json_decode(json_encode($value_d['account']), true);
            if (empty($character['depth'])) {
                $character['depth']['default'] = 0;
                $character['depth']['solo'] = 0;
            }
            DB::table($table)->where('rank', '=', $value_d['rank'])->where('league', '=', $league)->delete();
            DB::table($table)->insert(array(
                'character_id' => $character['id'],
                'cached_since' => $cached_since,
                'character_name' => $character['name'],
                'account_name' => $account['name'],
                'league' => $league,
                'rank' => $value_d['rank'],
                'dead' => $value_d['dead'],
                'online' => $value_d['online'],
                'character_level' => $character['level'],
                'character_class' => $character['class'],
                'character_experience' => $character['experience'],
                'character_depth_default' => $character['depth']['default'],
                'character_depth_solo' => $character['depth']['solo'],
                'account_challenges_total' => $account['challenges']['total'],
            ));
            DB::table($table_h)->insert(array(
                'character_id' => $character['id'],
                'cached_since' => $cached_since,
                'character_name' => $character['name'],
                'account_name' => $account['name'],
                'league' => $league,
                'rank' => $value_d['rank'],
                'dead' => $value_d['dead'],
                'online' => $value_d['online'],
                'character_level' => $character['level'],
                'character_class' => $character['class'],
                'character_experience' => $character['experience'],
                'character_depth_default' => $character['depth']['default'],
                'character_depth_solo' => $character['depth']['solo'],
                'account_challenges_total' => $account['challenges']['total'],
            ));
            $collecting_time = microtime(true) - $start_time;
            $formatted = sprintf("[%5d/15000] time= %4.3f s.", $value_d['rank'], $collecting_time);
            echo $formatted;
            print_r(' name= ' .  $character['name']);
            echo "\n";
        }

        $start_time = microtime(true);

        // $mv_time = microtime(true) - $start_time;
        // $formatted = sprintf("move success. time= %4.3f s.", $mv_time);
        // echo $formatted;
        // print_r('move success. time= ' . $mv_time . ' s');
        echo "\n";
        sleep(5);
        $client = new Client();

        print_r('start refreshing ...');
        echo "\n";

        $url_legion_array = array(
            "/Hardcore%20Legion?offset=", "/SSF%20Legion%20HC?offset=",
            "/SSF%20Legion?offset=", "/Legion?offset="
        );

        $start_time = microtime(true);
        for ($x = 0; $x < 4; $x++) {
            $url = 'http://api.pathofexile.com/ladders' . $url_legion_array[$x];

            $urls = []; //清除
            for ($y = 0; $y < 15000; $y += 200) {
                $urls[] =  $url . $y . '&limit=200';
            }

            $generator = function (array $urls) {
                foreach ($urls as $url) {
                    $request = new Request('GET', $url);
                    yield $request;
                }
            };

            $pool = new Pool($client, $generator($urls), [
                'concurrency' => 5,
                'fulfilled'   => function ($response) use ($x, $start_time) {
                    $res_dec = json_decode($response->getBody(), true);
                    foreach ($res_dec['entries'] as $value_d) {
                        print_r('saving [' . $x . '/3]');
                        saveData($x, $value_d, $res_dec['cached_since'], $start_time);
                    }
                },
                'rejected' => function ($reason) {
                    $this->error("rejected");
                    $this->error("rejected reason: " . $reason);
                },
            ]);

            $promise = $pool->promise();
            $promise->wait();
        }
        print_r('refresh success!');
        echo "\n";
    }
}
