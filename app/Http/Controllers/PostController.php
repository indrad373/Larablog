<?php

namespace App\Http\Controllers;

use App\Category;
use App\Posts;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        //buat var unutk mengambil data dari model tag
        $tags = Tag::all();
        //buat var untuk mengambil data dari model Category
        $category = Category::all();
        return view('admin.post.create', compact('category', 'tags'));
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
            'gambar' => 'required',
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
            'gambar' => 'public/uploads/posts/'.$new_gambar,
            'slug' => Str::slug($request->judul)
        ]);

        //attach tagnya buat penyimpanan muliple
        $post->tags()->attach($request->tags);

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
        $category = Category::all();
        $tags = Tag::all();
        $post = Posts::findorfail($id);
        return view('admin.post.edit', compact('post','tags', 'category'));
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
        ///tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
        $this->validate($request, [
            'judul' => 'required',
            'category_id' => 'required',
            'konten' => 'required',
        ]);

        $post = Posts::findorfail($id);

        if ($request->has('gambar')){
            $gambar = $request->gambar;
            //buat nama gambar jadi unique
            $new_gambar = time().$gambar->getClientOriginalName();
            $gambar->move('public/uploads/posts/', $new_gambar);

            $post_data = [
                'judul' => $request->judul,
                'category_id' => $request->category_id,
                'konten' => $request->konten,
                'gambar' => 'public/uploads/posts/'.$new_gambar,
                'slug' => Str::slug($request->judul)
            ];
        }
        else {
            $post_data = [
                'judul' => $request->judul,
                'category_id' => $request->category_id,
                'konten' => $request->konten,
                'slug' => Str::slug($request->judul)
            ];
        }



        //sinc datanya untuk update data
        $post->tags()->sync($request->tags);
        $post->update($post_data);

        //jika sudah berhasil kita redirect
        return redirect()->route('post.index')->with('success', 'Postingan anda berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Posts::findorfail($id);
        $post->delete();

        return redirect()->back()->with('success','Post berhasil dihapus ke trash');
    }

    public function tampil_trash(){
        //mengambil hanya data2 yang sudah softdelete saja
        $post = Posts::onlyTrashed()->paginate(10);
        return view('admin.post.trash', compact('post'));
        //jangan lupa buat route scr manual di web.php
    }

    public function restore($id){
        //cari post yang ada di trash
        $post = Posts::withTrashed()->where('id', $id)->first();
        $post->restore();

        return redirect()->back()->with('success','Post berhasil direstore, silahkan cek list post');
    }

    public function permanent_delete($id){
        //cari post yang ada ditrash
        $post = Posts::withTrashed()->where('id', $id)->first();
        $post->forceDelete();

        return redirect()->back()->with('success', 'Post berhasil dihapus secara permanen');
    }
}
