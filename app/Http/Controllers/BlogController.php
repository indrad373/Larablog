<?php

namespace App\Http\Controllers;

use App\Category;
use App\Posts;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Posts $posts){
        //buat menampung category
        $category = Category::all();
        //buat sebuah var data yg menampung post
        $data = $posts->latest()->take(8)->get();
        return view('blog', compact('data','category'));
    }

    public function isi_blog($slug){
        //buat menampung category
        $category = Category::all();
        $data = Posts::where('slug', $slug)->get();
        return view('blog.isi_post', compact('data','category'));
    }

    public function list_blog(){
        //buat menampung category
        $category = Category::all();
        $data = Posts::latest()->paginate(6);
        return view('blog.list_post', compact('data','category'));
    }
}
