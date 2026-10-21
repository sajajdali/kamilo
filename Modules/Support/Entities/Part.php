<?php

namespace Modules\Support\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    public $timestamps  = false;
    
    protected $table    = 'parts';
    
    protected $fillable = [
        'title', 'parent_id', 'setting', 'status'
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class,'part_user','part_id','user_id');
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function children()
    {
        return $this->hasMany($this,'parent_id');
    }
    
    public function getLink()
    {
        if($this != null) {
            $title = urlencode(str_replace(' ','-',$this->title));
            $id    = $this->id;
            return ('part/'.$id.'/'.$title.'.html');
        }else{
            return '';
        }
    }
}
