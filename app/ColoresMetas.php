<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColoresMetas extends Model
{
    protected $table = 'produccion';

    public $timestamps = false;
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
        'numero',
        'codigo_color',
        'updated_at',
        'created_at',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];



}
?>
