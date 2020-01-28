<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Validator;
class UsersController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname'=>'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }
        $input = $request->all();
        $input['password'] = \hash('sha256',$input['password']);
        $user = User::create($input);
      
        /**Take note of this: Your user authentication access token is generated here **/
        $data['token'] =  $user->createToken('MyApp')->accessToken;
        return response(['data' => $data, 'message' => 'Account created successfully!', 'status' => true]);
    }
    
     
}
