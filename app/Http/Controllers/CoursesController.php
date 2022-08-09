<?php

namespace App\Http\Controllers;

use App\Course;
use App\CourseBlock;
use App\Entity;
use App\Utils;
use App\TempFiles;
use App\CourseFile;
use App\CourseRatings;
use App\CoursesUsers;
use App\EntityActions;
use App\Http\Helpers\HelpersFunctions;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use DB;

class CoursesController extends Controller {
	public function public(Request $request)
	{
	
		return view('app.courses.allList');
	}
	public function create( Request $request, $id = null ) {
		
		$course = Course::find($id);
		
		if ($request->isMethod('get')) {
			if(!$course){
				
				$courseId = Course::insertGetId( [] );
				CoursesUsers::insert(['course_id'=> $courseId, 'user_id' => Auth::user()->user_id]);
				$data['course_id'] = $courseId;
			}else{
				$data['course_id'] = $course->id;
			}
			$course->getOwner;
			if($course->getOwner->user_id != Auth::user()->user_id){
 				return;
			}
			return view( 'app.courses.create' ,['data2' => $data]);
		}
		// POST
		
		if($course->getOwner->user_id != Auth::user()->user_id){
			return;
		}

		$data['success'] = true;
		$data['data'] = [];
		$update_arr = [];
		if (!file_exists(storage_path( 'app/public' ).'/courses/'. $id.'/course-poster/')) {
			Storage::disk('public')->makeDirectory('/courses/'. $id.'/course-poster/');
		}

		$coursePoints = $request['course-points'];
		$courseRequirements = $request['course-requirements'];
		$description = $request['description_ck'];

		$status = $request->status_checked == 'true' ? 1 : 0; ; 
		$update_arr = [
			'status'	=> $status,
			'course_title' => $request->title,  
			'slug'	=>	Utils::createSlug($request->title),
			'course_brief'	=>	$request->course_brief,
			'course_points' => $coursePoints, 
			'course_requirements' => $courseRequirements, 
			'course_description' => $description 
		];
		$filename = $request->course_picture->getClientOriginalName();
		//not allow same file update 
		if($filename!='blob'){
			$request->course_picture->storeAs('courses/'. $id.'/course-poster/',	$filename, 'public');
			$update_arr['course_image'] = $filename; 
		}
		Course::where('id', '=',$id)  
		->update($update_arr);


		$blockInputs = json_decode($request['block_inputs']);
		foreach ($blockInputs as  $value) {
			foreach ($value as $key2 => $value2) {
				$keys = array_keys((array)$value2);
				$values = array_values((array)$value2);
				for ($i=0; $i < count($keys); $i++) { 
					CourseFile::where('id', '=', $keys[$i])->update(['defined_detail'=> $values[$i]]);
				}
			}
		}


		// Remove Blocks without files and block names
		
		
		return response()->json($data);
	}

	public function fileUpload(Request $request)
	{
		if ($request->hasFile('course-material')) {
			$path = Utils::store(Auth::user(), $request->file('course-material'),'TempFiles/');
			$id = DB::table('temp_files')->insertGetId(['file_name'=> $request->file('course-material')->getClientOriginalName(), 'temppath' => $path, 'user_id' => Auth::user()->user_id, 'block_id' => $request->get('id')]);
			return response($id);
		}
	}
	public function fileDelete(Request $request, $id)
	{	
		$record = DB::table('temp_files')->where(['user_id' => Auth::user()->user_id, 'id' => $id ])->first();
		if(isset($record)){
			Storage::disk('public')->delete($record->temppath);
			DB::table('temp_files')->where(['user_id' => Auth::user()->user_id, 'id' => $id])->delete();
			return '';
		}
	}
	
