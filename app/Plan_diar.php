<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan_diar extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket_offline';

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
        'fecha',
        'cantidad',
        'modulo',
        'operacion',
        'team_leader',
        'efic',
        'min_producidos',
        'proyeccion_minutos',
        'created_at',
        'updated_at'

    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
