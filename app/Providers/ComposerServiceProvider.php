<?php

namespace App\Providers;

use App\Http\Controllers\UserController;
use App\Http\Helpers\HelpersFunctions;
use View;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
   
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function($view){
            $data = [];
            
            if(Auth::check()){
                $latest_notifications = Auth::user()->notifications->take(5)->toArray();
                for ($i=0; $i < sizeof($latest_notifications); $i++) { 
                    // $filename = preg_split( "/\\\\/", $latest_notifications[$i]['type'] );
                    // $filename = strtolower( $filename[ count( $filename ) - 1 ] );
                    // echo('<pre>');print_r($filename);echo('</pre>');die('call');
                    $sender_id = [];
                    if(is_array($latest_notifications[$i]['data']['sender'])){
                        $sender_id = $latest_notifications[$i]['data']['sender']['user_id'];
                    } else {
                        $sender_id = $latest_notifications[$i]['data']['sender'];
                    }
                    if($sender_id){
                        $user = User::whereUserId($sender_id)->firstOrFail()->toArray();
                        $user = UserController::setCuratedFields([$user])[0];
                        $latest_notifications[$i]['data']['sender']  = $user;
                    }
                    $receiver_id = $latest_notifications[$i]['notifiable_id'];

                    $receiver = User::whereUserId($receiver_id)->firstOrFail()->toArray();
                    $receiver = UserController::setCuratedFields([$receiver])[0];
                    $latest_notifications[$i]['data']['receiver']  = $receiver;
                    switch ($latest_notifications[$i]['type']) {

                        case 'App\Notifications\GotFollowForInstitution':
                            
                            // Remake this in future  
                            // if(session('UserType') == HelpersFunctions::INSTITUTIONUSER){
                                $latest_notifications[$i]['data']['receiver']['institution'] = User::where('user_id', $receiver['user_id'])->first()->getInstitution->toArray();
                            // }
                            break;
                        
                        default:
                            break;
                    }

                }
                $data['latest_notifications'] = $latest_notifications;
                // echo('<pre>');print_r($data['latest_notifications']);echo('</pre>');die('call');
            }
            $view->with('data', $data);
        });
    }

    public function register()
    {
    }

}
