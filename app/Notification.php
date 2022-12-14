<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property int $read
 * @property int|null $reaction_id
 * @property int|null $comment_id
 * @property int|null $friend_request_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereFriendRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereNotificationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereReactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereUserId($value)
 * @mixin \Eloquent
 */
class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;

    protected $primaryKey = 'id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'type',
        'read',
        'reaction_id',
        'comment_id',
        'friend_request_id',
    ];


    public static function getUserNotifications( ) {
        $userid= Auth::user()->user_id;

        $status = Notification::where( 'notifiable_id', '=', $userid )
        ->orderBy( 'updated_at', 'DESC' );

        return $status;
    }

    public function getUser() {
        return $this->data.sender;
    }

    public function getNameSender() {
    return $this->getUser()->getName();
    }


    public function getTextNotification( ) {
    $text="pouet";
    return $text;
    }

    public function getNotification($id){
        $notification = Notification::where( 'id', '=', $id );
        return $notification;
    }

    
	/**
	 * @param Notification $notification
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function delNotification($notification_id ) {
        Notification::where( 'notifiable_id', '=', $notification_id )
        ->delete();
		return $status;
	}




}