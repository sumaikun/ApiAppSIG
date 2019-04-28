<?php

namespace App\Http\Helpers;


class responseHelper
{

    public static function validatorFail($validator)
    {
        $validatorErrors = array();

        foreach ($validator->errors()->getMessages() as $item) {
                array_push($validatorErrors, $item[0]);
        }

        return response()->json($validatorErrors, 400);

    }


}
