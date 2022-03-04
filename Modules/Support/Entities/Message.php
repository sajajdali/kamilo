<?php

namespace Modules\Support\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Scalar\String_;

class Message extends Model
{
    public $timestamps = true;

    protected $table = 'messages';
    protected $fillable = [];

    CONST MASSAGE_STATUS_UNREAD = 0;
    CONST MASSAGE_STATUS_READ = 1;

    /**
     * Get user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get aget
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * create attribute for date
     *
     * @return string
     */
    public function getDateAttribute()
    {
        return jdate('Y-m-d H:i:s',$this->created_at->timestamp);
    }
}
