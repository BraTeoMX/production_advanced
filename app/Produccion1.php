<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produccion1 extends Model
{
    protected $table = 'produccion1';

    protected $fillable = [
        // otros campos
        'supervisor_id',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];



}
?>
