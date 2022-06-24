<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id' , 'ASC')->paginate(5);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = validator::make($request->all() , [
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ] , [
            'name' => 'الأسم',
            'email' => 'الإيميل',
            'password' => 'كلمة المرور',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->save();
        return response()->json(['msg' => 'تمت الاضافة بنجاح']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user =User::Find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = validator::make($request->all() , [
            'name' => 'required|min:5',
            'email' => 'required|unique:users,email,'.$id,
            'password' => 'sometimes|min:6',
        ] , [
            'name' => 'الأسم',
            'email' => 'الإيميل',
            'password' => 'كلمة المرور',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $user =User::Find($id);
        $user->email = $request->email;
        if ($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->save();
        return response()->json(['msg' => 'تمت التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['msg' , 'تم الحذف بنجاح']);
    }
}
