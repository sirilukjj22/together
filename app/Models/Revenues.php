<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Revenues extends Model
{
    use HasFactory;

    protected $table = 'revenue';
    protected $fillable = [
        'date',
        'front_cash',
        'front_transfer',
        'front_credit',
        'room_cash',
        'room_transfer',
        'room_credit',
        'fb_cash',
        'fb_transfer',
        'fb_credit',
        'wp_cash',
        'wp_transfer',
        'wp_credit',
        'total_credit',
        'total_credit_agoda',
        'total_elexa',
        'charge',
        'agoda_charge',
        'ev_charge',
        'other_revenue',
        'total_transaction',
        'total_no_type',
        'remark',
        'status',
        'created_by',
        'updated_by',
    ];

    public static function getManualCharge($date, $month, $year, $type, $status) {
        $day_now = date_create($date)->format('d');
        $symbol = $day_now == "01" ? "=" : "<=";

        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $status)->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', $date)->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit as total')->first();
        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $status)->where('revenue_credit.revenue_type', $type)->whereDay('date', $symbol, $day_now)->whereMonth('revenue.date', $month)->whereYear('revenue.date', $year)->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit  as total')->first();
        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', $status)->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', '<=', $date)->select(DB::raw("(SUM(revenue_credit.credit_amount) - revenue.total_credit) as total_credit, SUM(revenue_credit.credit_amount) as credit_amount"), 'revenue.total_credit  as total')->first();

        // dd($sum_revenue);
        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->credit_amount : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0,
            'fee_date' => isset($sum_revenue) && $sum_revenue->total > 0 ? $sum_revenue->total_credit : 0,
            'fee_month' => isset($sum_revenue_month) && $sum_revenue_month->total > 0 ? $sum_revenue_month->total_credit : 0,
            'fee_year' => isset($sum_revenue_year) && $sum_revenue_year->total > 0 ? $sum_revenue_year->total_credit : 0,
            'total' => (isset($sum_revenue) ? $sum_revenue->credit_amount : 0) - (isset($sum_revenue) ? $sum_revenue->total_credit : 0),
            'total_month' => (isset($sum_revenue_month) ? $sum_revenue_month->credit_amount : 0) - (isset($sum_revenue_month) ? $sum_revenue_month->total_credit : 0),
            'total_year' => (isset($sum_revenue_year) ? $sum_revenue_year->credit_amount : 0) - (isset($sum_revenue_year) ? $sum_revenue_year->total_credit : 0)
        ];

        return $data;
    }

    public static function getManualAgodaCharge($date, $month, $year, $type, $status) {
        $day_now = date_create($date)->format('d');
        $symbol = $day_now == "01" ? "=" : "<=";

        // dd($day_now);

        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
        ->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', $date)
        ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
        ->where('revenue_credit.revenue_type', $type)->whereDay('date', '<=', $day_now)->whereMonth('revenue.date', $month)
        ->whereYear('revenue.date', $year)
        ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
        ->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', '<=', $date)
        ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))->first();

        $sum_revenue_no_paid_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
        ->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', '<=', $date)->where('revenue_credit.receive_payment', 0)
        ->select(DB::raw("(SUM(revenue_credit.agoda_charge) - SUM(revenue_credit.agoda_outstanding)) as total_credit_agoda, SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->agoda_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->total_credit_agoda : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->total_credit_agoda : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->total_credit_agoda : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->agoda_outstanding : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->agoda_outstanding : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->agoda_outstanding : 0,
            'total_no_paid' => isset($sum_revenue_no_paid_month) ? $sum_revenue_no_paid_month->agoda_outstanding : 0,
        ];

        return $data;
    }

    public static function getManualTotalAgoda() {

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
        ->where('revenue_credit.revenue_type', 1)->where('revenue_credit.receive_payment', 0)
        ->select(DB::raw("SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))->first();

        $result = isset($sum_revenue_month) ? $sum_revenue_month->agoda_outstanding : 0;

        return $result;
    }

    public static function getManualEvCharge($date, $month, $year, $type, $status) {
        $day_now = date_create($date)->format('d');
        $symbol = $day_now == "01" ? "=" : "<=";

        // dd($day_now);

        $sum_revenue = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', $date)
        ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))->first();

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', $type)->whereDay('date', '<=', $day_now)->whereMonth('revenue.date', $month)->whereYear('revenue.date', $year)
        ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))->first();

        $sum_revenue_year = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')
        ->where('revenue_credit.status', 8)->where('revenue_credit.revenue_type', $type)->whereDate('revenue.date', '<=', $date)
        ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, (SUM(revenue_credit.ev_fee) + SUM(ev_vat)) as ev_fee, SUM(revenue_credit.ev_revenue) as ev_revenue"))->first();

        $data[] = [
            'revenue_credit_date' => isset($sum_revenue) ? $sum_revenue->ev_charge : 0,
            'revenue_credit_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_charge : 0,
            'revenue_credit_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_charge : 0,
            'revenue_credit_between' => isset($sum_revenue_year) ? $sum_revenue_year->ev_charge : 0,
            'fee_date' => isset($sum_revenue) ? $sum_revenue->ev_fee : 0,
            'fee_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_fee : 0,
            'fee_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_fee : 0,
            'fee_between' => isset($sum_revenue_year) ? $sum_revenue_year->ev_fee : 0,
            'total' => isset($sum_revenue) ? $sum_revenue->ev_revenue : 0,
            'total_month' => isset($sum_revenue_month) ? $sum_revenue_month->ev_revenue : 0,
            'total_year' => isset($sum_revenue_year) ? $sum_revenue_year->ev_revenue : 0,
            'total_between' => isset($sum_revenue_year) ? $sum_revenue_year->ev_revenue : 0
        ];

        return $data;
    }

    public static function getManualTotalEv() {

        $sum_revenue_month = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 8)
        ->where('revenue_credit.revenue_type', 8)
        ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, SUM(revenue_credit.ev_revenue) as ev_revenue"))->first();

        $result = isset($sum_revenue_month) ? $sum_revenue_month->ev_revenue : 0;

        return $result;
    }
}
