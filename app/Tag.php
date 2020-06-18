<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //kita harus menentukan field mana saja yang boleh dimasukan dalam database
    //slug itu untuk membuat tampilan url yang kita buat itu mudah dibaca
    protected $fillable = ['name', 'slug'];
}
