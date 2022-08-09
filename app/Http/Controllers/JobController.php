<?php

namespace App\Http\Controllers;

use App\Http\Helpers\HelpersFunctions;
use App\Institutions;
use App\Job;
use App\JobApplied;
use App\JobApplyDetails;
use App\JobLanguagesRequired;
use App\JobOffer;
use App\JobSkills;
use App\Skills;
use App\Snowflake;
use App\User;
use App\Utils;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller {
	public function store( Request $request, $id = null) {
		
		$validator = Validator::make( $request->all(), JobOffer::validation_create );

		if( $validator->fails() ) {
			return redirect()->back()->withErrors( $validator )->withInput()->with('error', 'Check Inputs');
		}
		// try {
		//Insert Job Offers  
		$params = $request->only(['position', 'job_type', 'job_location', 'education_req', 'description', 'salary_type', 'fixed', 'min', 'max', 'gender_prefer', 'resume_required', 'job_start_date', 'questions']);
		$params['job_poster'] = Utils::store(Auth::user(), $request->job_poster_file, 'images/job_posters/' );
		$params[ 'institution_id' ] = Auth::user()->getInstitution->institution_id;
		$params['fixed'] = $params['salary_type'] == 'hour' ? 0:$params['fixed']; 
		$params['cv_required'] = $request->cv_required == 'yes' ? 1:0;

		if(isset($params['questions'])){
			// Remove Empty Values before submitting  
			$params['questions'] = json_encode(array_filter($params['questions']), true);
		}else{
			$params['questions'] = json_encode([]); 
		}
		$params['job_start_date'] = null;
		if($request->job_start_date){
			$time = strtotime($request->job_start_date);
			$params['job_start_date'] = date('Y-m-d', $time);
		}
		$msg =  'Job Successfully Created';
		if($id){
			 JobOffer::where('job_id', $id)
			->update($params);
			$job_id = $id;
			$msg = 'Job Successfully Updated';
		}else{
			// InsertJobOffer
			$job_id = JobOffer::insertGetId($params);
		}
		
		if($request->skills){
			// Save skills 
			$skills = [];
			JobSkills::where('job_id', $job_id)->delete();
			foreach ($request->skills as $key => $value) {
				$skills[] = ['job_id'=> $job_id, 'skill_id'=> $value];
			}
			JobSkills::insert($skills);
		}

		if($request->languages){
			// Save Languages
			$job_language_req = [];
			JobLanguagesRequired::where('job_id', $job_id)->delete();
			foreach ($request->languages as $key => $value) {
					$job_language_req[] = ['job_id'=> $job_id, 'language_id'=> $value];
			}
			JobLanguagesRequired::insert($job_language_req);
		}

		return redirect()->back()->with('success', $msg);
		// } catch (\Throwable $th) {
			// return redirect()->back()
			// ->with('error', $th->getMessage());
			// ->withInput()
		// }   
	}

	public function update( Request $request, $id) {
		$validator = Validator::make( $request->all(), JobOffer::validation_update );

		if( $validator->fails() ) {
			return redirect()->back()->withErrors( $validator )->withInput();
		}

		$job = JobOffer::findOrFail( $id );

		if( !Auth::user()->hasFullEditRight() && !$job->author->isSame( Auth::user() ) ) {
			return response( 'Unauthorized.', 401 );
		}

		if( $request->has( 'position' ) ) {
			$job->position = $request->get( 'position' );
		}
		if( $request->has( 'description' ) ) {
			$job->description = $request->get( 'description' );
		}

		$job->save();

		return redirect()->route( 'job.list' );
	}

	public function create( Request $request,$id = null) {

		$data['success'] 		= true;
		$data['jobs'] 			= [];
		// try{
			if($id!=null){
				$job = JobOffer::where('job_id', $id)->first();
				$data['edit_job_skills'] = [];
				$data['edit_job_languages'] = [];

				$job->skills;
				foreach ($job->skills as $key => $skill) {
					$skill->skillName;
					$data['edit_job_skills'][] = ['id'=> $skill->skillName->id, 'skill_name' => $skill->skillName->skill_name]; 
				}
				$job->languages;
				foreach ($job->languages as $key => $language) {
					$language->languagesName;
					$data['edit_job_languages'][] = ['id'=> $language->languagesName->id, 'Language' => $language->languagesName->Name]; 
				}
				$data['edit_job_data'] = $job;
				// echo('<pre>');print_r($data);echo('</pre>');die('call');
			}
		// }catch(Exception $e){
		// 	return redirect()->back()->with('error', 'Check your id');
		// }
		$data['jobs']['types'] 	= HelpersFunctions::JOB_TYPES;
		$data['jobs']['job_locations'] = HelpersFunctions::JOB_LOCATIONS;
		$data['education_req'] 	= HelpersFunctions::JOB_EDUCATIONS;
		$data['salary_type'] 	= HelpersFunctions::JOB_SALARIES;
		$data['gender_prefer'] 	= HelpersFunctions::JOB_GENDERS;
		return view( 'app.job.create', ['data2' => $data] );
	}

	public function edit( $id ) {
		$job = JobOffer::findOrFail( $id );

		if( !Auth::user()->hasFullEditRight() && !$job->author->isSame( Auth::user() ) ) {
			return response( 'Unauthorized.', 401 );
		}

		return view( 'app.job.edit', [ 'job' => $job ] );
	}

	public function list(Request $request) {
		$type = 'public';
		if( $request->segment(3) !== null && $request->segment(3) !== '' && Institutions::where('slug', '=', $request->segment(3))->count() ==1 )  {
			$type = 'specific';
			$data2['url_key'] = $request->segment(3);
		}
		$data2['type'] = $type;
		return view( 'app.job.list', ['data2' => $data2]);
	}

	public function jobs($institute = null){
		return response()->json(self::curatedJobs($institute));
	}

	public function curatedJobs($slug = null)
	{	
		$jobs = JobOffer::join('institution', 'job_offers.institution_id', '=', 'institution.institution_id');

		if($slug){
			$temp = Institutions::where('slug', '=', $slug);
			if($temp->count() == 1 && Auth::check() && Auth::user()->user_id == $temp->first()->owner_id){
				$jobs = $jobs->where('institution.slug', '=', $slug);
			}
		}
		$jobs = $jobs->orderBy('job_offers.created_at', 'desc')
			->paginate(4);
		foreach ($jobs as $key => $value) {
			$value->created_at_curated = \Carbon\Carbon::parse($value->created_at)->diffForhumans();
			$value->institution_name_curated = ucwords($value->institution_name);
			$value->position_curated = ucfirst($value->position);
			$value->job_location_curated = strtoupper($value->job_location);
			$value->job_type_curated = strtoupper($value->job_type);
			$value->gender_prefer_curated = strtoupper($value->gender_prefer);
			$value->is_owner_requested = Auth::check() ? $value->owner_id == Auth::user()->user_id : false;

			// Init Eloquents
			$value->skills;
			foreach ($value->skills as $key => $skill) {
				$skill->skillName;
			}

			$value->languages;
			foreach ($value->languages as $key => $skill) {
				$skill->languagesName;
			}
			$value->appliedUsers;
		}
		return $jobs;
	}
	public function show( $id ) {
		$job = JobOffer::findOrFail( $id );
		return view( 'app.job.show', [ 'job' => $job ] );
	}

	public function deleteAsk( $id ) {
		$job = JobOffer::findOrFail( $id );
		return view( 'app.job.delete', [ 'job' => $job ] );
	}

	public function delete( $id ) {
		$data['success'] 		= true;
		$data['data'] 			= [];
		try {
			JobOffer::findOrFail( $id )->delete();
		} catch (\Throwable $th) {
			$data['success'] 		= false;
		}
		return response()->json($data);  
	}

	public function apply( Request $request, $id ) {
		$validator = Validator::make( $request->all(), []);
		if( $validator->fails() ) {
			return redirect()->back()->withErrors( $validator )->withInput();
		}
		$data['success'] = true;
		$data['data'] = [];
		$data['msg'] = '';
		$insertGetId = Auth::user()->applyToJob($id);
		$rdata = $request->get('data');
		if(count($rdata['answers'])){
			JobApplyDetails::insert(['apply_id' => $insertGetId, 'answers' => json_encode($rdata['answers'])]);
		}
		// \Session::flash( 'success', 'Applied Successfully' );
		return response()->json($data);
	}

	public function userApplications(Request $request)
	{	
		if(!Auth::check()){
			return redirect()->back()->with('error', 'Please login before continuing ');
		}
		$institute = Auth::user()->getInstitution;
		
		if(!$institute){
			return redirect()->back()->with('error', 'Need to create an institution first!');
		}
		$job_offers = JobOffer::where('institution_id', '=', $institute->institution_id )
		->get();
		// Fetching relations
		foreach ($job_offers as $value) {
			foreach ($value->skills as $value2) {
				$value2->skillName;
			}
		}
		
		$temp = $job_offers->toArray();
		$data = [];
		foreach ($temp as $value) {
			$value['description'] = json_decode($value['description']);
			$value['questions'] = json_decode($value['questions']);
			$data[] = $value;
		}
		// echo('<pre>');print_r($data);echo('</pre>');die('call');
		// unset( $data[0]['description'] );
		// // unset( $data[0]['position'] );
		// unset( $data[0]['questions'] );
		// $data = 
		// echo('<pre>');print_r($job_offers->toArray() );echo('</pre>');die('call');
		return view( 'app.job.applications', ['data2' => $data] );
	}
	
	public function apiGetUserApplicationByJob(Request $request, $job_id)
	{
		$response['data'] = [];
		$response['success'] = true;
		
		$institute = Auth::user()->getInstitution;
		$usersApplied = JobOffer::join('job_applied', 'job_applied.job_id', '=', 'job_offers.job_id')
		->leftJoin('job_applied_details', 'job_applied.id', '=', 'job_applied_details.apply_id')
		->join('users', 'users.user_id', '=', 'job_applied.user_id')
		->where('institution_id', '=', $institute->institution_id )
		->where('job_offers.job_id', '=', $job_id )
		->select('users.*', 'job_applied_details.apply_id', 'job_applied_details.answers')
		->get();
		$users = [];
		// echo('<pre>');print_r($usersApplied->toArray());echo('</pre>');
		foreach ($usersApplied as $value) {
			$user = User::where('user_id', '=', $value->user_id)->first();
			$user->profile;
			$user->profile->profileExperiences;
			$user->profile->profileSkills;
			if(count($user->profile->profileSkills)){
				foreach ($user->profile->profileSkills as  $value2) {
					$value2->skillName;
					$value2->appreciators;
				}
			}
			$user['answers'] = json_decode($value->answers);
			$temp = new UserController();
			$user['job_experience'] = $temp->getExperienceProfileByType($user, 1);
			$users[] = $user;
		}
		UserController::setCuratedFields($users);
		$response['data']['users'] = $users;

		// echo('<pre>');print_r($usersecho('</pre>');die('call');
		return  response()->json($response);
		// echo('<pre>');print_r($data);echo('</pre>');die('call');
	}
}
