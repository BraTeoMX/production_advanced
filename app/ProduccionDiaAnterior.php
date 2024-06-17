<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProduccionDiaAnterior extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produccion_dia_anterior';


    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = [

        'id',
        'dia_anterior',
        'producidasI',
        'producidasII',
    ];

}

