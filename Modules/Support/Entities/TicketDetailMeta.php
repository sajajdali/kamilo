<?php

namespace Modules\Support\Entities;

use Illuminate\Database\Eloquent\Model;

class TicketDetailMeta extends Model
{
    CONST META_KEY_FILE = 'FILE';

    public $timestamps = true;

    protected $table = 'ticket_detail_metas';

    protected $fillable = [
        'ticket_detail_id', 'meta_key', 'meta_value'
    ];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticketDetail()
    {
        return $this->belongsTo(TicketDetail::class);
    }
}
