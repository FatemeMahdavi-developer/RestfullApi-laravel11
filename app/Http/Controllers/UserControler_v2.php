<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\RestFullApi\Facade\ApiResponse;
use App\Services\Userservices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserControler extends Controller
{
    public Userservices $Userservices;
    public function __construct()
    {
        $this->Userservices=new Userservices();
    }

    public function index()
    {
        try{
            return ApiResponse::withData(User::all())
                ->withMessage('Show All user')
                ->withStatus(Response::HTTP_OK)
                ->Builder();
        }catch(Throwable $th){
            return ApiResponse::withData($th->getMessage())
                ->withMessage('Server Error')
                ->withStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->Builder();
        }
    }

    public function store(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'name'=>['required','string','min:3','max:255'],
            'lastname'=>['required','string','min:3','max:255'],
            'email'=>['required','email','unique:users','min:3','max:255'],
            'password'=>['required','string','min:8','max:18'],
        ]);

        if($validation->fails()){
            return response()->json([
                'data'=>$validation->errors(),
                'msg'=>'Fixed Items'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $inputs=$validation->validated();
        $result=$this->Userservices->register($inputs);
    
        if($result->ok){
            return ApiResponse::withData($result->data)->withMessage('User Added')->withStatus(Response::HTTP_ACCEPTED)->Builder();
        }else{
            return ApiResponse::withData($result->data)->withMessage('Server Error')->withStatus(Response::HTTP_INTERNAL_SERVER_ERROR)->Builder();
        }
    }

    public function show(string $id)
    {
        try{
            return ApiResponse::withData(User::all())
                ->withMessage('Show user')
                ->withStatus(Response::HTTP_OK)
                ->Builder();
        }catch(Throwable $th){
            return ApiResponse::withData($th->getMessage())
                ->withMessage('Server Error')
                ->withStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->Builder();
        }
    }

    public function update(Request $request, User $user)
    {
        $validation=validator::make($request->all(),[
            'name'=>['required','string','min:3','max:255'],
            'lastname'=>['required','string','min:3','max:255'],
            'email'=>['required','email','unique:users,email,'.$user->id,'min:3','max:255'],
            'password'=>['required','string','min:8','max:18'],
        ]);
        if($validation->fails()){
            return response()->json([
                'data'=>$validation->errors(),
                'msg'=>'Fixed Items'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        try{
            $inputs=$validation->validated();
            $inputs['password']=Hash::make($inputs['password']);
            $user->update($inputs);

            return response()->json([
                'data'=>$user,
                'msg'=>'User Updated'
            ],Response::HTTP_OK);

        }catch(Exception $e){
            return response()->json([
                'data'=>$e->getMessage(),
                'msg'=>'Server Error'
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(User $user)
    {
        try{
            $user->delete();
            return ApiResponse::withData('User Deleted')
                ->withStatus(Response::HTTP_NO_CONTENT)
                ->Builder();
        }catch(Throwable $th){
            return ApiResponse::withData($th->getMessage())
                ->withMessage('Server Error')
                ->withStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->Builder();
        }
    }
}
