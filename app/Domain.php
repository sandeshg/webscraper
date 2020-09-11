<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    //
    public function domains(){
        return $this->belongsTo('App\Project');
    }
   
}
