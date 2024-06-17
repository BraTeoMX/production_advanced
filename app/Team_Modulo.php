<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team_Modulo extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_modulo';

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
        'team_leader',
        'modulo',
        'planta',
        'cliente',
        'sam',
        'op_real',
        'op_presencia',
        'pxhrs',
        'capacitacion',
        'utility',
        'piezas_meta',
        'meta_cumplida',
        'min_x_producir',
        'min_presencia',
        'min_presencia_netos',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
