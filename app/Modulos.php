<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class modulos extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cat_modulos';

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
        'Modulo',
        'sam',
        'op_real',
        'op_presencia',
        'pxhrs',
        'capacitacion',
        'utility',
        'estatus',
        'deleted_at',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
