<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
use Illuminate\Http\Response as HttpResponse;

class AuthController extends Controller{

    public function login(Request $request){ 

        $validator = Validator::make($request->all(), [
            'user' => 'required|email',
            'password' => 'required'
        ]);
            
        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }
        
        $data=$request->all();
        try{
        
            $query = DB::select("CALL validar_usuario_password('".$data['user']."', '".$data['password']."', NOW(), @salida , @codigo, @mensaje);");
        
            $query = DB::select("SELECT @salida AS data;");
            
            if(count($query)>0){
                $query = json_decode($query[0]->data);
                $user = User::find($query->user_id);
                $tokenResult = $user->createToken('uer vsl');
                $token = $tokenResult->token;
                $token->expires_at = Carbon::now()->addDays(1);
                $query->token=$tokenResult->accessToken;
                return response()->json([$query],HttpResponse::HTTP_OK);
            }else{
                return response()->json([
                    "code"=>"401",
                    "msg" =>"Unhautorized",
                ],HttpResponse::HTTP_UNHAUTORIZED);
            }
            
        
            }catch(\Exception $e){
                \Log::error($e->getMessage());
                return response()->json([
                    'code' => '1006',
                    'message' => $e->getMessage(),
                    'errors' => [__('messages.1006')]
                ], HttpResponse::HTTP_BAD_REQUEST);
            }
        
    }

    public function remember_password(Request $request){
        $validator = Validator::make($request->all(), [
            'user' => 'required|email',
            'app_user' => 'required',
            'transaction_id'=>'required'
        ]);
            
        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        } 

        try{

            $data=$request->all();
            $query=DB::select("call recordar_contraseña('".$data['user']."',@v_password, @v_execution_code, @v_execution_message);");
            $query=DB::select("SELECT @v_password as data");
            $date = date('Ymd',time());
            
            $query = json_decode($query[0]->data);

            if(!empty($query) && isset($query)){
                
                return response()->json([
                    'code'=>'200',
                    'msg'=>'ok',
                    'data'=>$query,
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK);
            }else{
                return response()->json([
                    'code'=>'200',
                    'msg'=>'Usuario no encontrado',
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK);
            }
            

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'code' => '1006',
                'message' => $e->getMessage(),
                'errors' => [__('messages.1006')]
            ], HttpResponse::HTTP_BAD_REQUEST);
        }

        
    }
    
    public function change_password(Request $request){

        $validator = Validator::make($request->all(), [
            'user' => 'required|email',
            'aplication'=>'required',
            'current_password'=>'required',
            'new_password'=>'required',
            'app_user' => 'required',
            'transaction_id'=>'required'
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }
        try{
            $data=$request->all();
            $date = date('Y-m-d',time());
            $query=DB::select("CALL cambiar_contraseña('".$data['user']."','".$data['current_password']."','".$data['new_password']."','".$date."', @v_execution_code, @v_execution_message);");
            
            $query=DB::select("SELECT @v_execution_code as data;");

            $query = json_decode($query[0]->data);
            $date = date('Ymd',time());
            if(!empty($query) && isset($query)){

                return response()->json([
                    'code'=>'200',
                    'msg'=>'ok',
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK);

            }else{
                return response()->json([
                    'code'=>'200',
                    'msg'=>'Datos incorrectos',
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK);
            }

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'code' => '1006',
                'message' => $e->getMessage(),
                'errors' => [__('messages.1006')]
            ], HttpResponse::HTTP_BAD_REQUEST);
        }

    }

    public function block_user(Request $request){
        
        $validator = Validator::make($request->all(), [
            'user' => 'required|email',
            'aplication'=>'required',
            'app_user' => 'required',
            'transaction_id'=>'required'
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }
        try{

            $data = $request->all();
            $date = date('Y-m-d',time());
            $query = DB::select("call uer.bloquear_usuario('".$data['user']."', 1 , '".$date."', @v_execution_code, @v_execution_message);");
            $query = DB::select("SELECT @v_execution_code as data");
            $query = json_decode($query[0]->data);
            if($query==0){
                return response()->json([
                    'code'=>'200',
                    'msg'=>'Datos incorrectos',
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK); 
            }else{
                return response()->json([
                    'code'=>'200',
                    'msg'=>'Datos incorrectos',
                    'transaction_id'=>'UER'.$date,
                ],HttpResponse::HTTP_OK);
            }

        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json([
                'code' => '1006',
                'message' => $e->getMessage(),
                'errors' => [__('messages.1006')]
            ], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

}
