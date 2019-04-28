<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
  use SoftDeletes;
  protected $table = 'reg_actividades';

  public function typeActivity(){

    return $this->belongsTo('App\models\ListActivities','tp_actividad');
  }

  public function customers(){

    return $this->belongsTo('App\models\ListEnterprises','tp_empresa');
  }

  public function enterprises(){

      return $this->belongsTo('App\models\ListEnterprises','tp_propia');
  }

 public function users(){

    return $this->belongsTo('App\models\User','usuario');
  }
}
