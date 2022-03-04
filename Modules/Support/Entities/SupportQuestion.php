<?php

namespace Modules\Support\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SupportQuestion extends Model
{
    protected $table = 'support_questions';
    
    public $timestamps = true;
    
    protected $fillable = [
        'user_id', 'title', 'question', 'answer'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

	/**
	 * Set mutator for created at
	 *
	 * @param $value
	 *
	 * @return mixed|string
	 */
    public function getCreatedAtAttribute($value){
    	return jdate("Y/m/d",strtotime($value));
    }
}
