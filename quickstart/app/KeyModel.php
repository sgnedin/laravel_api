<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyModel extends Model
{
    protected $table = 'keys';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'gen_key'
    ];
}
