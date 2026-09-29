<?php
/*
©  2021 Inventcolabs Pvt. Ltd. ,  All rights reserved.
*/
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class BankData extends Model
{    
    // protected $hidden = [
    //     'updated_at',
    // ];
    protected $fillable = [
        'user_id',
        'method_of_payment',
        'account_holder',
        'account_holder_name',
        'account_number',
        'iban',
        'vat_number',
        'fiscal_code',
        'route',
        'invoicing_type',
        'retention',
        'ledger_account',
        'bic_swift',
        'tax',
        'bank_name',
        'cnae_code',
        'status',
    ];
    protected $tab = 'bank_data';
}
