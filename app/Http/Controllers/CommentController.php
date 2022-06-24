<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
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
            'comment' => 'required',
            'user_id' => 'required',
            'post_id' => 'required',
        ] , [
            'comment' => 'التعليق',
            'user_id' => 'اي دي المستخدم',
            'post_id' => 'اي دي البوست',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = $request->user_id;
        $comment->post_id = $request->post_id;
        $comment->save();
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
        $validated = validator::make($request->all() , [
            'comment' => 'required',
        ] , [
            'comment' => 'التعليق',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $comment = Comment::Find($id);
        $comment->comment = $request->comment;
        $comment->save();
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
        Comment::where('id', $id)->delete();
        return response()->json(['msg' , 'تم الحذف بنجاح']);
    }
}
