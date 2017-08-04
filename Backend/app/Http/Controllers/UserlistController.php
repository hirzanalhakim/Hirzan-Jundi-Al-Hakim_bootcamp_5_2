<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use JWTAuth;

use App\UserList;

class UserlistController extends Controller
{
    function getData(){

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        $userList = UserList::get();

        return response()->json($userList, 200);
    }

    function addData(Request $request){

        //Transaction berguna ketika kita mempunyai banyak input fungsi ini berguna untuk melakukan
        //RollBack apabila ada input yang gagal, dengan menggunakan fungsi DB::rollBack() dibawah
        // Jadi Transaction mempunyain 3 fungsi, yaitu DB::beginTransaction(), DB::commit(), dan DB::rollBack();
        DB::beginTransaction();

        try{
            $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
            ]);

            $name = $request->input('name');
            $address = $request->input('address');
            $email = $request->input('email');

            $user = new UserList;
            $user->name = $name;
            $user->address = $address;
            $user->email = $email;
            $user->save();

            $userlist = UserList::get();

            DB::commit(); // data tidak akan di insert sebelum di commit
            return response()->json($userlist, 200);
        }
        catch(\Exception $e){

            DB::rollback(); //untuk rollback data ketika data yang dimasukkan mengalami kegagalan
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    function deleteData(Request $request){
        DB::beginTransaction();
        try{
            $this->validate($request, [
                'id' => 'required',
            ]);

            $id = $request->input('id');
            $user = Userlist::find($id);

            if(empty($user)){
                return response()->json(["message" => "User not Found"], 
                404);
            }

            $user->delete();

            $userlist = UserList::get();

            DB::commit();
            return response()->json($userlist, 200);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }

        


    }
}
