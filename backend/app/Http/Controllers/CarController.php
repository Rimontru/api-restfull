<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Car;


class CarController extends Controller {		
		/*metodo de validacion por token para el controlador*/
		private function secure($request){

			$hash = $request->header('Authorization', null);
    	$jwtAuth = new JwtAuth();

    	if( $jwtAuth->checkToken($hash) )
    		return $jwtAuth->checkToken($hash, true);    		
    	else
    		return false;
		}

    /*metodo de inicio del controlador*/
    public function index(){
    		
      $cars = Car::all()->load('user');
      $datas = array(
        'cars' => $cars,
        'status' => 'success',
      );

    return response()->json($datas, 200);
    }

    /*metodo de ver por coche*/
    public function show($id){
        
      $car = Car::find($id)->load('user');
      $datas = array(
        'car' => $car,
        'status' => 'success',
      );

    return response()->json($datas, 200);
    }

    /*metodo de crear nuevo*/
    public function store(Request $request){
    	//Validar el Token en la pagina
    	if( !is_bool($data = $this->secure($request)) ) : 
    		
    		//Recoger los valores por post
    		$json = $request->input('json', null);
    		$params = json_decode($json);

    		$params_array = json_decode($json, true);

    		//Consigue los datos del usuario identificado
    		$user = $data;

    		//Validacion de datos
  			$validate = \Validator::make($params_array, [
						'title' => 'required|max:255',
						'description' => 'required|max:255',
						'price' => 'required|numeric',
				]);

        if ($validate->fails()) {
          return response()->json($validate->errors(), 400);
        }

    		//Setear los valores del post coche
    		$car = new Car();
    		$car->user_id = $user->sub;
    		$car->title = $params->title;
    		$car->description = $params->description;
    		$car->price = $params->price;
    		$car->status = 'true';/*$params->status*/

    		//Guardar los valores del post coche
    		$car->save();

    		$datas = array(
    			'car' => $car, 
    			'status' => 'success', 
    			'code' => 200
    		);

    	else:

    		$datas = array(
    			'message' => 'Opps::ACCESO DENEGADO...', 
    			'status' => 'error', 
    			'code' => 400
    		);

    	endif;

    return response()->json($datas, 200);
    }

    /*metodo de actualizacion de datos*/
    public function update($id, Request $request){
      //Validamos autenticacion de usuarios
      if( !is_bool($data = $this->secure($request)) ) : 

        //Recoger los valores por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //Validar los datos
        $validate = \Validator::make($params_array, [
            'title' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        if ($validate->fails()) {
          return response()->json($validate->errors(), 400);
        }

        //Actualizar el registro
        $car = Car::where('id', $id)->update($params_array);
        $datas = array(
          'car' => $params, 
          'status' => 'success', 
          'code' => 200
        );

      else:

        $datas = array(
          'message' => 'Opps::ACCESO DENEGADO...', 
          'status' => 'error', 
          'code' => 400
        );

      endif;
    return response()->json($datas, 200);
    }



    /*metodo de eliminacion de datos*/
    public function destroy($id, Request $request){
      //Validamos autenticacion de usuarios
      if( !is_bool($data = $this->secure($request)) ) : 

        //Busca el registro
        $car = Car::find($id);
        if (!is_null($car)){
          //Elimina el registro
          $car->delete();
          //Devuelve la respuesta de borrado
          $datas = array(
            'car' => $car, 
            'status' => 'success', 
            'code' => 200
          );
          
        } else
          //No encontro el registro
          $datas = array(
            'message' => 'no se encontro', 
            'status' => 'error', 
            'code' => 300
          );
        
      else:
        //Devuelve el error de autenticacion
        $datas = array(
          'message' => 'Opps::ACCESO DENEGADO...', 
          'status' => 'error', 
          'code' => 400
        );

      endif;
    return response()->json($datas, 200);
    }

}
