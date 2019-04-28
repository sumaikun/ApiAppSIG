<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use \Illuminate\Support\Facades\Validator;
use App\Http\Helpers\responseHelper;
/**
* @OA\Info(title="API", version="1.0")
*
* @OA\Server(url="http://swagger.local")
*/
class UserController extends Controller
{
    /**
    * @OA\Get(
    *     tags={"usuario"},
    *     path="/api/users",
    *     summary="Mostrar usuarios",
    *     @OA\Response(
    *         response=200,
    *         description="Usuarios encontrados."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function get()
    {
        return User::all();
    }

    /**
    * @OA\Get(
    *     tags={"usuario"},
    *     path="/api/users/{id}",
    *     summary="Mostrar usuario por id",
    *     @OA\Parameter(
    *       name="id",
    *       in="path",
    *       required=true,
    *       description="id del usuario",
    *       @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Usuario encontrado."
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

      $result = User::find($id);
      return (  $result  ) ?  $result  :    response()->json(null, 204);
    }
}
