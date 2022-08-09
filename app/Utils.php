<?php

namespace App;

use App\Exceptions\QuotaExceededException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Rackspace\RackspaceAdapter;
use OpenCloud\ObjectStore\Constants\UrlType;
use Illuminate\Support\Facades\File;

class Utils {
	public static function getFileUrl($path) {
		//return Storage::disk('public')->get($path);
		return Storage::url($path);
	}

	public static function store( $user, $file, $path ) {
		if(isset($file)){
			$size = filesize( $file->getRealPath() ) / pow( 1024, 2 );
			$newquota = $size + $user->usagequota;
			if( $newquota <= User::quota_usage_max ) {
				$user->usagequota = $newquota;
				$user->save();
				$fileName   = time() .'_'. $file->getClientOriginalName();
	
				Storage::disk('public')->put($path . $fileName, File::get($file));
				return $path . $fileName;
			}
			throw new QuotaExceededException();
		}
		return '';
	}
	public static function handleEmpty($string)
	{
		return $string  == ''? 'ㅤ': $string;
	}
	public static function handleEmptyStringKeys($obj)
	{
		// $temp = array_keys($obj);
		foreach ($obj as $key => $value) {
			if(is_string($obj[$key]) && $obj[$key] == ''){
				$obj[$key] = 'ㅤ';
			}
		}
		return $obj;
	}
	public static function array_except($array, $keys) {
		$temp = [];
		foreach ($array as $key => $value) {
			$temp[] =  array_except($value, $keys);   
		}
		return $temp;
	}
	
	public static function GetWordFirstCharacters($string = '')
	{
		if($string==''){
			return '';
		}
		preg_match_all('/(?<=\b)[a-z]/i',$string,$matches);
		return strtoupper(implode('',$matches[0]));
	}
	
	public static function getFileInfo($fileMime)
	{
		$info ='';
		
		switch ($fileMime) {
			case 'video/mp4':
			case 'video/3gpp':
			case 'video/x-msvideo':
			case 'video/mpeg':
			case 'video/ogg':
			// case 'video/x-flv':
				$info = 'video';
				break;
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'CSV':
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			case 'application/vnd.ms-excel':
			case 'application/vnd.ms-powerpoint':
			case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			case 'text/plain':
				$info = 'document';
				break;
			case 'image/bmp':
			case 'image/gif':
			case 'image/jpeg':
			case 'image/png':
			case 'image/svg+xml':
			case 'image/tiff':
			case 'image/webp':
				$info = 'image';
				break;
			default:
				$info = 'other';
				break;
		}
		return $info;
	}

	public static function createSlug($str, $delimiter = '-'){

		$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
		return $slug;
	
	} 
}