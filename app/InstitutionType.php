<?php

namespace App;

use App\Institutions as AppInstitutions;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\FriendRequestReceived;
use App\Notifications\FriendRequestAccepted;
use Laravel\Scout\Searchable;

class InstitutionType extends Authenticatable {
	use Notifiable;

	protected $table = 'institution_type_desc';
	public $timestamps = false;
}