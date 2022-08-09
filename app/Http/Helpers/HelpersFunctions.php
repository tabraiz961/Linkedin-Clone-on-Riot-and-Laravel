<?php

namespace App\Http\Helpers;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\HelpersFunctions as HelpersFunctions2;
class HelpersFunctions { 
	
	// UserTypes
	const LOGGEDUSER 		= 'LoggedUser';
	const ANONYMOUS 		= 'Anonymous';
	const INSTITUTIONUSER 	= 'InstitutionUser';
	
	// Blank Character
	const BLANK_CHARACTER 		= 'ㅤ';
	const BLANK_CHARACTER_2L 	= 'ㅤ\nㅤ\n';
	const BLANK_CHARACTER_3L 	= 'ㅤ\nㅤ\nㅤ\n';


	// JOB TYPE 
	const JOB_TYPE_FULLTIME 	= "fulltime";
	const JOB_TYPE_PARTTIME 	= "parttime";
	const JOB_TYPE_INTERNSHIP 	= "internship";
	const JOB_TYPES				= [HelpersFunctions2::JOB_TYPE_FULLTIME,HelpersFunctions2::JOB_TYPE_PARTTIME,HelpersFunctions2::JOB_TYPE_INTERNSHIP ];
	
	
	// JOB LOCATION 
	const JOB_LOCATION_ONSITE 	= "onsite";
	const JOB_LOCATION_REMOTE 	= "remote";
	const JOB_LOCATIONS				= [HelpersFunctions2::JOB_LOCATION_ONSITE,HelpersFunctions2::JOB_LOCATION_REMOTE];

	// JOB EDUCATION 
	const JOB_EDUCATION_NONE 	= "none";
	const JOB_EDUCATION_ENTRY 	= "entry";
	const JOB_EDUCATION_EXPERIENCED 	= "experienced";
	const JOB_EDUCATION_PROFESSIONAL 	= "professional";
	const JOB_EDUCATIONS			= [HelpersFunctions2::JOB_EDUCATION_NONE,HelpersFunctions2::JOB_EDUCATION_ENTRY,HelpersFunctions2::JOB_EDUCATION_EXPERIENCED,HelpersFunctions2::JOB_EDUCATION_PROFESSIONAL];
	
	// SALARY 
	const JOB_SALARY_HOUR 		= "hour";
	// const JOB_SALARY_DAY 		= "day";
	const JOB_SALARY_MONTH 		= "month";
	const JOB_SALARIES			= [HelpersFunctions2::JOB_SALARY_HOUR, HelpersFunctions2::JOB_SALARY_MONTH];

	// GENDERS 
	const JOB_GENDER_MALE 	= "male";
	const JOB_GENDER_FEMALE 	= "female";
	const JOB_GENDER_ANY 	= "any";
	const JOB_GENDERS			= [HelpersFunctions2::JOB_GENDER_MALE,HelpersFunctions2::JOB_GENDER_FEMALE,HelpersFunctions2::JOB_GENDER_ANY];
	
	public static function _group_by($array, $key) {
		$return = array();
		foreach($array as $val) {
			$return[$val[$key]][] = $val;
		}
		return $return;
	}
	
	public static function  group_by($key, $data) {
		$result = array();
		foreach($data as $val) {
			if(array_key_exists($key, $val)){
				$result[$val[$key]][] = $val;
			}else{
				$result[""][] = $val;
			}
		}
		return $result;
	}
	public static function _group_by_and_getsingle_item($array, $key) {
		$return = array();
		foreach($array as $val) {
			if(!isset($return[$val[$key]])){
				$return[$val[$key]] = $val;
			}
		}
		return $return;
	}
	public static function getUserType()
	{
		if(Auth::user()){
			return HelpersFunctions::LOGGEDUSER;
		}
		return HelpersFunctions::ANONYMOUS;
	}
	public static function getTagsArrayFromString($input = '')
	{  	$allMatches = null;
		if($input == '') {
			return [];
		}
		preg_match_all('/(?<!\w)#\w+/', $input, $allMatches); 
		for ($i=0; $i < count($allMatches[0]); $i++) { 
			$allMatches[0][$i] = strtolower($allMatches[0][$i]);
		}
		return array_unique($allMatches[0]);
	}
}