<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //kita akan berelasi dengan table user
        //kita buat sebuah var untuk menampung data user yang ada didatabase
        $user = User::paginate(10);
        return view('admin.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //kita validasi dulu datanya
        $this->validate($request, [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email',
            'tipe'=> 'required',
        ]);

        //jika ketika proses pembuatan user field password tidak diisi baik sengaja atau tidak maka akan mengeluarkan password default
        //jika kita input password maka kita akan store password yang telah dibuat oleh kita
        if ($request->input('password')){
            $password = bcrypt($request->password);
        } else {
            //jika tidak maka akan store default password, yaitu 1234
            $password = bcrypt('1234');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'tipe' => $request->tipe,
            //ambil data dari var password
            'password' => $password
        ]);

        return redirect()->back()->with('success', 'User Berhasil Disimpan');
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
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));

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
        //kita validasi dulu datanya
        $this->validate($request, [
            'name' => 'required|min:3|max:100',
            'tipe'=> 'required',
        ]);

        //jika password nya kita input berarti passwordnya kita ubah
        if($request->input('password')){
            //buat var untuk menampung data
            $user_data = [
                'name' => $request->name,
                'tipe' => $request->tipe,
                'password' => bcrypt($request->password),
            ];
        } else { //kalo kita ga input password ya berarti ga diubah diemin aja
            //buat var untuk menampung data
            $user_data = [
                'name' => $request->name,
                'tipe' => $request->tipe,
            ];
        }

        $user = User::find($id);
        $user->update($user_data);

        return redirect()->route('user.index')->with('success', 'Data User Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back()->with('success', 'Data User Berhasil Dihapus');
    }
}
