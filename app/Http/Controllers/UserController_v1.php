<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\RestFullApi\Facade\ResponseApi;
// use App\RestFullApi\ResponseApi;
use App\RestFullApi\ResponseApiBuilder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        try {
            //level 1
            // return response()->json([
            //     'data'=>User::all(),
            //     'msg'=>'Show User'
            // ],Response::HTTP_OK);

            //level 2
            // $result=new ResponseApi();
            // $result->setMessage('Show User');
            // $result->setDate(User::all());
            // $result->setStatus(Response::HTTP_OK);

            // return $result->response();

            //level3
            // return (new ResponseApiBuilder())->withMessage('Show User')->withData(User::all())->withStatus(Response::HTTP_OK)->Builder();

            //level4
            return ResponseApi::withData(User::all())
            ->withMessage('Show User')
            ->withStatus(Response::HTTP_OK)
            ->Builder();
   

        } catch (\Throwable $th) {
            return response()->json([
                'data'=>'Server Error',
                'msg'=>$th->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try{
            $validation=Validator::make($request->all(),[
                'name'=>['required','string','min:3','max:255'],
                'lastname'=>['required','string','min:3','max:255'],
                'password'=>['required','string','min:8','max:18'],
                'email'=>['required','email','unique:users','min:3','max:255'],
            ]);

            if($validation->failed()){
                return response()->json([
                    'data'=>$validation->errors(),
                    'msg'=>'Fix the errors'
                ],Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $inputs=$validation->validated();
            $inputs['password']=Hash::make($inputs['password']);
            User::create($inputs);

            return response()->json([
                'data'=>User::all(),
                'msg'=>'User Created'
            ],Response::HTTP_ACCEPTED);

        }catch(Exception $e){
            return response()->json([
                'data'=>'Server Error',
                'msg'=>$e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        try{
            return response()->json([
                'data'=>User::find($user),
                'msg'=>'Show User'
            ]);
        }catch(Exception $e){
            return response()->json([
                'data'=>'Server Error',
                'msg'=>$e->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request,User $user)
    {
        try{
            $validation=validator::make($request->all(),[
                'name'=>['required','string','min:3','max:255'],
                'lastname'=>['required','string','min:3','max:255'],
                'password'=>['required','string','min:8','max:18'],
                'email'=>['required','email','unique:users,email,'.$user->id,'min:3','max:255'],
            ]);
            if($validation->failed()){
                return response()->json([
                    'data'=>'Fixe Items',
                    'msg'=>$validation->errors()
                ]);
            }

            $inputs=$validation->validated();
            $user->update($inputs);

            return response()->json([
                'data'=>$user,
                'msg'=>'User Updated',
            ]);
        }catch(Throwable $th){
            return response()->json([
                'data'=>'Server Error',
                'msg'=>$th->getMessage()
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }   
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'data'=>'User Deleted',
            'msg'=>'Not Found User'
        ],Response::HTTP_NO_CONTENT);
    }
}
