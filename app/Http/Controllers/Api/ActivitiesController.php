<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ListActivities;
use App\Http\Helpers\responseHelper;
use App\Http\Helpers\sqlHelper;
use \Illuminate\Support\Facades\Validator;

class ActivitiesController extends Controller
{
    /**
    * @OA\POST(
    *     tags={"actividades"},
    *     path="/api/activity",
    *     summary="Registrar actividad",
    *   @OA\RequestBody(
    *       required=false,
    *       @OA\MediaType(
    *           mediaType="application/x-www-form-urlencoded",
    *           @OA\Schema(
    *               type="object",
    *               required={},
    *               @OA\Property(
    *                   property="fecha",
    *                   description="Fecha de la actividad",
    *                   example= "2017-01-01",
    *                   type="string",
    *                   format="date"
    *               ),
    *               @OA\Property(
    *                   property="actividad",
    *                   description="Tipo de actividad (id del tipo de actividad) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="cliente",
    *                   description="Empresa o organización del cliente (id de la empresa del cliente) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="empresa",
    *                   description="Empresa o organización que atiende al cliente (id de la empresa propia) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="filial",
    *                   description="Entidad filial",
    *                   example= "Bogotá",
    *                   type="string",
    *               ),
    *              @OA\Property(
    *                   property="subcontratista",
    *                   description="Subcontratista de la activididad",
    *                   example= "Gas natural",
    *                   type="string",
    *               ),
    *              @OA\Property(
    *                   property="descripcion",
    *                   description="Descripción de la activididad",
    *                   example= "Ejemplo de descripción",
    *                   type="string",
    *              ),
    *              @OA\Property(
    *                   property="hora_inicial",
    *                   description="Hora inicial de la activididad",
    *                   example= "08:50",
    *                   type="string",
    *              ),
    *              @OA\Property(
    *                   property="hora_final",
    *                   description="Hora final de la activididad",
    *                   example= "13:40",
    *                   type="string",
    *              ),
    *               @OA\Property(
    *                   property="usuario",
    *                   description="id del usuario a registrar la actividad",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *           )
    *       )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividad registrada."
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Request mal mandado."
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Ingreso no autorizado."
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

    public function create(Request $request)
    {

      $validator = Validator::make($request->all(), [
        'fecha' => 'required|date_format:Y-m-d',
        'hora_inicial' => 'required|date_format:H:i',
        'hora_final' => 'required|date_format:H:i|after:hora_inicial',
        'actividad' => 'required|integer',
        'cliente' => 'required|integer',
        'empresa' => 'required|integer',
        'usuario' => 'required|integer'
       ]);

       if($validator->fails()) { return responseHelper::validatorFail($validator); }

       $cross = $this->crossTime($request->fecha,$request->hora_final,$request->hora_inicial,$request->actividad,0,$request->usuario);

       if(!$cross)
       {
           $Actividad = $this->fabricTableObject(null,$request);

           $id = $Actividad->id;

           $Actividad->save();

          return response()->json(["message"=>"¡Actividad registrada!","id"=>$id], 200);
          //return response()->json($Actividad, 200);
       }
       else{
            return response()->json(["message"=>"No se puede registrar actividad ya que se cruza con otra actividad"], 400);
       }
    }
    /**
    * @OA\PUT(
    *     tags={"actividades"},
    *     path="/api/activity/{id}",
    *     summary="Editar actividad",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *     description="id de la actividad a editar",
    *     @OA\Schema(type="string")
    *   ),
    *   @OA\RequestBody(
    *       required=false,
    *       @OA\MediaType(
    *           mediaType="application/x-www-form-urlencoded",
    *           @OA\Schema(
    *               type="object",
    *               required={"fecha"},
    *               @OA\Property(
    *                   property="fecha",
    *                   description="Fecha de la actividad",
    *                   example= "2017-01-01",
    *                   type="string",
    *                   format="date"
    *               ),
    *               @OA\Property(
    *                   property="actividad",
    *                   description="Tipo de actividad (id del tipo de actividad) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="cliente",
    *                   description="Empresa o organización del cliente (id de la empresa del cliente) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="empresa",
    *                   description="Empresa o organización que atiende al cliente (id de la empresa propia) ",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *               @OA\Property(
    *                   property="filial",
    *                   description="Entidad filial",
    *                   example= "Bogotá",
    *                   type="string",
    *               ),
    *              @OA\Property(
    *                   property="subcontratista",
    *                   description="Subcontratista de la activididad",
    *                   example= "Gas natural",
    *                   type="string",
    *               ),
    *              @OA\Property(
    *                   property="descripcion",
    *                   description="Descripción de la activididad",
    *                   example= "Ejemplo de descripción",
    *                   type="string",
    *              ),
    *              @OA\Property(
    *                   property="hora_inicial",
    *                   description="Hora inicial de la activididad",
    *                   example= "08:50",
    *                   type="string",
    *              ),
    *              @OA\Property(
    *                   property="hora_final",
    *                   description="Hora final de la activididad",
    *                   example= "13:40",
    *                   type="string",
    *              ),
    *               @OA\Property(
    *                   property="usuario",
    *                   description="id del usuario a registrar la actividad",
    *                   example= "1",
    *                   type="integer",
    *                   format="int32"
    *               ),
    *           )
    *       )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividad actualizada."
    *     ),
    *     @OA\Response(
    *         response=400,
    *         description="Request mal mandado."
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Ingreso no autorizado."
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
    public function update(Request $request, $id)
    {
        $Actividad = $this->fabricTableObject($id,$request);

        $Actividad->update();

       return response()->json(["message"=>"¡Actividad actualizada!"], 200);
       //return response()->json($Actividad, 200);
    }

    /**
    * @OA\DELETE(
    *     tags={"actividades"},
    *     path="/api/activity/{id}",
    *     summary="Eliminar actividad",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *       description="id de la actividad a eliminar",
    *       @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividad eliminada."
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
    *         response=401,
    *         description="Ingreso no autorizado."
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

    public function deleteByid($id)
    {
        $result = Activity::find($id);
        if ($result) {
          $result->delete();
          return response()->json(["message"=>"¡Actividad eliminada!"], 200);
        }else{
          return response()->json(null, 204);
        }
    }

    /**
    * @OA\GET(
    *     tags={"actividades"},
    *     path="/api/activity/{id}",
    *     summary="Obtener actividad",
    *   @OA\Parameter(
    *     name="id",
    *     in="path",
    *     required=true,
    *       description="id de la actividad a obtener",
    *       @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividad encontrada."
    *     ),
    *     @OA\Response(
    *         response=204,
    *         description="No hay resultados que mostrar."
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Ingreso no autorizado."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function getByid($id)
    {
        $result = Activity::find($id);
        return (  $result  ) ?  $result  :    response()->json(null, 204);
    }

    /**
    * @OA\GET(
    *     tags={"actividades"},
    *     path="/api/activitiesbyUser/{user}",
    *     summary="Obtener actividad por usuario",
    *     @OA\Parameter(
    *       name="user",
    *       in="path",
    *       required=true,
    *       description="id del usuario",
    *     @OA\Schema(type="integer")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividades encontradas."
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Ingreso no autorizado."
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


    /**
    * @OA\GET(
    *     tags={"actividades"},
    *     path="/api/activitiesbyUser/{user}/{date}",
    *     summary="Obtener actividad por usuario y fecha",
    *     @OA\Parameter(
    *       name="user",
    *       in="path",
    *       required=true,
    *       description="id del usuario",
    *     @OA\Schema(type="integer")
    *     ),
    *     @OA\Parameter(
    *       name="date",
    *       in="path",
    *       required=true,    *
    *       description="fecha a filtrar",
    *     @OA\Schema(type="string",example="2019-01-01")
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Actividades encontradas."
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Ingreso no autorizado."
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


    public function getByUser($user,$date = null)
    {
      if($date)
      {
        $result = Activity::where("usuario",$user)->where("fecha",$date)->get();
        return (  $result  ) ?  $result  :    response()->json(null, 204);
      }
      else{
        $result = Activity::where("usuario",$user)->get();
        return ( $result ) ?  $result  :    response()->json(null, 204);
      }

    }


    private function crossTime($fecha,$hora_final,$hora_inicial,$actividad,$id,$usuario)
   {
       $actividad = strtoupper(ListActivities::where('id','=',$actividad)->value('nombre'));
       if($actividad != "DESPLAZAMIENTO")
       {
           $registros = Activity::where('fecha','=',$fecha)->where('usuario','=',$usuario)->where('tp_actividad','!=',61)->where('id','!=',$id)->orderBy('hora_inicio')->get();
           foreach($registros as $registro)
           {

               if($this->float_time($hora_inicial,$registro->hora_final)>=0)
               {
                   $var1 = True;
               }
               else{
                   $var1 = False;
               }
               if($this->float_time($hora_final,$registro->hora_inicio)<=0)
               {
                   $var2 = True;
               }
               else{
                   $var2 = False;
               }

               if($var1==False and $var2==False)
               {
                   return True;
               }

           }
           return False;
       }
       else{
           return False;
       }

   }

   private function float_time($hora1,$hora2){
       $separar[1]=explode(':',$hora1);
       $separar[2]=explode(':',$hora2);

       $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
       $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
       $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];
       return $total_minutos_trasncurridos;
   }

   private function fabricTableObject($id,$request)
   {
       $Actividad = new Activity;

       $id = ($id == null) ?  sqlHelper::idGenerator($Actividad,'id') : $id ;

       $Actividad->id = $id;
       $Actividad->fecha = $request->fecha;
       $Actividad->tp_actividad = $request->actividad;
       $Actividad->tp_empresa = $request->cliente;
       $Actividad->tp_propia = $request->empresa;
       $Actividad->filial = $request->filial;
       $Actividad->subcontratista = $request->subcontratista;
       $Actividad->horas = round($this->float_time($request->hora_final,$request->hora_inicial)/60,2);
       $Actividad->descripcion = $request->descripcion;
       $Actividad->hora_inicio = $request->hora_inicial;
       $Actividad->hora_final = $request->hora_final;
       $Actividad->usuario = $request->usuario;
       return $Actividad;
   }

}
