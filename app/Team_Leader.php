<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team_Leader extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cat_team_leader';

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
        'estatus',
        'deleted_at'
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
  
}
