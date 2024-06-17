<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Planeacion extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'planeacion';

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
        'team_leader',
        'area',
        'modulo',
        'estilo',
        'op_real',
        'op_presencia',
        'pxhrs',
        'capacitacion',
        'utility',
        'sam_P07',
        'meta_proyectada',
        'sam',
        'deleted_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
  
}
