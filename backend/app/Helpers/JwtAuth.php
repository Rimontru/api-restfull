<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\User;

/**
 * | Libreria para utentican usuario mediante Json Tokens
 */
class JwtAuth {
	public $key;


	public function __construct(){
		$this->key = 'Lorissa-SanJuan-Mondragon::09/2015-08/1994';
	}
	


	public function signup($email, $password, $getToken=null){
		$user = User::where(
				array(
					'email' => $email, 
					'password' => $password 
				))->first();

		$signup = false;
		if ( is_object($user) ) {
			$signup = true;			
		}

		if ($signup) {
			//Genera y Devuelve el token

			$token = array(
				'sub' => $user->id,/*por norma se usa sub como id en JWT*/
				'email' => $user->email,
				'name' => $user->name,
				'surname' => $user->surname,
				'lat' => time(),
				'exp' => time() + (7*24*60*60) /*expira el token*/
			);

			//token encodificado del usuario
			$jwt = JWT::encode($token, $this->key, 'HS256');
			$decode = JWT::decode($jwt, $this->key, array('HS256'));


			if (is_null($getToken))
				return $jwt;
			else
				return $decode;


		} else {
			//Devuelve el error
			return array('status' => 'error', 'message' => 'Login ha fallado !!');			

		}

	} 


	public function checkToken($jwt, $getIdentity = false){
		$auth	= false;

		try{
			$decoded = JWT::decode($jwt, $this->key, array('HS256'));		
		}catch(\UnexpectedValueException $e){
			$auth	= false;
		}catch(\DomainException $e){
			$auth	= false;
		}

		if (isset($decoded)  && is_object($decoded) && isset($decoded->sub) )
			$auth	= true;
		else
			$auth	= false;

		if ( $getIdentity )
			return $decoded;


		return $auth;
	}



}