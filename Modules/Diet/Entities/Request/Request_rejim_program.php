<?php

namespace Modules\Diet\Entities\Request;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Diet\Entities\Meal\Meal_category;
use Modules\Finance\Entities\Transaction;

class Request_rejim_program extends Model
{
    protected $table = 'request_rejim_program';
    protected $guarded = ['id'];    /**
     * active scope
     *
     * @param $q
     * @return mixed
     */
    public function scopeActive($q)
    {
        return $q->whereActive(true);
    }

    public function scopeOffline($q)
    {
        return $q->whereOffline(true);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dietType()
    {
        return $this->belongsTo(Meal_category::class, 'rejim_type');
    }

    public function dietDetail()
    {
        return $this->hasMany(Request_rejim_filed::class, 'request_rejim_id');
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class,"tid");
    }
}
