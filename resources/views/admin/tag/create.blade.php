@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'Tambah Tag')
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
    <form action="{{ route('tag.store') }}" method="POST">
        <!-- kita wajib menambahkan form csrf jika akan menambahkan form untuk keamanan-->
        <!-- csrf itu adalah form hidden yang berbentuk token -->
        @csrf
        <div class="form-group">
            <label>Tag</label>
            <input type="text" class="form-control" name="name">
        </div>

        <div class="form-group">
            <button class="btn btn-primary">Simpan Tag</button>
        </div>
    </form>

@endsection
