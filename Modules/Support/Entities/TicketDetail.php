<?php

namespace Modules\Support\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Support\Events\AddNewTicketAnswer;

class TicketDetail extends Model
{
    CONST TYPE_QUESTION = 0;
    CONST TYPE_ANSWER = 1;

    public $timestamps = true;

    protected $table = 'ticket_details';
    
    protected $casts = [
        'type' => 'boolean',
    ];

    protected $fillable = [
        'ticket_id', 'user_id', 'parent_id', 'type', 'message'
    ];
    
    public static function boot()
    {
        parent::boot();
        
        self::saved(function($model){
            event(new AddNewTicketAnswer($model));
        });
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo($this, 'parent_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany($this, 'parent_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metas()
    {
        return $this->hasMany(TicketDetailMeta::class);
    }
    
    /**
     * Create attrubute for file
     *
     * @return mixed
     */
    public function getFileAttribute() {
        return optional($this->metas()->whereMetaKey(TicketDetailMeta::META_KEY_FILE)->first())->meta_value;
    }

    public static function filterByUser($userId)
    {
        return self::where('user_id', $userId);
    }
}
