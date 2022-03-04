<?php

namespace Modules\Finance\Entities;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\Advise\Entities\AdvisePackage;
use Modules\Exam\Entities\ExamCollection;
use Modules\Exam\Entities\ExamPackage;
use Modules\FileManager\Entities\EducationalPackage;
use phpDocumentor\Reflection\Types\String_;

class Transaction extends Model
{
    CONST TYPE_PAY_ONLINE = 1;
    CONST TYPE_PAY_OFFLINE_CART = 2;
    CONST TYPE_PAY_OFFLINE_BANK_INVOICE = 3;
    CONST TYPE_REFUND = 4;
    CONST TYPE_CHARGE_BY_ADMIN = 5;
    CONST TYPE_BUY = 6;
    CONST TYPE_GIFT = 7;
    public static $TYPE = [
        self::TYPE_PAY_ONLINE => 'پرداخت آنلاین',
        self::TYPE_PAY_OFFLINE_CART => 'پرداخت آفلاین با کارت',
        self::TYPE_PAY_OFFLINE_BANK_INVOICE => 'پرداخت آفلاین با فیش بانکی',
        self::TYPE_REFUND => 'بازپرداخت',
        self::TYPE_CHARGE_BY_ADMIN => 'شارژ توسط مدیر',
        self::TYPE_BUY => 'خرید خدمات',
        self::TYPE_GIFT => 'اعتبار هدیه',
    ];

    CONST STATUS_PENDING = 0;
    CONST STATUS_SUCCESS = 1;
    CONST STATUS_REJECTED = 2;
    CONST STATUS_CANCELED = 3;
    CONST STATUS_UNSUCCESSFULLY = 4;

    public static $STATUS = [
        self::STATUS_PENDING => [
            'title' => 'در انتظار',
            'class' => 'warning',
            'color' => '#f8c969'
        ],
        self::STATUS_SUCCESS => [
            'title' => 'موفق',
            'class' => 'success',
            'color' => '#8cd481'
        ],
        self::STATUS_REJECTED => [
            'title' => 'رد شده',
            'class' => 'danger',
            'color' => '#ed7764'
        ],
        self::STATUS_CANCELED => [
            'title' => 'کنسل شده',
            'class' => 'danger',
            'color' => '#ed7764'
        ],
        self::STATUS_UNSUCCESSFULLY => [
            'title' => 'ناموفق',
            'class' => 'danger',
            'color' => '#ed7764'
        ]
    ];


    protected $table = 'transactions';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionMetas()
    {
        return $this->hasMany(TransactionMeta::class);
    }

    /**
     * Code generator
     *
     * @param int $id
     * @param int $length
     * @param int $start
     * @param int $end
     * @return String
     */
    public static function codeGenerator(int $id,int $length = 5,int $start=1,int $end=9) : String
    {
        $code = $id;
        for($i=1;$i<=$length;$i++)
            $code .= rand($start,$end);
        return $code;
    }
}
