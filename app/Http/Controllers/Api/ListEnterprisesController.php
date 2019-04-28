<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ListEnterprises;
use \Illuminate\Support\Facades\Validator;

class ListEnterprisesController extends Controller
{
    /**
    * @OA\Get(
    *     tags={"Listar empresas "},
    *     path="/api/enterprises",
    *     summary="Muestra todas las empresas asociadas a sig",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos las empresas."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function getEnterprises(Request $request)
    {
       $result = ListEnterprises::where('cliente', 0)->get();
       return (  $result  ) ?  $result  :  response()->json(null, 204);
    }

    /**
    * @OA\Get(
    *     tags={"Listar empresas "},
    *     path="/api/customers",
    *     summary="Muestra todos los clientes",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos las clientes."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function getCustomers(Request $request)
    {
      $result = ListEnterprises::where('cliente', 1)->get();
      return (  $result  ) ? $result  :  response()->json(null, 204);
    }
}
