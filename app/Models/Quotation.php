<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Quotation extends Model
{
    use HasFactory;
    protected $table = 'quotation';
    protected $fillable = [
        'Quotation_ID',
        'Company_ID',
        'valid',
        'check-in',
        'check-out',
        'day',
        'night',
        'adult',
        'children',
        'max-discount',
        'ComRateCode',
        'freelancer-aiffiliate',
        'commission-rate-code',
        'event-format',
        'vat-type',
        'comment',
        'Document_issuer',
    ];
    public function  company()
    {
        return $this->hasOne(companys::class, 'Profile_ID', 'Company_ID');
    }
    public function  contact()
    {
        return $this->hasOne(representative::class, 'Company_ID', 'Company_ID');
    }
    public function  freelancer()
    {
        return $this->hasOne(Freelancer_Member::class, 'Profile_ID', 'freelanceraiffiliate');
    }
    public function  user()
    {
        return $this->hasOne(User::class, 'id', 'Document_issuer');
    }
}
