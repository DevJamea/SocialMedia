<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'user_id' => 'required',
            'post_id' => 'required',
        ] , [
            'user_id' => 'اي دي المستخدم',
            'post_id' => 'اي دي البوست',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $like = new Like();
        $like->user_id = $request->user_id;
        $like->post_id = $request->post_id;
        $like->save();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Like::where('id', $id)->delete();
        return response()->json(['msg' , 'تم الحذف بنجاح']);
    }
}
