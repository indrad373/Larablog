@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'Edit Kategori')
@section('content')

    <!-- actionnya bakal pake method store dr CategoryController -->
    <form action="{{ route('category.update', $category->id) }}" method="POST">
        <!-- kita wajib menambahkan form csrf jika akan menambahkan form untuk keamanan-->
        <!-- csrf itu adalah form hidden yang berbentuk token -->
        @csrf
        @method('patch') <!-- method patch ini merupakan sebuah inputan juga, boleh patch boleh put -->
        <div class="form-group">
            <label>Kategori</label>
            <input type="text" class="form-control" name="name" value="{{ $category->name }}">
        </div>

        <div class="form-group">
            <button class="btn btn-primary">Update Kategori</button>
        </div>
    </form>

@endsection
