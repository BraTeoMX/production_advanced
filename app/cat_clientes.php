<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cat_clientes extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cat_clientes';

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
        'clientes',

    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

}
