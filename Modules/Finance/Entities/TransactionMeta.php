<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;

class TransactionMeta extends Model
{
    protected $table = "transaction_metas";
    protected $fillable = ['transaction_id','meta_key','meta_value'];
    public $timestamps = false;

    CONST META_AU_NUMBER = "AU_NUMBER";
    CONST META_ONLINE_TRANSACTION_NUMBER = "ONLINE_TRANSACTION_NUMBER";
    CONST META_BANK_CODE = "BANK_CODE";
    CONST META_DESCRIPTION = "DESCRIPTION";
    CONST META_CART_NAME = "CART_NAME";
    CONST META_CART_NUMBER = "CART_NUMBER";
    CONST META_CART_PAY_DATE = "CART_PAY_DATE";
    CONST META_INVOICE_NUMBER = "INVOICE_NUMBER";
    CONST META_INVOICE_NAME = "INVOICE_NAME";
    CONST META_INVOICE_PAY_DATE = "INVOICE_PAY_DATE";
    CONST META_AGENT_ID = "AGENT_ID";
    CONST META_AGENT_NAME = "AGENT_NAME";
    CONST META_AGENT_IP = "AGENT_IP";
    CONST META_AGENT_CHANGER_ID = "AGENT_CHANGER_ID";
    CONST META_AGENT_CHANGER_NAME = "AGENT_CHANGER_NAME";
    CONST META_AGENT_CHANGER_IP = "AGENT_CHANGER_IP";
    CONST META_DIET_PACKAGE_ID = "DIET_PACKAGE_ID";
    CONST META_DIET_ID = "DIET_ID";
    CONST META_DIET_NAME = "DIET_NAME";

    public static $metas = [
        self::META_AU_NUMBER => "شماره AU",
        self::META_ONLINE_TRANSACTION_NUMBER => "شماره تراکنش",
        self::META_BANK_CODE => "کد پیگیری بانک",
        self::META_DESCRIPTION => "توضیحات تراکنش",
        self::META_CART_NAME => "نام و نام خانوادگی صاحب کارت",
        self::META_CART_NUMBER => "۴ رقم آخر شماره کارت",
        self::META_CART_PAY_DATE => "تاریخ کارت به کارت",
        self::META_INVOICE_NUMBER => "شماره فیش",
        self::META_INVOICE_NAME => "نام و نام خادنوادگی پرداخت کننده فیش",
        self::META_INVOICE_PAY_DATE => "تاریخ پرداخت فیش",
        self::META_AGENT_ID => "شماره کاربری مدیر",
        self::META_AGENT_NAME => "شارژ شده توسط",
        self::META_AGENT_IP => "آدرس IP مدیر",
        self::META_AGENT_CHANGER_ID => "آیدی مدیر تغییر وضعیت دهنده",
        self::META_AGENT_CHANGER_NAME => "نام مدیر تغییر وضعیت دهنده",
        self::META_AGENT_CHANGER_IP => "آدرس IP مدیر تغییر وضعیت دهنده",
        self::META_DIET_PACKAGE_ID => "شماره پکیج رژیم",
        self::META_DIET_ID => "ایدی رژیم دریافتی",
        self::META_DIET_NAME => "نام رژیم دریافتی",
    ];
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
