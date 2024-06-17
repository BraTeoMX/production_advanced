<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion1 extends Model
{
    protected $table = 'produccion1';

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
        'nombre',
        'modulo',
        'semana1',
        'semana2',
        'semana3',
        'semana4',
        'semana5',
        'semana6',
        'semana7',
        'semana8',
        'semana9',
        'semana10',
        'semana11',
        'semana12',
        'semana13',
        'semana14',
        'semana15',
        'semana16',
        'semana17',
        'semana18',
        'semana19',
        'semana20',
        'semana21',
        'semana22',
        'semana23',
        'semana24',
        'semana25',
        'semana26',
        'semana27',
        'semana28',
        'semana29',
        'semana30',
        'semana31',
        'semana32',
        'semana33',
        'semana34',
        'semana35',
        'semana36',
        'semana37',
        'semana38',
        'semana39',
        'semana40',
        'semana41',
        'semana42',
        'semana43',
        'semana44',
        'semana45',
        'semana46',
        'semana47',
        'semana48',
        'semana49',
        'semana50',
        'semana51',
        'semana52',
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
