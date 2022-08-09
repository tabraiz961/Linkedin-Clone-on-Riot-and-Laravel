<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseRatings extends Model {
	protected $table = 'course_ratings';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;

}