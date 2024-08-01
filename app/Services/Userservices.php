<?php

namespace App\Services;

use App\Base\ServiceResult;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Userservices
{  
    public function register($inputs)
    {
        try{
            $inputs['password']=Hash::make($inputs['password']);
            $user=User::create($inputs);
            return new ServiceResult(true,$user);

        }catch(Throwable $th){
            return new ServiceResult(false,$th->getMessage());

        }
    }
}
