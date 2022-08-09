<?php

namespace App;

use App\CourseBlock;
use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model {
	protected $table = 'course_files';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;

	
	public function block() {
		return $this->belongsTo( CourseBlock::class, 'block_id', 'id' );
	}
}