<?php

namespace App;
use Jasny\SSO\Server;
use Jasny\ValidationResult;
use Illuminate\Support\Facades\Auth;
use DB;


class MySSOServer extends Server
{
    /**
     * Registered brokers
     * @var array
     */
    private static $brokers = [
        'Uipj' => ['secret'=>'8iwzik1bwd'],
        'Vrr' => ['secret'=>'7pypoox2pc'],
        'Desaparecidos' => ['secret'=>'ceda63kmhp']
    ];
    /**
     * Get the API secret of a broker and other info
     *
     * @param string $brokerId
     * @return array
     */
    protected function getBrokerInfo($brokerId)
    {
        return isset(self::$brokers[$brokerId]) ? self::$brokers[$brokerId] : null;
    }

    public function login()
    {
        $this->startBrokerSession();
        if (empty($_GET['username'])) $this->fail("No username specified", 400);
        if (empty($_GET['password'])) $this->fail("No password specified", 400);


        $validation = $this->authenticate($_GET['username'], $_GET['password']);

        if ($validation->failed()) {
            return $this->fail($validation->getError(), 400);
        }

        $this->setSessionData('sso_user', $_GET['username']);
        $this->userInfo();
    }
    /**
     * Authenticate using user credentials
     *
     * @param string $username
     * @param string $password
     * @return ValidationResult
     */
    protected function authenticate($username, $password)
    {
        if (!isset($username)) {
            return ValidationResult::error("username isn't set");
        }
        if (!isset($password)) {
            return ValidationResult::error("password isn't set");
        }

         // dd(Auth::attempt(['email' => 'fgeneral2@fiscaliaveracruz.gob.mx', 'password' => 'pruebauipj']));

        // $this->guard()->attempt(
        //     $this->credentials($request), $request->filled('remember')
        // );
        // dd(Auth::attempt(['email' => $username, 'password' => $password]));


        if(Auth::attempt(['email' => $username, 'password' => $password])){
            return ValidationResult::success();
        }
        return ValidationResult::error("can't find user");
    }
    /**
     * Get the user information
     *
     * @return array
     */
    protected function getUserInfo($username)
    {
        $user = User::where('email',$username)->first();
        return $user ? $user : null;
    }
    public function getUserById($id){
        return User::findOrFail($id);
    }
}
