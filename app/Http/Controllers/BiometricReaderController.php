<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BiometricReaderController extends Controller
{
    //

    public function index()
    {

    }

    public function read()
    {
        $response = Http::accept('application/json')->get('127.0.0.1/zk/');

        $att = [];

        // var_dump(json_decode($response));
        $logs = json_decode($response);

        if($logs->Row)
        {
            foreach($logs->Row as $log)
            {
                $datetime = Carbon::createFromFormat('Y-m-d H:i:s',$log->DateTime);

                switch($log->Status) {
                    case 0 : case '0':
                            $cstate = 'C/In';
                        break;
                    
                    case 1 : case '1':
                            $cstate = 'C/Out';
                        break;
                    case 2 : case '2':
                            $cstate = 'OT/In';
                        break;
                    case 3 : case '3':
                            $cstate = 'OT/Out';
                        break;
                }

                DB::table('biometric_raw')
                        ->updateOrInsert(
                    [
                        'biometric_id' => $log->PIN,
                        'punch_date' => $datetime->format('Y-m-d'),
                        'punch_time' => $datetime->format('H:i'),
                    ],
                    [
                        'cstate' => $cstate,
                        'state' => $log->Status
                    ]
                );

            }

        }    
    }
}

/*
punch_date 
punch_time
biometric_id

  +"PIN": "824"
  +"DateTime": "2023-08-02 20:02:23"
  +"Verified": "1"
  +"Status": "3"
  +"WorkCode": "0"


        // array_push($att,[
                //     'biometric_id' => $log->PIN,
                //     'punch_date' => $datetime->format('Y-m-d'),
                //     'punch_time' => $datetime->format('H:i'),
                //     'cstate' => $cstate,
                //     'state' => $log->Status
                // ]);
*/