<?php 

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Http\Helpers\HelpersFunctions;

class UpdateUserMetaData  {


    public function __construct()
    {
    }

    
    public function handle(Login $event)
    {
        session(['UserType' => HelpersFunctions::LOGGEDUSER]);
        
    }

}