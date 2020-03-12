<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactTel extends Model
{
    protected $table = 'contacts_tels';

    protected $fillable = [
        'contact_id',
        'numero',
        'tipo'
    ];
}