	public function blockDelete(Request $request, $blockId)
	{	
		$data['success'] = true;
		$data['data'] = [];
		$records = DB::table('course_files')->where(['user_id' => Auth::user()->user_id, 'block_id' => $blockId ])->get();
		$courseBlock = CourseBlock::where('id', '=', $blockId)->first();
		// Multiple event trigger issue at courses create modeal close
		if($courseBlock){
			$courseId = $courseBlock->course->id;
			// Delete FIles if any
			if(count($records)){
				Storage::disk('public')->deleteDirectory('/courses/'.$courseId.'/'.$blockId);
				// foreach ($records as $value) {
				// 	Storage::disk('public')->delete('/courses'.'/'.$courseId.'/'.$blockId.'/'.$value->file_name);
				// }
			}
			// Delete Records
			DB::table('course_files')
				->where(['user_id' => Auth::user()->user_id, 'block_id' => $blockId ])
				->delete();
	
			DB::table('course_block')
				->where(['id' => $blockId])
				->delete();
		}

		return $data;
	}
	public function removeFileFromBlock(Request $request)
	{
		$data['success'] = true;
		$data['data'] = [];
		$fileId = $request->get('fileId');

		$file =	CourseFile::where('id', '=', $fileId)->first();
		$file->block;
		Storage::disk('public')->delete('/courses'.'/'.$file->block->course_id.'/'.$file->block->id.'/'.$file->file_name);
		CourseFile::where('id', '=', $fileId)->delete();
		return $data;
	}
	public function getAutoGeneratedBlockId(Request $request)
	{
		$blockId = DB::table('course_block')->insertGetId(['block_name' => null, 'course_id' => $request->get('course_id')]);
		return response($blockId);
	}
	public function fileConfirm(Request $request)
	{
		$data['success'] = true;
		$data['data']['blocks'] = [];
		
		if($request->has('blockId')){
			CourseBlock::where('id', '=', $request->get('blockId'))->update(['block_name' => $request->get('blockName')]);	
		}
		$courseFiles = [];
		if($request->has('ids') && count($request->get('ids')) > 0 ){
			$block = CourseBlock::find($request->get('blockId'));
			$tempRecords = TempFiles::where('block_id', '=', $block->id)->where('user_id', '=', Auth::user()->user_id)->get();
			$course = $block->course;
			foreach ($tempRecords as $file) {
				if( in_array($file->id, $request->get('ids')) ) {
					if (!file_exists(storage_path( 'app/public' ).'/courses/'. $course->id.'/'.$block->id.'/')) {
						Storage::disk('public')->makeDirectory('/courses/'. $course->id.'/'.$block->id.'/');
					}
					Storage::disk('public')->move($file->temppath,'/courses/'. $course->id .'/'. $block->id.'/'.$file->file_name);
					
					$courseFiles[] = CourseFile::insertGetId(['file_name' => $file->file_name, 'user_id' => Auth::user()->user_id, 'block_id'=> $block->id]);
				}
			}
			TempFiles::where('block_id', '=', $block->id)->where('user_id', '=', Auth::user()->user_id)->delete();
			$data['data']['blocks'][] = [ 'block_id' => $block->id,  'courseIds' => $courseFiles]; 
		}

		// Delete empty Blocks that are autogenerated
		CourseBlock::where('course_id', '=', $course->id)
			->whereNull('block_name')
			->delete();
		return response()->json($data);		
	}

