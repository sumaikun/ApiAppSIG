<?php

namespace App\Http\Helpers;


class sqlHelper
{

    public static function idGenerator($table,$id){

    		$query = $table::withTrashed()->pluck($id)->last();
        if($query!=null)
    		{
    			return $query+1;
    		}
    		else {
    			return 1;
    		}
  	}
    
}
