@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'User')
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

    <a href="{{ route('user.create') }}" class="btn btn-info">Tambah User</a>
    <br><br>

    <!-- table-sm membuat table yang ukuran height dan widthnya lebih kecil-->
    <table class="table table-striped table-hover table-sm table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Email</th>
            <th>Role Type</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($user as $result => $hasil)
            <tr>
                <td>{{ $result + $user->firstitem() }}</td>
                <td>{{ $hasil->name }}</td>
                <td>{{ $hasil->email }}</td>
                <td>
                    <!-- disini kalo tipenya 1 berarti yang muncul kata admin kalo 0 yang muncul kata writer -->
                    @if ($hasil->tipe)
                        <span class="badge badge-info">Admin</span>
                        @else
                        <span class="badge badge-warning">Writer</span>
                    @endif
                </td>
                <td>
                    <!-- Kenapa pake form disini ? karena kita akan langsung request ke database makanya pake form -->
                    <form action="{{ route('user.destroy', $hasil->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <!-- btn-sm membuat button yang ukurannya lebih kecil-->
                        <a href="{{ route('user.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <!-- add pagination from controller -->
    {{ $user->links() }}
@endsection
