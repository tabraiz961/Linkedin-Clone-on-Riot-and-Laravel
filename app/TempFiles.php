<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TempFiles extends Model {
	protected $table = 'temp_files';
	public $timestamps = false;
	protected $primaryKey = 'id';
	public $incrementing = true;


}