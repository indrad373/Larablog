@extends('template_blog.content')
@section('isi')
<!-- post -->
<div class="col-md-8 hot-post-left">
    @foreach($data as $list_post)
<div class="post post-row">
    <a class="post-img" href="{{ route('blog.isi', $list_post->slug) }}"><img src="{{ asset($list_post->gambar) }}" alt="{{ $list_post->judul }}"></a>
    <div class="post-body">
        <div class="post-category">
            <a href="#">{{ $list_post->category->name }}</a>
        </div>
        <h3 class="post-title"><a href="{{ route('blog.isi', $list_post->slug) }}">{{ $list_post->judul }}</a></h3>
        <ul class="post-meta">
            <li><a href="#">{{ $list_post->users->name }}</a></li>
            <li>{{ $list_post->created_at }}</li>
        </ul>
        <p>{!! mb_strimwidth($list_post->konten,0,160) !!} ...</p>
    </div>
</div>
@endforeach
<!-- /post -->
<center>{{ $data->links() }}</center>
</div>

<!-- /post -->
@endsection
