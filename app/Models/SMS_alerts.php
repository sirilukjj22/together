<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS_alerts extends Model
{
    use HasFactory;

    protected $table = 'sms_alert';
    protected $fillable = [
        'sort',
        'split_ref_id',
        'date',
        'date_into',
        'transfer_from',
        'into_account',
        'amount',
        'amount_before_split',
        'into_qr',
        'booking_id',
        'sequence',
        'transfer_status',
        'agoda_status',
        'split_status',
        'status',
        'status_receive_agoda',
        'status_receive_elexa',
        'remark',
        'date_remark',
        'transfer_remark',
        'other_remark',
        'close_day',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function fullAmount()
    {
        return $this->hasOne(SMS_alerts::class, 'id', 'split_ref_id');
    }

    public static function check_bank($datakey){
        
        $query = Masters::where('category', 'bank')->whereNull('deleted_at')->select('id', 'name_en')->get();

        $bank_name = '';
        if (strtoupper($datakey) == "TMB") {
            $check = Masters::where('name_en', 'TTB')->select('id')->first();
            $bank_name = $check->id;

        } else {
            foreach ($query as $key => $value) {
                if (strtoupper($value->name_en) == strtoupper($datakey)) {
                    $bank_name = $value->id;
                    break;
                }
            }
        }

        if (empty($bank_name)) {
            $check = Masters::where('category', 'bank')->whereNull('deleted_at')->where('name_en', "SCB")->select('id', 'name_en')->first();
            $bank_name = $check->id ?? 0;
        }

        return $bank_name;
    }

    public static function check_account($datakey) {

        $account = "";
        if ($datakey == "x267913") {
            $account = "708-226791-3";
        } elseif ($datakey == "x267921") {
            $account = "708-226792-1";
        } elseif ($datakey == "x273574") {
            $account = "708-227357-4";
        }

        return $account;
    }

    public function transfer_bank()
    {
        return $this->hasOne(Masters::class, 'id', 'transfer_from');
    }

    ## Check Close Day
    public static function checkCloseDay($date) {

        $adate = date('Y-m-d', strtotime($date));

        $check_data = TB_close_days::where('date', $adate)->first();

        if (!empty($check_data)) {
            return 1;
        } else {
            return 0;
        }
    }
}
