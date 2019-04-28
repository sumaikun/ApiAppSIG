<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListEnterprises extends Model
{
  use SoftDeletes;

  protected $table = "lista_empresas";

}
