<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posts extends Model
{
    //buat softdelete
    use SoftDeletes;

    //buat fillablenya
    protected $fillable = [
        'judul',
        'category_id',
        'konten',
        'gambar',
        'slug',
    ];

    //buat eloquent relationship dengan model category
    public function category(){
        return $this->belongsTo('App\Category');
    }

    //buat eloquent many to many ke model tag nya
    public function tags(){
        return $this->belongsToMany('App\Tag');
    }
}
