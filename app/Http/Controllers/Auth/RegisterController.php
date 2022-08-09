<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\ProfileDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Redirects user to front page when requesting the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return redirect()->route( 'index' );
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {  
        //  echo('<pre>');print_r(json_decode(json_encode(Validator::make($data, User::validation)), true));echo('</pre>');die('call');
        return Validator::make($data, User::validation);
    }

    /**
    * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     * @throws \Exception
     */
    protected function create(array $data)
    {
        // echo('<pre>');print_r($data);echo('</pre>');die('call');
        // $data = [];
        // if (env('REGISTRATION_ALLOWED'))
        // {
            // echo('<pre>');print_r($data);echo('</pre>');die('call');
            $params = $data;
            $params[ 'password' ] = Hash::make( $params[ 'password' ] );
            $params[ 'role' ] = 'AUTHOR';
            $user =  User::create( $params );
            ProfileDetails::insert(
                ['user_id' => $user->user_id, 'short_bio' => '', 'long_description' => '']
            );
            return $user;
        // }
        throw new \Exception( 'Registration not allowed.' );
    } 
}
