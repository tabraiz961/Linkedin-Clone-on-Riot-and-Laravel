<?php

namespace App\Http\Controllers;

use App\Addresses;
use App\Utils;
use App\Controllers;
use App\Http\Controllers\InstitutionController as ControllersInstitutionController;
use App\Http\Helpers\HelpersFunctions;
use App\InstitutionFollows;
use App\Notifications\FriendRequestAccepted;
use App\User;
use App\Institutions;
use App\JobOffer;
use App\ProfileDetails;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class InstitutionController extends Controller
{
    const INSTITUTION_PROFILE_PATH = '/images/Institutions/profile_image/';
    const INSTITUTION_COVER_PATH = '/images/Institutions/cover_images/';
    public function get($name = '')
    {   if($name == ''){
            return Institutions::all()->toArray();
        }
        return Institutions::where('institution_name', 'like', '%'.$name.'%')->get()->toArray();
    }
    public function loadInstitution( Request $request, $slug)
    {
        $data['success'] = true;
        $data = [];
        $data['institution'] = self::getInstitutionSubDetails($slug);
        
        // echo('<pre>');print_r($data);echo('</pre>');die('call');
        $data['user'] = Auth::user() ? Auth::user()->toArray() : null;
        unset($data['user']['infos']);


        return view('app.institution.profile', ['data2' => $data]);
    }
    public function createStepOne(Request $request)
    {
        $response['success'] =  false;
        $response['data'] =  [];

        $response['message'] =  '';
        try {
            $uniName = $request->get('uni_name');
            if(isset($uniName) && (trim($uniName) !== '')){
                $institution = new Institutions;
                $institution->owner_id = Auth::id();
                $institution->institution_name = $uniName;
                $institution->slug = str_slug($uniName, '-');
                $institution->save();
                
                $response['success'] =  true;
                $response['data']['institution'] =  $institution;
                $response['data']['redirect_url'] =  route('institutions.create2', ['slug' => $institution->slug]);
                session(['UserType' => HelpersFunctions::INSTITUTIONUSER]);
            }

        } catch (\Throwable $th) {
            $response['message'] = $th->getMessage();
        }
        return response()->json($response);
    } 
    public function createStepTwo(Request $request)
    {   $institution = [];
        $data['StepTwo'] = false;
        if(session('UserType') != HelpersFunctions::INSTITUTIONUSER){
            return redirect('/');
        }
        $slug = $request->segment(2);
        $institution = self::getInstitutionSubDetails($slug);
        $data['StepTwo'] = true;
        if($institution != []){
            $data['institution'] = $institution->toArray();
        }
        $data['user'] = Auth::user() ? Auth::user()->toArray() : null;
        unset($data['user']['infos']);
        return view( 'app.institution.profile', ['data2' => $data]);
    }
    public static function getCuratedInstitution($_institution = null)
    {   
        $institution = [];
        // if(session('UserType') == HelpersFunctions::INSTITUTIONUSER){
        if($_institution == null){
            $institution = Institutions::where('owner_id', Auth::id())->first();  
        }else{
            $institution = $_institution;
        }
        if(!isset($institution['institution_cover'])){
            $institution['institution_cover'] = '/images/Institutions/cover_images/pen-uni-cover.jpg';
        }
        if(!isset($institution['institution_photo'])){
            $institution['institution_photo'] = '/images/Institutions/profile_image/default_profile.jfif';
        }
        if(!isset($institution['public_bio'])){
            $institution['public_bio'] = HelpersFunctions::BLANK_CHARACTER;
        }
        if(!isset($institution['long_overview_description'])){
            $institution['long_overview_description'] = HelpersFunctions::BLANK_CHARACTER_3L;
        }
        return $institution;
        // }
        return false;
    }
    public function getInstitutionSubDetails($slug)
    {
        $institution = Institutions::where('slug', $slug)->firstOrFail();  
        
        // questions causes javascript parse break
        $institution['jobs_placed'] = JobOffer::where('institution_id', $institution->institution_id )
            ->orderBy('created_at', 'desc')
            ->get();
            // ->toArray();
        foreach ($institution->jobs_placed as $key => $value) {
            $value->job_location_curated    = strtoupper($value->job_location);
            $value->job_type_curated        = strtoupper($value->job_type);
            $value->position_curated        = ucwords($value->position);
            $value->created_at_curated  = \Carbon\Carbon::parse($value->created_at)->diffForhumans();
        }
        $institution['institution_name_blockedName'] = Utils::GetWordFirstCharacters($institution['institution_name']);

        $institution['jobs_placed'] = Utils::array_except($institution['jobs_placed'], ['questions', 'description']);
        $institution = ControllersInstitutionController::getCuratedInstitution($institution);
        $institution['followers_count'] = $institution->followers->count(). ' Followers';
        
        $institutionFollows = new InstitutionFollows();
        
        $friendsWorkingHereCount = $institution->getMyFriendsWorkingInInstitution($institution);
        $institution['conn_working'] = $friendsWorkingHereCount ?  $friendsWorkingHereCount. ' connection work here': HelpersFunctions::BLANK_CHARACTER;
        
        $connectionsCount = $institutionFollows->getUsersFollowingInstitution($institution, array_column(Auth::user()->getFriends()->toArray(),'user_id'));
        $institution['conn_following'] = $connectionsCount ?  $connectionsCount. ' of your connections are following': HelpersFunctions::BLANK_CHARACTER;
        
        $institution['emp_count'] = $institution->getUsersWorkingInInstitution($institution). ' Employees';
        if(isset($institution['address_id'])){
            $temp = Addresses::where('id', $institution['address_id'])->first();
            $institution['address_details'] = $temp;
            $institution['address_details']['curated_address'] = $temp['state_name']. ', '. $temp['city'];
        }
        // $date = DateTime::createFromFormat("Y-m-d", $institution['founded_date']);
        $institution['founded_year'] = isset($institution['founded_date']) ? date('Y', strtotime($institution['founded_date'])): '2000';
        $institution['category'] = 'University';
        // $institution['is_owner'] = $institution['owner_id'] == Auth::id();
        return $institution;
    }
    public function update(Request $request)
    {
        // $founded_date =  date('Y-m-d H:i:s', time());
        $slug = $request->segment(2);
        $institution_photo_path = '';
        $institution_cover_path = '';
        
        if (Input::hasFile('institution_photo') )
        {
            $fileName = $slug.'_'.time().'.'.Input::file('institution_photo')->getClientOriginalExtension();
            Input::file('institution_photo')->move(public_path().ControllersInstitutionController::INSTITUTION_PROFILE_PATH, $fileName, 'public');
            $institution_photo_path = ControllersInstitutionController::INSTITUTION_PROFILE_PATH . $fileName;
        }
        if (Input::hasFile('institution_cover'))
        {
            $fileName = $slug.'_'.time().'.'.Input::file('institution_cover')->getClientOriginalExtension();
            Input::file('institution_cover')->move(public_path().ControllersInstitutionController::INSTITUTION_COVER_PATH, $fileName, 'public');
            $institution_cover_path = ControllersInstitutionController::INSTITUTION_COVER_PATH . $fileName;
        }
        $arr = $request->all();
        // $arr['institution_photo'] = 
        if($institution_photo_path!= ''){
            unset($arr['institution_photo']);
            $arr['institution_photo'] = $institution_photo_path;
        }
        
        if($institution_cover_path!= ''){
            unset($arr['institution_cover_path']);
            $arr['institution_cover'] = $institution_cover_path;
        }
        $arr['address_id'] = $arr['address_selected'];
        // $founded_date
        unset($arr['_token']);
        unset($arr['address_selected']);
        Institutions::where('slug', $slug)->update($arr);
        $data['success'] = true;
        $data['institution'] = Institutions::where('slug', $slug)->firstOrFail();
        return redirect()->route('institutions.load',['slug' => $slug]);
        // return response()->json($data);
        
        // return view('app.institution.profile', ['data2' => $data]);
    }
}