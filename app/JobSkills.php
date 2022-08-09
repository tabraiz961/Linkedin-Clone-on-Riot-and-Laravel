<?php

namespace App;

use App\Notifications\JobApplication;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\JobOffer
 *
 * @property int $job_id
 * @property int $author_id
 * @property int $entity_id
 * @property string $position
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereAuthorId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereDescription( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereEntityId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereJobId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer wherePosition( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\JobOffer whereUpdatedAt( $value )
 * @mixin \Eloquent
 */
class JobSkills extends Model {
	//use Searchable;
	protected $table = 'job_skills';
	public $timestamps = false; 
	protected $primaryKey = 'id';
	
	public function skillName() {
		return $this->hasOne(Skills::class, 'id', 'skill_id');
	}
	
}