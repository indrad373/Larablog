<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{

    public function index()
    {
        //kita tambah sebuah variabel yang akan memanggil semua data dari category model
        //$tag = Category::all();

        $tag = Tag::paginate(10);

        //return sebuah view yang merujuk pada sebuah folder /admin/tag/index.blade.php
        return view('admin.tag.index', compact('tag'));
    }


    public function create()
    {
        //buat return
        return view('admin.tag.create');
    }


    public function store(Request $request)
    {
        //tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
        $this->validate($request, [
            'name' => 'required|max:20|min:4'
        ]);

        //dd($request->all());
        //disini kita akan memasukan data di model tag yang tabelnya kita panggil dr model tag
        //lakukan penyimpanan data kedalam tabel, buat sebuah var
        $tag = Tag::create([
            'name' => $request->name,
            //value slug nya kita ambil dr name yang kita udah tulis
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success','Tag berhasil disimpan');
        //tambah with untuk mengeluarkan flash data berhasil disimpan
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //dapatkan dayanya dulu
        $tag = Tag::findorfail($id);
        return view('admin.tag.edit', compact('tag'));
    }


    public function update(Request $request, $id)
    {
        //validasi dulu
        $this->validate($request, [
            //tulis apa sih request kita
            'name' => 'required',
        ]);

        //buat sebuah array lagi
        $tag_data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ];

        //buat pencarian id, panggil modelnya
        Tag::whereId($id)->update($tag_data);

        //return dan redirect ke sebuah route
        return redirect()->route('tag.index')->with('success', 'Tag Berhasil di Update');
    }


    public function destroy($id)
    {
        //buat var, suruh cari id nya
        $tag = Tag::findorfail($id);
        //kalo ketemu kita tingal suruh delete
        $tag->delete();

        //return dan redirect back
        return redirect()->back()->with('success', 'Tag Berhasil dihapus');
    }
}
