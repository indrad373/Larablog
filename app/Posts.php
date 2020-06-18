<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    //buat fillablenya
    protected $fillable = [
        'judul',
        'category_id',
        'konten',
        'gambar',
    ];

    //buat eloquent relationship dengan model category
    public function category(){
        return $this->belongsTo('App\Category');
    }
}
