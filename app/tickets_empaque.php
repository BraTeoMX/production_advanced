<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tickets_empaque extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ticket_empaque';

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
        'created_at',
        'updated_at'

    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
