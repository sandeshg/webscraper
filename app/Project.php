<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    //Table name
    protected $table='projects';
    //Primary key
    public $primaryKey = 'id';

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function domains(){
        return $this->hasMany('App\Domain');
    }
    
}
