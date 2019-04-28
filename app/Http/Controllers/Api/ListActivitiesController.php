<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListActivities;
use App\Http\Helpers\responseHelper;
use \Illuminate\Support\Facades\Validator;

class ListActivitiesController extends Controller
{


    /**
    * @OA\Get(
    *     tags={"Lista Actividades"},
    *     path="/api/listActivities",
    *     summary="Mostrar lista de todos los tipos de actividades",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los tipos de actividades."
    *     ),
    *     @OA\Response(
    *         response=204,
    *         description="No hay resultados que mostrar."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function get(Request $request)
    {
        $result = ListActivities::all();
        return ( $result  ) ?  $result  :    response()->json(null, 204);


    }


    /**
    * @OA\Get(
    *     tags={"Lista Actividades"},
    *     path="/api/listActivities/{id}",
    *     summary="Mostrar datos de tipo de actividad por id",
    *      @OA\Parameter(
    *        name="id",
    *        in="path",
    *        description="id especifico a retornar",
    *        example= "1",
    *        required= true,
    *        @OA\Schema(type="integer", format="int32")
    *       ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los tipos de actividades."
    *     ),
    *     @OA\Response(
    *         response=204,
    *         description="No hay resultados que mostrar."
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Request mal mandado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function getByid($id)
    {
      $validator = Validator::make([
            'id' => $id
          ], [
         'id'       => 'required|integer',
      ]);

      if($validator->fails()) { return responseHelper::validatorFail($validator); }

      $result = ListActivities::find($id);
      return (  $result  ) ?  $result  :    response()->json(null, 204);
    }
}
