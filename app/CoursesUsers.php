<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TempFiles;

class CoursesUsers extends Model {
	protected $table = 'courses_users';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;


	/**
	 * Get the user associated with the CoursesUsers
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function user()
	{
		return $this->hasOne(User::class, 'user_id', 'user_id');
	}

	public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}