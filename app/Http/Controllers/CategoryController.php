<?php

namespace App\Http\Controllers;

//import model category
use App\Category;

use Illuminate\Http\Request;
//import str helper
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //kita tambah sebuah variabel yang akan memanggil semua data dari category model
        //$category = Category::all();

        //pake Category::paginate(); untuk menampilkan data perhalaman
        $category = Category::paginate(10);

        //untuk memanggil data tambahkan compact()
        return view('admin.category.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //buat return
        return view('admin.category.create');
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
            'name' => 'required|min:4'
        ]);


        //dd($request->all());
        //disini kita akan memasukan data di model category yang tabelnya kita panggil dr model kategory
        //lakukan penyimpanan data kedalam tabel, buat sebuah var
        $category = Category::create([
            'name' => $request->name,
            //value slug nya kita ambil dr name yang kita udah tulis
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success','Kategori berhasil disimpan');
        //tambah with untuk mengeluarkan flash data berhasil disimpan
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
    public function edit($id) //disini ada sebuah id yang kita dapat dr url yg udh didef di indexnya
    {
        //dapatkan dayanya dulu
        $category = Category::findorfail($id);
        return view('admin.category.edit', compact('category'));
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
        //validasi dulu
        $this->validate($request, [
            //tulis apa sih request kita
            'name' => 'required',
        ]);

        //buat sebuah array lagi
        $category_data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ];

        //buat pencarian id, panggil modelnya
        Category::whereId($id)->update($category_data);

        //return dan redirect ke sebuah route
        return redirect()->route('category.index')->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //buat var, suruh cari id nya
        $category = Category::findorfail($id);
        //kalo ketemu kita tingal suruh delete
        $category->delete();

        //return dan redirect back
        return redirect()->back()->with('success', 'Data Berhasil dihapus');
    }
}
