<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentPart;
use Modules\Diet\Entities\Disease\Disease;
use Modules\Diet\Entities\Request\Request_rejim_program;
use Modules\Finance\Entities\Transaction;
use Modules\Page\Entities\Page;
use Modules\Support\Entities\Message;
use Modules\Support\Entities\Ticket;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * App\User
 *
 * @property int $id
 * @property string $mobile
 * @property string $email
 * @property int $charge
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Diet\Entities\Request\Request_rejim_program[] $dietRequests
 * @property-read mixed $activity_rate
 * @property-read mixed $avatar
 * @property-read mixed $birthday_day
 * @property-read mixed $birthday_month
 * @property-read mixed $birthday_year
 * @property-read mixed $disease
 * @property-read mixed $first_name
 * @property-read mixed $full_name
 * @property-read mixed $gender
 * @property-read mixed $height
 * @property-read mixed $last_name
 * @property-read mixed age
 * @property-read mixed $target_weight
 * @property-read mixed $weight
 * @property-read mixed $wrist
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Support\Entities\Message[] $messages
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Page\Entities\Page[] $pages
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Support\Entities\Ticket[] $tickets
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Finance\Entities\Transaction[] $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $userMetas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\Appointment[] $appointment
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\Appointment\Entities\AppointmentPart[] $appointmentParts
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles,HasApiTokens;
    protected $dates = ['last_login'];//Carbonized item
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email',
        'mobile',
        'charge',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    /**
     * Get all meta of user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userMetas()
    {
        return $this->hasMany(UserMeta::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Create attrubute for firstname
     *
     * @return mixed
     */
    public function getFirstNameAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::FIRST_NAME)->first())->meta_value;
    }

    public function viewTrasheed()
    {
        return $this->can('view_deleted_appointment');
    }

    /**
     * Create attribute for lastname
     *
     * @return mixed
     */
    public function getLastNameAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::LAST_NAME)->first())->meta_value;
    }

    /**
     * Create attribute for birthday month
     *
     * @return mixed
     */
    public function getBirthdayMonthAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::BIRTHDAY_MONTH)->first())->meta_value;
    }

    /**
     * Create attribute for birthday year
     *
     * @return mixed
     */
    public function getBirthdayYearAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::BIRTHDAY_YEAR)->first())->meta_value;
    }

    /**
     * Create attribute for birtday day
     *
     * @return mixed
     */
    public function getBirthdayDayAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::BIRTHDAY_DAY)->first())->meta_value;
    }

    /**
     * Gender
     *
     * @return mixed
     */
    public function getGenderAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::GENDER)->first())->meta_value;
    }

    public function getAgeAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::AGE)->first())->meta_value;
    }
    public function getAcquaintedAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::ACQUAINTED)->first())->meta_value;
    }

    public function getAddressAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::ADDRESS)->first())->meta_value;
    }


    /**
     * Get user wrist
     *
     * @return mixed
     */
    public function getWristAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::WRIST)->first())->meta_value;
    }

    /**
     * Get user disease
     */
    public function getDiseaseAttribute()
    {
        $ret = array();
        foreach ($this->userMetas()->whereMetaKey(UserMeta::DISEASE)->get() as $item){
            if(Disease::whereId($item->meta_value)->exists()){
                $ret[] = $item->meta_value;
            }
        }
        return $ret;
    }
    /**
     * Get user weight
     *
     * @return mixed
     */
    public function getWeightAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::WEIGHT)->first())->meta_value;
    }

    /**
     * Get user height
     *
     * @return mixed
     */
    public function getHeightAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::HEIGHT)->first())->meta_value;
    }

    public function getActivityRateAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::ACTIVITY_RATE)->first())->meta_value;
    }

    public function getTargetWeightAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::TARGET_WEIGHT)->first())->meta_value;
    }

    /**
     * Get avatar image
     *
     * @return mixed
     */
    public function getAvatarAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::PROFILE_AVATAR)->first())->meta_value == null ? url('uploads/avatar/no_avatar.png') : url('uploads/avatar/' . optional($this->userMetas()->whereMetaKey(UserMeta::PROFILE_AVATAR)->first())->meta_value);
    }

    public function getUserApiIdAttribute()
    {
        return optional($this->userMetas()->whereMetaKey(UserMeta::USER_API_ID)->first())->meta_value;
    }


    /**
     * Update many meta for user
     *
     * @param array $metaKey
     * @param array $metaValue
     * @throws \Throwable
     */
    public function updateOrCreateMeta(array $metaKey, array $metaValue){
        throw_if(sizeof($metaKey) != sizeof($metaValue),new InvalidParameterException("Parameter size not match"));
        for ($i=0;$i<sizeof($metaKey);$i++){
            UserMeta::updateOrCreate(
                ['user_id'=>$this->id,'meta_key'=>$metaKey[$i]],
                ['meta_value'=>$metaValue[$i]]
            );
        }
    }

    /**
     * User diet list
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dietRequests(){
        return $this->hasMany(Request_rejim_program::class);
    }

    /**
     * transaction list
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Ticket list
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get user message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class,'user_id');
    }


    public function appointmentParts()
    {
        return $this->belongsToMany(AppointmentPart::class);
    }

    public function appointment()
    {
        return $this->hasMany(Appointment::class);
    }
}
