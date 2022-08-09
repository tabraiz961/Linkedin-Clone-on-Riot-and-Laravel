<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TempFiles;

class Course extends Model {
	protected $table = 'courses';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;

	public function getBlocks()
	{
		return $this->hasMany( CourseBlock::class, 'course_id', 'id' );
	}
	
	public function getOwner()
	{
		return $this->hasOne( CoursesUsers::class, 'course_id', 'id' );
	}
	
	public function ratings()
	{
		return $this->hasMany( CourseRatings::class, 'course_id', 'id' );
	}
	
}