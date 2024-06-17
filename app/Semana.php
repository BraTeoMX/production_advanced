<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Semana extends Model
{
    protected $table = 'semanas';

    public $timestamps = false;

    protected $keyType = 'integer';

    protected $fillable = [
        'anio',
        'semana',
        'color',
        'titulo'
    ];
}
