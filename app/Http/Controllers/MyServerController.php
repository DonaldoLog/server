<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\MySSOServer;
use Illuminate\Support\Facades\Auth;


class MyServerController extends Controller
{
    public function index(MySSOServer $ssoServer){
        // dd($ssoServer);
         // $_REQUEST['command']='login-user';
        $command = isset($_REQUEST['command']) ? $_REQUEST['command'] : null;
          // $command='attach';
          // dd( $ssoServer->$command(),'23123213');
         // dd($request

        if (!$command || !method_exists($ssoServer, $command)) {
            header("HTTP/1.1 404 Not Found");
            header('Content-type: application/json; charset=UTF-8');

            echo json_encode(['error' => 'Unknown command']);
            exit();
        }
          $user = $ssoServer->$command();
          // dd($user);
          // $user =['id'=>1,'email'=>'uipj@fiscaliaveracruz@gob.mx'];
         // dd($user);
        if($user)
            return response()->json($user);
    }
}
