<?php

namespace App\Http\Controllers;


use App\Controllers;
use App\Notifications\FriendRequestAccepted;
use App\User;
use App\Institutions;
use App\ProfileExperience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileExperienceController extends Controller
{
    public function update($item)
    {
        $temp = array(
            'institution_id'    => $item['institution_id'],
            'experience_title'  => $item['experience_title'],
            'start'             => $item['start'],
            'end'               => $item['end'],
            'is_currently_engaged' => $item['is_currently_engaged'],
            'description'       => $item['description']
        );
        ProfileExperience::where('experience_id', $item['experience_id'])
        ->update($temp);
    }
}