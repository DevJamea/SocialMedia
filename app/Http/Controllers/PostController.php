<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id' , 'ASC')->paginate(5);
        return response()->json($posts);
    }

    public function search(Request $request){
        $posts = Post::when($request->text , function ($q) use ($request){
            $q->where('text' , 'like' , '%'.$request->text.'%');
        })->paginate(5);
        return response()->json($posts);
    }

    public function getUserPosts($id){
        $posts = Post::where("user_id" , '=' , $id)->paginate(5);
        return response()->json($posts);
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
            'text' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
            'user_id' => 'required',
        ] , [
            'text' => 'النص',
            'image' => 'الصورة',
            'user_id' => 'اي دي المستخدم',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $post = new Post();
        $post->text = $request->text;
        $post->user_id = $request->user_id;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $image_name = time().'.'.$file->getClientOriginalExtension();
            $path = 'images'.'/'.$image_name;
            $file->move(public_path('images'), $image_name);
            $post->image = $path;
        }
        $post->save();
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
        $post_data = Post::with('likes' , 'comments')
            ->withCount('likes' , 'comments')->find($id);
        $post_likes = $post_data->likes;
        $post_comments = $post_data->comments;

        $post_comments = $post_comments->map(function ($post){
            return[
                'name'=>User::find($post->user_id)->name,
                'comment'=>$post->comment
            ];
        });

        $likes = [];

        foreach ($post_likes as $like){
            array_push($likes ,User::find($like->user_id)->name);
        }

        $data =collect([
            'Post'=>$post_data->text,
            'Likes Count'=>$post_data->likes_count,
            'Post Likes By' => $likes,
            'Comments Count'=>$post_data->comments_count,
            'Post Comments'=>$post_comments,
        ]);

        return response()->json($data);

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
            'text' => 'required',
            'image' => 'sometimes|mimes:jpeg,jpg,png,gif|max:10000',
        ] , [
            'text' => 'النص',
            'image' => 'الصورة',
        ]);

        if ($validated->fails()){
            $msg = "تأكد من البيانات المدخلة";
            $date = $validated->errors();
            return response()->json(compact('msg' , 'date') , 422);
        }

        $post = Post::Find($id);
        $post->text = $request->text;
        if ($request->hasFile('image')){
            $file = $request->file('image');
            $image_name = time().'.'.$file->getClientOriginalExtension();
            $path = 'images'.'/'.$image_name;
            $file->move(public_path('images'), $image_name);
            $post->image = $path;
        }
        $post->save();
        return response()->json(['msg' => 'تم التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::where('id', $id)->delete();
        return response()->json(['msg' , 'تم الحذف بنجاح']);
    }
}
