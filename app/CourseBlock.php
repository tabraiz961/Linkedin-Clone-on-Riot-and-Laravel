<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TempFiles;
use App\CourseFile;

class CourseBlock extends Model {
	protected $table = 'course_block';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;

	public function tempFiles() {
		return $this->hasMany( TempFiles::class, 'block_id', 'id' );
	}
	
	public function courseFiles() {
		return $this->hasMany( CourseFile::class, 'block_id', 'id' );
	}

	public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}