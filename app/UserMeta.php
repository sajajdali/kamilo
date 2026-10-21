<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserMeta
 *
 * @property int $id
 * @property int $user_id
 * @property string $meta_key
 * @property string $meta_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereMetaKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereMetaValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereUserId($value)
 * @mixin \Eloquent
 */
class UserMeta extends Model {
	protected $table = 'user_metas';
	protected $guarded = ['id'];
    public $timestamps = true;
	CONST FIRST_NAME = "FIRST_NAME";
	CONST LAST_NAME = "LAST_NAME";
	CONST BIRTHDAY_MONTH = "BIRTHDAY_MONTH";
    CONST NATIONAL_CODE = "NATIONAL_CODE";
    CONST MEDICAL_RECORD_NUMBER = "MEDICAL_RECORD_NUMBER";

    CONST BIRTHDAY_DAY = "BIRTHDAY_DAY";
	CONST BIRTHDAY_YEAR = "BIRTHDAY_YEAR";
	CONST AGENT = "AGENT";
	CONST PROFILE_AVATAR = "AVATAR";
    CONST WEIGHT = "WEIGHT";
    CONST HEIGHT = "HEIGHT";
    CONST ACTIVITY_RATE = "ACTIVITY_RATE";
    CONST TARGET_WEIGHT = "TARGET_WEIGHT";
    CONST GENDER = "GENDER";
    CONST WRIST = "WRIST";
    CONST DISEASE = "DISEASE";
    CONST RECOVERY_CODE = "RECOVERY_PASSWORD_CODE";
    CONST USER_API_ID = "USER_API_ID";
    CONST AGE = "AGE";
    CONST ACQUAINTED = "ACQUAINTED";
    CONST APPOINTMENT_FIRST_TYPE = "APPOINTMENT_FIRST_TYPE";
    CONST SERVICE = "SERVICE";
    CONST SERVICE_DETAIL = "SERVICE_DETAIL";
    CONST ADDRESS = "ADDRESS";
    CONST SECOND_MOBILE = "SECOND_MOBILE";

	public function user() {
		return $this->belongsTo(User::class);
	}

}
