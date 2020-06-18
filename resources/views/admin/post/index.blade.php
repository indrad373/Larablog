@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'Post')
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
    @if(Session::has('success')) <!-- jika si Session punya success sama seperti yang dicontroller maka kita akan tampilkan -->
    <div class="alert alert-success" role="alert">
        {{ Session('success') }}
    </div>
    @endif

    <a href="{{ route('post.create') }}" class="btn btn-info">Tambah Post</a>
    <br><br>

    <!-- table-sm membuat table yang ukuran height dan widthnya lebih kecil-->
    <table class="table table-striped table-hover table-sm table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama Post</th>
            <th>Kategori</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($post as $result => $hasil)
            <tr>
                <td>{{ $result + $post->firstitem() }}</td>
                <td>{{ $hasil->judul }}</td>
                <td>{{ $hasil->category->name }}</td> <!-- $hasil->category->nama_field_ditable_category -->
                <td>
                    <!-- Kenapa pake form disini ? karena kita akan langsung request ke database makanya pake form -->
                    <form action="{{ route('post.destroy', $hasil->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <!-- btn-sm membuat button yang ukurannya lebih kecil-->
                        <a href="{{ route('post.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- add pagination from controller -->
    {{ $post->links() }}
@endsection
