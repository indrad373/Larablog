@extends('template_backend.home')
<!-- import section sub-judul -->
@section('sub-judul', 'Edit Post')
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
    <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        <!-- kita wajib menambahkan form csrf jika akan menambahkan form untuk keamanan-->
        <!-- csrf itu adalah form hidden yang berbentuk token -->
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label>Judul</label>
            <input type="text" class="form-control" name="judul" value="{{ $post->judul }}">
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select class="form-control" name="category_id">
                <option value="" holder>Pilih Kategori</option>
                @foreach($category as $result)
                    <option
                        value=" {{ $result->id }} "
                        @if($post->category_id == $result->id)
                            selected
                        @endif> {{ $result->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Pilih Tags</label>
            <select class="form-control select2" multiple="" name="tags[]">
                @foreach($tags as $tag)
                    <option
                        value="{{ $tag->id }}"
                        @foreach($post->tags as $value)
                            @if($tag->id == $value->id)
                                selected
                            @endif
                        @endforeach>{{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Konten</label>
            <textarea class="form-control" name="konten" id="konten">{{ $post->konten }}</textarea>
        </div>
        <div class="form-group">
            <label>Thumbnail</label>
            <input type="file" class="form-control" name="gambar" >
        </div>
        <div class="form-group">
            <button class="btn btn-primary">Update Post</button>
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'konten' );
    </script>
@endsection
