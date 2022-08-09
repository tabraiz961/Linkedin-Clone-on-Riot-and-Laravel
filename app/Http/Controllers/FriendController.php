<?php

namespace App\Http\Controllers;

// use App\Notification as AppNotification;

use App\FriendRequests;
use App\Notification;
use App\Notifications\FriendRequestAccepted;
use App\Notifications\FriendRequestReceived;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;

class FriendController extends Controller
{
    public function get() {
        return response()->json( Auth::user()->getFriends() );
    }

    /**
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function add(Request $request, $username ) {
        
        $data['msg']     = '';
        $data['success'] = true;
        $data['error_msg'] = '';
        try {
            $user = User::whereUsername( $username )->firstOrFail();
            Auth::user()->addFriend( $user );
            $data['friend'] = Auth::user()->getFriends()->where('username', $username);
	        UserController::setCuratedFields($data['friend']);
            $data['msg']     = trans('lang.friend_is_connection');
            Notification::whereId($request['noti_id'])->delete();
        } catch (\Throwable $th) {
            $data['success'] = false;
            $data['friend'] = [];
            $data['error_msg'] = $th->getMessage();
        }
        return response()->json($data);
    }

    public function ask($username ) {
        $user = User::whereUsername( $username )->firstOrFail();
        // $user->notify( new FriendRequestReceived( Auth::user() ) );
        // return;
        $data = [
            'success'   => false,
            'data'      => []
        ];
        if(Auth::user()->sendFriendRequest( $user )){
            $data['success'] = true;
        }
        return response()->json($data);
    }

    public function removeRequest($username ) {
        $user = User::whereUsername( $username )->firstOrFail();

        Auth::user()->delSentFriendRequest( $user );

        return back();
    }

        public function refuse($username ) {
        $user = User::whereUsername( $username )->firstOrFail();

        Auth::user()->delReceivedFriendRequest( $user );

        return back();
    }

    /**
     * @param $username
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove( $username ) {
        $user = User::whereUsername( $username )->firstOrFail();

        Auth::user()->removeFriend( $user );

        return back();
    }

    public function toggleRequest($userId)
    {
        $data['success'] = false;
		$data['data'] = [];
		if($userId){
			try {
				$record = FriendRequests::where('invited_id', $userId)
                    ->where('requester_id', Auth::user()->user_id)
                    ->get();
				if(count($record) > 0) {
					FriendRequests::where('invited_id', $userId)
                        ->where('requester_id', Auth::user()->user_id)
						->delete();
				}else{
					FriendRequests::insert(
					[
					'invited_id' => $userId, 
					'requester_id' => Auth::user()->user_id
					]);
				}
				$data['success'] = true;
			} catch (\Throwable $th) {
				$data['success'] = false;
			}
		}
		return $data;
    }
}