<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use \Illuminate\Support\Facades\Validator;
/**
*
*
* @OA\Server(url="http://localhost:8000")
*/

class AuthController extends Controller
{
  /**
  * @OA\POST(
  *     tags={"usuario"},
  *     path="/api/login",
  *     summary="Login de usuarios",
  *   @OA\RequestBody(
  *       required=false,
  *       @OA\MediaType(
  *           mediaType="application/x-www-form-urlencoded",
  *           @OA\Schema(
  *               type="object",
  *               required={"email"},
  *               @OA\Property(
  *                   property="email",
  *                   description="Correo del usuario",
  *                   type="string"
  *               ),
  *               @OA\Property(
  *                   property="password",
  *                   description="Contraseña del usuario",
  *                   type="string",
  *                   format="password"
  *               ),
  *           )
  *       )
  *     ),
  *     @OA\Response(
  *         response=200,
  *         description="Login realizado."
  *     ),
  *     @OA\Response(
  *         response=400,
  *         description="Request mal mandado."
  *     ),
  *     @OA\Response(
  *         response=401,
  *         description="Intento de login rechazado."
  *     ),
  *     @OA\Response(
  *         response=405,
  *         description="metodo HTTP no permitido."
  *     ),
  *     @OA\Response(
  *         response="default",
  *         description="Ha ocurrido un error."
  *     )
  * )
  */
    public function login(Request $request)
   {

     $validator = Validator::make($request->all(), [
        'email'       => 'required|string|email',
        'password'    => 'required|string',
        'remember_me' => 'boolean',
      ]);

      if($validator->fails()) {

        $validatorErrors = array();

        foreach ($validator->errors()->getMessages() as $item) {
                array_push($validatorErrors, $item[0]);
        }

        return response()->json($validatorErrors, 400);

      }

       $credentials = array(
  			'usu_email' => $request->email,
  			'password' => $request->password,
  			'usu_estado' => 'activo'
  		);

       //$credentials = request(['email', 'password']);


       if (!Auth::attempt($credentials)) {
           return response()->json([
               'message' => 'Unauthorized'], 401);
       }
       else
       {
         $usuario = User::where('usu_email', '=', Input::get('usu_usuario')."@grupo-sig.com")->get();
       }
       return response()->json(
         $usuario
       );
   }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
