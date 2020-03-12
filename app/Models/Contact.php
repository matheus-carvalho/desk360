<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = [
        'nome',
        'email',
        'facebook',
        'linkedin'
    ];

    public function Tels()
    {
        return $this->hasMany('App\Models\ContactTel', 'contact_id', 'id');
    }
}