	public function getCoursesBlocks($courseId)
	{
		$course = Course::where('id', '=', $courseId)->first();
		$course->getBlocks;
		
		foreach ($course->getBlocks as $value) {
			$value->courseFiles;
			self::setCourseFilesIcons($value, $course);
		} 
		$data['success'] = true;
		$data['data'] = $course;
		return response()->json($data, 200);
	}
	public function setCourseFilesIcons(&$value, $course)
	{
		foreach ($value->courseFiles as $value2) {
			$fileMime = Storage::disk('public')->getMimeType('courses/'.$course->id.'/'.$value->id.'/'.$value2->file_name); 
			$type = \App\Utils::getFileInfo($fileMime);
			if($type == 'document'){
				$type = 'file';
			}
			if($type == 'other'){
				$type = 'question';
			}
			$value2->info = $type;
			$value2->mime = $fileMime;
		}
	}
	public function getCourseView($slug)
	{
		return view('app.courses.view',['slug' => $slug]);
	}
	public function getCourseBySlug(Request $request, $slug)
	{
		$data2['data']['course'] = Course::where('slug', '=', $slug)->where('status', '=', 1)->first();
		if(!$data2['data']['course']){ 
			abort(403, 'Unauthorized action.');
			return;
		}
		$ratings = CourseRatings::where('course_id', '=', $data2['data']['course']['id'])->get()->toArray();
		if(Auth::check()) {
			$is_viewed  = EntityActions::where('entity_name', '=', 'course')
			->where('entity_name_type', '=', 'view')
			->where('entity_main_id', '=', $data2['data']['course']['id'])
			
			->where('user_id', '=', Auth::user()->user_id)->get();
			if(!count($is_viewed)){
				EntityActions::insert([
					'user_id'=> Auth::user()->user_id, 
					'entity_name_type' =>  'view', 
					'entity_name' => 'course', 
					'entity_main_id' => $data2['data']['course']['id']
				]);
			}
		}
		$data2['data']['ratings'] = [];
		if(count($ratings)){
			$points = 'points';
			$arr = $ratings;
			$ratings_arr = array_map(function($item) use($points) {
				return $item[$points];
			}, $arr);
			
			// $viewed = array_filter($arr, function($item){return $item['is_viewed'] == 1;});
			// $interested = array_filter($arr, function($item){return $item['is_interested'] == 1;});
			$interested = EntityActions::where('entity_name', '=', 'course')
			->where('entity_name_type', '=', 'interested')
			->get();
			$ratings_arr = array_filter($ratings_arr);
			$data2['data']['ratings']['value'] = round(array_sum($ratings_arr) / count($ratings_arr), 1); 
			$data2['data']['ratings']['total_ratings'] = count($ratings_arr); 
			// $data2['data']['ratings']['views'] = count($viewed);
			$data2['data']['ratings']['interested'] = count($interested); 
			
		}
		$data2['data']['course']->getOwner;
		$data2['data']['course']->getBlocks;
		foreach ($data2['data']['course']->getBlocks as $value) {
			$value->courseFiles;
			self::setCourseFilesIcons($value, $data2['data']['course']);
		}
		// $data2['data']['course']->getBlocks;
		$data2['data']['course']->getOwner->user;
		$data2['data']['owner'] = UserController::setCuratedFields([$data2['data']['course']->getOwner->user]);
		$data2['data']['is_owner'] = false;
		if(Auth::check()){
			$data2['data']['is_owner'] = $data2['data']['course']->getOwner->user_id == Auth::user()->user_id;
		}
		$data2['success'] = true;
		return response()->json($data2, 200);
	}
	public function myCourses()
	{
		$coursesUsers = CoursesUsers::where('user_id', '=', Auth::user()->user_id)->get();
		
		foreach ($coursesUsers as $value) {
			$value->course;
			$value->course->ratings;
			$value['views'] = EntityActions::where('entity_name', '=', 'course')
			->where('entity_name_type', '=', 'view')
			->where('entity_main_id', '=', $value->course->id)
			->count();
		}

		return view('app.courses.mylist',['courses' => $coursesUsers->toArray()]);
	}

	public function getAllCourses() {
		$data['success'] = true;
		$data['data'] = [];
		$courses = Course::where('status', '=', 1)->get();
		foreach ($courses as $value) {
			$value->ratings;
			$value->getOwner;
			$value->interested = EntityActions::where('entity_name', '=', 'course')
			->where('entity_name_type', '=', 'interested')
			->where('entity_main_id', '=', $value->id)
			->get();

			$value->views = EntityActions::where('entity_name', '=', 'course')
			->where('entity_name_type', '=', 'view')
			->where('entity_main_id', '=', $value->id)
			->get();

		}
		$data['data'] = $courses->toArray();
		return response()->json($data);
	}

	public function interestedToggle($courseId = null)
	{
		$data['success'] = false;
		$data['data'] = [];
		if($courseId){
			try {
				$record = EntityActions::where('entity_name', '=', 'course')
					->where('entity_name_type', '=', 'interested')
					->where('entity_main_id', '=', $courseId)
					->where('user_id', '=', Auth::user()->user_id)
					->get();
				if(count($record) > 0) {
					$record = EntityActions::where('entity_name', '=', 'course')
						->where('entity_name_type', '=', 'interested')
						->where('entity_main_id', '=', $courseId)
						->where('user_id', '=', Auth::user()->user_id)
						->delete();
				}else{
					EntityActions::insert(
					[
					'entity_name' => 'course', 
					'entity_name_type' => 'interested', 
					'entity_main_id'=> $courseId, 
					'user_id' => Auth::user()->user_id
					]);
				}
				$data['success'] = true;
			} catch (\Throwable $th) {
				
			}
		}
		return $data;
	}

	public function addRatings(Request $request)
	{
		$data['success'] = false;
		$data['data'] = [];
		if(isset($request->data)){
			$rating = CourseRatings::where('course_id' , '=', $request->data['courseId'])
			->where('user_id', '=',Auth::user()->user_id)->get();
			if(count($rating)>0){
				// Update
				CourseRatings::where('course_id' , '=', $request->data['courseId'])
				->where('user_id', '=',Auth::user()->user_id)
				->update(['points'=> $request->data['value']]);
			}else{
				// Insert
				CourseRatings::insert([
					'course_id' => $request->data['courseId'], 
					'user_id' => Auth::user()->user_id, 
					'points'=> $request->data['value']
				]);
			}
			$data['success'] = true;
		}
		return response()->json($data);
	}
}

