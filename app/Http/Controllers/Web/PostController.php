<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\SendPostEmail;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('emails\index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required|min:6',
            'body'=> 'required|min:6',
        ]);
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();
        //var_dump($post->id);//int(2)
        //exit;
        $this->dispatch(new SendPostEmail($post)); // 分发队列
        return redirect()->back()->with('status', 'Your post has been submitted successfully');
    }
}
