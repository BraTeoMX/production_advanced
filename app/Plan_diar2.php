<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan_diar2 extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planeacion_diaria';

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
        'id_planeacion',
        'team_leader',
        'Modulo',
        'piezas',
        'efic',
        'min_producidos',
        'proyeccion_minutos',
        'efic_total',
        'created_at',
        'updated_at'

    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
