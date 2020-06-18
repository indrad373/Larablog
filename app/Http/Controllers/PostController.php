<?php

namespace App\Http\Controllers;

use App\Category;
use App\Posts;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //kita tambah sebuah variabel yang akan memanggil semua data dari category model
        //$tag = Posts::all();

        $post = Posts::paginate(10);

        //return sebuah view yang merujuk pada sebuah folder /admin/post/index.blade.php
        return view('admin.post.index', compact('post'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //buat var untuk mengambil data dari model Category
        $category = Category::all();
        return view('admin.post.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
        $this->validate($request, [
            'judul' => 'required',
            'category_id' => 'required',
            'konten' => 'required',
            'gambar' => 'required'
        ]);

        //kalo gambar kan data yg kita upload terpisah yg 1 diproject directory kita yg 1 itu di path yg ada di field table kita
        //jadi hrs gini
        $gambar = $request->gambar;
        //buat nama gambar jadi unique
        $new_gambar = time().$gambar->getClientOriginalName();

        $post = Posts::create([
            'judul' => $request->judul,
            'category_id' => $request->category_id,
            'konten' => $request->konten,
            'gambar' => 'public/uploads/posts/'.$new_gambar
        ]);

        $gambar->move('public/uploads/posts/', $new_gambar);

        //jika sudah berhasil kita redirect
        return redirect()->back()->with('success', 'Postingan anda berhasil disimpan');
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
        //
    }
}
