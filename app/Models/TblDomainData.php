<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TblDomainData extends Model
{

    protected $fillable = [
        'list_id',
        'domain_name',
        'dmarc_flag',
        'dkim_flag',
        'process_status'
    ];
}
