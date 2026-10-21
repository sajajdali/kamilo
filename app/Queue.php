<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Queue
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $type نوع صف که می‌تواند ایمیل یا پیامک یا نوتیفیکشن باشد
 * @property string $to
 * @property string $content
 * @property string|null $result نتیجه ارسال
 * @property string|null $model نام مدل
 * @property \Illuminate\Support\Carbon $send_at
 * @property int $priority
 * @property int $status وضعیت ارسال شده یا ارسال نشده
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue execute()
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Queue onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Queue whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Queue withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Queue withoutTrashed()
 * @mixin \Eloquent
 */
class Queue extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'integer',
        'count' => 'integer',
        'priority' => 'integer',
    ];
    protected $dates = ['send_at'];

    CONST TYPE_SMS = 0;
    CONST TYPE_EMAIL = 1;
    CONST TYPE_NOTIFICATION = 2;

    CONST STATUS_SEND_CANCELED = 2;
    CONST STATUS_SEND_COMPLETE = 1;
    CONST STATUS_SEND_PENDING = 0;

    CONST PRIORITY_LOW = 0;
    CONST PRIORITY_MEDIUM = 1;
    CONST PRIORITY_HIGH = 2;
    CONST PRIORITY_URGENT = 3;


    protected static function boot()
    {
        parent::boot();
        //add global scope for orderBy priority
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('priority', 'desc');
        });
    }

    public function scopeActive($query)
    {
        return $query->whereStatus(self::STATUS_SEND_PENDING);
    }

    public function scopeExecute($query)
    {
        return $query->active()->where('send_at',"<=",Carbon::now()->toDateTimeString());
    }
    public static function addQueue(string $to, string $content,string $model, int $type, Carbon $send_at, int $priority = Queue::PRIORITY_MEDIUM, bool $sendImmediately = false)
    {

        $queue = Queue::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'to' => $to,
            'content' => $content,
            'result' => null,
            'model' => $model,
            'send_at' => $send_at,
            'priority' => $priority,
            'status' => Queue::STATUS_SEND_PENDING
        ]);
        if($sendImmediately){
            //send sms immediately
            if ($queue->type == Queue::TYPE_SMS) {
                $to = [];
                if($queue->model == null){
                    $to = Arr::wrap($queue->to);
                }else{
                    $model = $queue->model::find($queue->to);
                    if($model != null){
                        if($model->user != null){
                            $to = Arr::wrap($model->user->mobile);
                        }else{
                            $to = Arr::wrap($model->mobile);
                        }
                    }
                }

                $content = Arr::wrap($queue->content);
                $resultSms = sendAfeSMS($to, $content)[0]['result'];
                $queue->result = $resultSms;
                $queue->status = Queue::STATUS_SEND_COMPLETE;
                $queue->save();
                
                return $resultSms;
            }
        }
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function scopeCronExecute($query)
    {
        return $query->active()
            ->where([
                ['send_at', '>=', Carbon::today()],
                ['send_at', '<', Carbon::tomorrow()],
            ]);
    }
}
