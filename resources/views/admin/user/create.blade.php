@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'Tambah User')
@section('content')

    <!-- check jika ada data pada form yang kosong maka akan mengeluarkan message error -->
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif

    <!-- check jika data pada form berhasil dimasukan kedalam db maka akan mengeluarkan flash success -->
    @if(Session::has('success')) <!-- jika si Session punya success sama seperti yang dikontroller maka kita akan tampilkan -->
    <div class="alert alert-success" role="alert">
        {{ Session('success') }}
    </div>
    @endif

    <!-- actionnya bakal pake method store dr CategoryController -->
    <form action="{{ route('user.store') }}" method="POST">
        <!-- kita wajib menambahkan form csrf jika akan menambahkan form untuk keamanan-->
        <!-- csrf itu adalah form hidden yang berbentuk token -->
        @csrf
        <div class="form-group">
            <label>Nama User</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="form-group">
            <label>Role User</label>
            <select class="form-control" name="tipe">
                <option value="" holder>Pilih Role User</option>
                <option value="1" holder>Admin</option>
                <option value="0" holder>Writer</option>
            </select>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" class="form-control" name="password">
        </div>

        <div class="form-group">
            <button class="btn btn-primary">Simpan User</button>
        </div>
    </form>

@endsection
