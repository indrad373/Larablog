<?php

namespace App\Http\Controllers;

use App\Posts;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Posts $posts){
        //buat sebuah var data yg menampung post
        $data = $posts->orderBy('created_at', 'desc')->get();
        return view('blog', compact('data'));
    }

    public function isi_blog($slug){
        $data = Posts::where('slug', $slug)->get();
        return view('blog.isi_post', compact('data'));
    }
}
