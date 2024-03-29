- buat database baru di phpmyadmin

- konfig .env file sambungkan dengan database

- php artisan make:migration create (nama tabel yang mau dibuat) ~ create_category_table

- php artisan migrate (untuk membuat tabel di mysql)

- php artisan make:model Category (untuk membuat model kategori di project laravel kita)

- php artisan make:controller CategoryController --resource (untuk membuat controller kategori di project laravel kita, --resource dipakai untuk mempermudah kita membuat method crud, jadi udh otomatis dibuat sama laravel gausah dibuat 1" lagi kaya method create nya atau read, update, delete)

- tambahkan return view('admin.category.index'); pada function index di kontroler kategori ~ berarti filenya ada pada /view/admin/category/index.blade.php)

- tambahkan Route::resource('/category','CategoryController');

- pada terminal ketik php artisan route:list

- pada CategoryController import model Category dengan menuliskan, use App\Category;

- pada model Category definisikn nama tabel yang kita punya di mysql dengan menuliskan protected $table = 'category';

- pada CategoryController, kita tambah sebuah variabel yang akan memanggil semua data dari category model, $category = Category::all(); , lalu     untuk memanggil data tambahkan compact() hasilnya menjadi, return view('admin.category.index', compact('category'));

- kemudian untuk menampilkan datanya, pada file /admin/category/index.blade.php tambahkan :
    
    
            @foreach($category as $result)
                <ul>
                    <li>
                        {{ $result->name }}
                    </li>
                </ul>
            @endforeach
    
  maka data name akan ditampilkan

- Edit /admin/category/index.blade.php

- Pada CategoryController ubah 

    `$category = Category::all();` 
    
    menjadi 
    
    `$category = Category::paginate(*isi nomor pagination*);`

- Lalu pada /admin/category/index.blade.php, link pagination yang diambil dari controller {{ $category->links() }}

- Pada /view/template_backend/home.blade.php, buat @yield agar judulnya dapat dipakai ditiap page, dan tidak sama judulnya (co, category, article, dll) dengan @yield('sub-judul')

- Lalu pada /admin/category/index.blade.php, import section sub-judul dibagian atas dengan @section('sub-judul', 'Kategori')

- Lalu pada /admin/category/index.blade.php, tambah dibagian atas : 


            <a href="{{ route('category.create') }}" class="btn btn-info">Tambah Kategori</a>
            <br><br>
            `<!-- category.create itu routenya -->`
    

- Lalu pada /admin/category/ buat file baru create.blade.php samakan isi import nya dengan index.blade.php, ganti sub-judulnya lalu tambahkan form

- Pada CategoryController kita akan memasukan / menyimpan data yang tabel nya kita panggil dari model kategori, sebelumnya import str helper use Illuminate\Support\Str;

	
            $category = Category::create([
                    'name' => $request->name,
                    'slug' => Str::slug($request->name),
                ]);
                
                return redirect()->back();
        
        
- Pada model Category kita harus menentukan field mana saja yang boleh dimasukan dalam database : 


    `protected $fillable = ['name', 'slug'];`
    

- Edit template sidebar menjadi kategori dan <li> nya menjadi List Kategori : 
	
	
	`<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Kategori</span></a>
        <ul class="dropdown-menu">
        <li><a class="nav-link" href={{ route('category.index') }}>List Kategori</a></li>`


-------------------------------------------- VALIDATION AND FLASH ------------------------------------------------------------------

- Pada CategoryController, tambah validasi ketika menginputkkan data jd jika data yang diinputkan kosong akan muncul validasi bahwa data tidak boleh kosong :

	
            $this->validate($request, [
                'name' => 'required|min:4', 
                //minimal ada 4 hruf yg kita inputkan
            ]);
	
- pada admin/category/create.blade.php dibawah section, buat sebuah kondisi jika errornya ada maka akan mengeluarkan message error
    
    
        `@if(count($errors) > 0)
            @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
            @endforeach
        @endif`
    	
- Pada CategoryController, tambah with untuk mengeluarkan flash data berhasil disimpan :
	
	
	`return redirect()->back()->with('success','Kategori berhasil disimpan');`
	
- pada admin/category/create.blade.php, buat sebuah kondisi jika data berhasil dimasukan maka akan mengeluarkan flash berhasil	
    
    
        `@if(Session::has('success')) <!-- jika si Session punya success sama seperti yang dikontroller maka kita akan tampilkan -->
            <div class="alert alert-danger" role="alert">
                {{ Session('success') }}
            </div>
        @endif`
    	
----------------------------------------------EDIT AND UPDATE DATA---------------------------------------------------------------------------
	
- pada admin/category/create.blade.php, tambahkan href route pada button edit :
	
	`<a href="{{ route('category.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>`
	
- pada CategoryController bagian edit, tambah :
	
	
	    //dapatkan dayanya dulu
        $category = Category::findorfail($id);
        return view('admin.category.edit', compact('category'));
	
- Buat file edit.blade.php pada /admin/category/ lalu copy semua yang ada di create.blade.php ke edit.blade.php dan ganti sub-judul

- Lalu tambahkan value yang kita dapat dr kategori yang kita kirim kan td melalui function edit :
        
        
        <input type="text" class="form-control" name="name" value="{{ $category->name }}">

- Ubah route('category.store') menjadi route('category.update', $category->id)

- Lalu dibawah @csrf tambahkan @method('patch')
	
- Pada CategoryController, dibagian function update :


	    <!-- validasi dulu -->
        $this->validate($request, [
            //tulis apa sih request kita
            'name' => 'required',
        ]);
        
        //buat sebuah array lagi
        $category_data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ];
        
        //buat pencarian id, panggil modelnya
        Category::whereId($id)->update($category_data);
        
        //return dan redirect ke sebuah route
        return redirect()->route('category.index')->with('success', 'Data Berhasil di Update');
        
---------------------------------------------------------DELETE DATA KATEGORI----------------------------------------------------------

- Ubah anchor (<a>) delete pada admin/category/index.blade.php menjadi <button>, lalu tambahkan <form></form> :


	    <!-- Kenapa pake form disini ? karena kita akan langsung request ke database makanya pake form, kalo yg edit ga pake form karena cuma menampilkan, meskipun sama2 request langusng ke database sih -->
        <form action="{{ route('category.destroy', $hasil->id) }}" method="POST">
        	@csrf
        	@method('delete')
        	<button href="" class="btn btn-danger btn-sm">Delete</button>
        </form>
        
- Lalu ubah posisi edit agar dimasukan juga kedalam tag form :), btw form actionnya ga akan ngaruh apa2 ke yg editnya kok


	    <form action="{{ route('category.destroy', $hasil->id) }}" method="POST">
                @csrf
                @method('delete')
                <!-- btn-sm membuat button yang ukurannya lebih kecil-->
                <a href="{{ route('category.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
        </form>

       
- Pada CategoryController, pada function destroy :
	
	
	    //buat var, suruh cari id nya
        $category = Category::findorfail($id);
        
        //kalo ketemu kita tingal suruh delete
        $category->delete();

        //return dan redirect back
        return redirect()->back()->with('success', 'Data Berhasil dihapus');
        
        
---------------------------------------------------------- CRUD TAGS ------------------------------------------------------------------
- Pada template backend sidebar.blade.php, copy menu dropdown kategori lalu ubah menjadi tag :
    
    
        `<li class="dropdown">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Tag</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href={{ route('category.index') }}>List Tag</a></li>
            </ul>
        </li>`
    
- Kemudian buat model untuk tag pada terminal ketikan :
    
    
    `php artisan make:model Tags -m`
    -m berarti kita buat model sekaligus dimigrasi

- Lalu pada file tags migrationnya pada function up table yang akan dibuat, kita tambahkan :
    
    
        public function up()
            {
                Schema::create('tags', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('slug');
                    $table->timestamps();
                });
            }

- Pada terminal ketikan, php artisan migrate untuk memigrasi table tags yang kita buat di laravel ke mysql database :


    `php artisan migrate`
    
- Lalu tambahkan data secara manual pada database table tags, lalu kita memperoleh data yang telah secara manual kita tambahkan pada tabel tags

- Buat controller untuk mengeluarkan data tags yang ada di database :

    
    `php artisan make:controller TagController --resource`

- Pada web.php tambahkan route baru untuk mengakses controllernya :

    
    `Route::resource('/tag', 'TagController');`
    
- Jalankan command pada terminal, untuk melihat route list yang dapat digunakan :

    
    `php artisan route:list`    
    
- Untuk menampilkan data kita perlu route tag.index, pergi ke sidebar.blade.php lalu ubah :

    
    `<li><a class="nav-link" href={{ route('category.index') }}>List Kategori</a></li>`

    menjadi
    
    `<li><a class="nav-link" href={{ route('tag.index') }}>List Kategori</a></li>`


- Pada TagController dibagian index ketikan :

    
            //return sebuah view yang merujuk pada sebuah folder /admin/tag/index.blade.php
            return view('admin.tag.index');
    
    
- Buat sebuah file baru di folder /admin -> index

- Copy semua dr folder /admin/category/index.blade.php ke /admin/post/index.blade.php, lalu ubah sedikit dr category ke tag

- Pada folder TagController bagian index, tambahkan variabel tag (sama kaya category) :

    
        public function index()
            {
                //kita tambah sebuah variabel yang akan memanggil semua data dari category model
                //$tag = Posts::all();
        
                $tag = Posts::paginate(10);
        
                //return sebuah view yang merujuk pada sebuah folder /admin/post/index.blade.php
                return view('admin.post.index', compact('tag'));
            }
    
    
- Pada file TagController bagian create (sama kaya category) :
    
    
            //buat return
            return view('admin.tag.create');
    
    
- Buat file create.blade.php pada /admin/tag

- Copy semua yang ada di /admin/category/create.blade.php ke /admin/tag/create.blade.php, lalu ubah2 dikit kata kategori jadi tag

- Pada file TagController bagian create (sama kaya category) :


            //tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
            $this->validate($request, [
                'name' => 'required|max:20|min:4'
            ]);
            
            //disini kita akan memasukan data di model tag yang tabelnya kita panggil dr model tag
            //lakukan penyimpanan data kedalam tabel, buat sebuah var
            $tag = Tag::create([
                'name' => $request->name,
                //value slug nya kita ambil dr name yang kita udah tulis
                'slug' => Str::slug($request->name),
            ]);
            
            return redirect()->back()->with('success','Tag berhasil disimpan');
            //tambah with untuk mengeluarkan flash data berhasil disimpan


- Pada Tag model tambahkan : 

    
    `protected $fillable = ['name', 'slug'];`
    

- Pada TagController bagian edit tambahkan :


            //dapatkan dayanya dulu
            $tag = Tag::findorfail($id);
            return view('admin.tag.edit', compact('tag'));
    
- Pada admin/tag/ buat file edit.blade.php lalu copy file edit yang ada di folder category, lalu ubah kata kategori menjadi tag

- Pada TagController bagian update tambahkan :


            //validasi dulu
            $this->validate($request, [
                //tulis apa sih request kita
                'name' => 'required',
            ]);
            
            //buat sebuah array lagi
            $tag_data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
            ];
            
            //buat pencarian id, panggil modelnya
            Tag::whereId($id)->update($tag_data);
            
            //return dan redirect ke sebuah route
            return redirect()->route('tag.index')->with('success', 'Tag Berhasil di Update');


- Pada TagController bagian destroy tambah :

    
            //buat var, suruh cari id nya
            $tag = Tag::findorfail($id);
            //kalo ketemu kita tingal suruh delete
            $tag->delete();
            
            //return dan redirect back
            return redirect()->back()->with('success', 'Data Berhasil dihapus');
    
----------------------------------------------- CRUD POST ----------------------------------------------

- Buat model post baru ketikan diterminal :
    
    
    `php artisan make:model Posts -m`

- Pada migration posts table tambahkan :


        public function up()
            {
                Schema::create('posts', function (Blueprint $table) {
                    $table->id();
                    $table->string('judul');
                    $table->integer('category_id');
                    $table->text('content');
                    $table->string('gambar');
                    $table->timestamps();
                });
            }

- Pada terminal ketikan php artisan migrate :
    
        
     `php artisan migrate`
     
- Pada template backend sidebar.blade.php, copy menu dropdown kategori lalu ubah menjadi post :
      
      
      <form>
      <li class="dropdown">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Post</span></a>
          <ul class="dropdown-menu">
              <li><a class="nav-link" href={{ route('post.index') }}>List Post</a></li>
          </ul>
      </li>
      </form>

- Buat controller untuk mengeluarkan data post yang ada di database :

    
    `php artisan make:controller PostController --resource`

- Pada web.php tambahkan route baru untuk mengakses controllernya :

    
    `Route::resource('/tag', 'PostController');`
    
- Jalankan command pada terminal, untuk melihat route list yang dapat digunakan :

    
    `php artisan route:list`  

- Pada TagController dibagian index ketikan :

    
        //return sebuah view yang merujuk pada sebuah folder /admin/tag/index.blade.php
        return view('admin.post.index');    
    
    
- Buat sebuah file baru di folder /admin
    
    
        //kita tambah sebuah variabel yang akan memanggil semua data dari category model
        //$tag = Posts::all();
        
        $post = Posts::paginate(10);
        
        //return sebuah view yang merujuk pada sebuah folder /admin/post/index.blade.php
        return view('admin.post.index', compact('post'));
            
- kemudian kita akan buat eloquent relationship, pada model Posts kita ketikan :


        public function category(){
                return $this->belongsTo('App\Category');
        }
        
- Lalu ada sedikit perubahan di /admin/post/index.blade.php nya, lihat sendiri males ngetik :)

- Pada file TagController bagian create (sama kaya tag) :
    
    
            //buat return
            return view('admin.post.create');
    
    
- Buat file create.blade.php pada /admin/post

- Lalu copy2 seperti biasa kemudian modifikasi

- Pada PostController tambah script :


            //buat var untuk mengambil data dari model Category
            $category = Category::all();
            return view('admin.post.create', compact('category'));
    
- Pada file TagController bagian create (sama kaya category) :


            //tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
            $this->validate($request, [
                'judul' => 'required',
                'category_id' => 'required',
                'konten' => 'required',
                'gambar' => 'required'
            ]);
            
            //kalo gambar kan data yg kita upload terpisah yg 1 diproject directory kita yg 1 itu di path yg ada di field table kita
            //jadi hrs gini
            $gambar = $request->gambar;
            //buat nama gambar jadi unique 
            $new_gambar = time().$gambar->getClientOriginalName();
            
            $post = Posts::create([
                'judul' => $request->judul,
                'category_id' => $request->category_id,
                'content' => $request->konten,
                'gambar' => 'public/uploads/posts/'.$new_gambar
            ]);
            
            $gambar->move('public/uploads/posts/', $new_gambar);
            
            //jika sudah berhasil kita redirect
            return redirect()->back()->with('success', 'Postingan anda berhasil disimpan');

- Pada Post model tambahkan : 
  
      
      //buat fillablenya
          protected $fillable = [
              'judul',
              'category_id',
              'konten',
              'gambar',
          ];

------------------------------------------ POST SLUG + TAMPIL THUMBNAIL ------------------------------------------

- Sekarang karna lupa naro slug di database kita bakal coba tambah slug pake migrate

    
        `php artisan make:migration add_new_slug_posts_table`
        
- di add_new_posts_table tambahkan string slug yg lainnya hapus :

    
        Schema::table('posts', function (Blueprint $table) {
                    $table->string('slug');
        });
        
- lalu diterminal ketik php artisan migrate untung memigrasinya, itu cara menambahkan field baru didalem database kita kalo misal lupa ditambah

- modif dikit diPostController, tambahin slug lalu di modelnya tambah juga slug dibagian fillable nya

- kemudian kita akan menampilkan gambar yang sudah dipost di database, buka /admin/post/index.blade.phpnya lalu ubah dikit

----------------------------------------------- TAGS MANY TO MANY ------------------------------------------------

- pada model Posts tambah function baru bernama tags pakai method belongsToMany, begitu pula pada model . Jadi post kita ini bisa dimiliki banyak tags dan tags bisa dimiliki oleh banyak post

- buat pivot title, sebelumnya buat dulu relationnya buat table sendiri khusus relation post dan tag, buka terminal :

     
     `php artisan make:migration create_post_tag_table`

- Pada migration yang sudah dibuat tambahkan :
    

        public function up(){
            Schema::create('post_tag', function (Blueprint $table) {
                $table->id();
                $table->integer('post_id');
                $table->integer('tag_id');
                $table->timestamps();
            });
        }
    
- Lalu pada terminal ketikan php artisan migrate, untuk memigrasi relasi table post_tag yang telah dibuat

- pada post index.blade.php kita tambahkan inputan berupa multiple, importkan js dan css file dari templatenya select2 js dan select2 css kemudian copykan

- Pada PostController dibagian create kita buat var baru untuk membuat pilihan tag di index.blade.php menjadi dinamis

    
        public function create(){
            //buat var unutk mengambil data dari model tag
            $tag = Tag::all();
            //buat var untuk mengambil data dari model Category
            $category = Category::all();
            return view('admin.post.create', compact('category', 'tag'));
        }
       
- Lakukan foreach di index.blade.php nya

- Sekarang kita ingin melakukan penyimpanan si select multiple itu td, pergi ke PostController lalu tambahkan :


        //attach tagnya buat penyimpanan muliple
        $post->tags()->attach($request->tags);
    
- Lalu dibagian post index.blade.php kita akan keluarkan tags nya

-------------------------------------------------- EDIT POST ---------------------------------------------------

- pada PostController bagian edit samain kaya edit2 sebelumnya :

    
        public function edit($id){
                $category = Category::all();
                $tags = Tag::all();
                $post = Posts::findorfail($id);
                return view('admin.post.edit', compact('post','tags','category'));
        }
    
- pada edit.blade.php ubah sedikit untuk mengeluarkan datanya

- pada PostController di bagian update :


            ///tambah validasi jika data ksong muncul pesan tidak boleh kosong (required)
            $this->validate($request, [
                'judul' => 'required',
                'category_id' => 'required',
                'konten' => 'required',
            ]);
    
            $post = Posts::findorfail($id);
    
            if ($request->has('gambar')){
                $gambar = $request->gambar;
                //buat nama gambar jadi unique
                $new_gambar = time().$gambar->getClientOriginalName();
                $gambar->move('public/uploads/posts/', $new_gambar);
    
                $post_data = [
                    'judul' => $request->judul,
                    'category_id' => $request->category_id,
                    'konten' => $request->konten,
                    'gambar' => 'public/uploads/posts/'.$new_gambar,
                    'slug' => Str::slug($request->judul)
                ];
            }
            else {
                $post_data = [
                    'judul' => $request->judul,
                    'category_id' => $request->category_id,
                    'konten' => $request->konten,
                    'slug' => Str::slug($request->judul)
                ];
            }
        
            //sinc datanya untuk update data
            $post->tags()->sync($request->tags);
            $post->update($post_data);
    
            //jika sudah berhasil kita redirect
            return redirect()->route('post.index')->with('success', 'Postingan anda berhasil diupdate'); 

-------------------------------- SOFT DELETE & RESTORE------------------------------

- Pergi ke model Posts tambahkan :

    
        `//buat softdelete`
        `use SoftDeletes;`

- Pergi ke PostController lalu dibagian destroy 


        public function destroy($id)
            {
                $post = Posts::findorfail($id);
                $post->delete();
                
                return redirect()->back()->with('success','Post berhasil dihapus ke trash');
            }
        
- buat suatu table migration pada terminal ketikan :

    `php artisan make:migration tambah_softdelete_ke_post`

- dalam table migration yang baru copy dari table migration post yg up :

     
         Schema::table('posts', function (Blueprint $table) {
                     //pake table bawaan softdelete dr laravel
                     $table->softDeletes();
                 });
             
- Pada terminal jalankan :

    `php artisan migrate`
    
    maka akan menambah field baru deleted_at
    
- Kita akan menampilkan data pada trash bin, buat sebuah function baru pada PostController :

    
        public function tampil_trash(){
            //mengambil hanya data2 yang sudah softdelete saja
            $post = Posts::onlyTrashed()->paginate(10);
            return view('admin.post.trash');
            //jangan lupa buat route scr manual di web.php
        }
    
- Buat route tampil_hapus secara manual di web.php, posisinya disimpan diatas karna posisi itu pengaruh

    `Route::get('/post/tampil_trash', 'PostController@tampil_trash')->name('post.tampil_trash');`

- Buat file pada /admin/post dengan nama trash.blade.php, lalu copy paste dr halaman index ganti dikit2 kata2nya

- Tambahkan juga route buat akses halaman trashed post nya di sidebar.blade.php :

    `route('post.tampil_trash')`

- Lalu buat function baru di PostController untuk restore datanya

    
        public function restore($id){
            //cari post yang ada di trash
            $post = Posts::withTrashed()->where('id', $id)->first();
            $post->restore();
            return redirect()->back()->with('success','Post berhasil direstore, silahkan cek list post');
        }
        
- Kemudian buat route secara manual untuk function restore ini di web.php :

    `//route manual restore
     Route::get('/post/restore/{id}', 'PostController@restore')->name('post.restore');`
     
- Pada trash.blade.php panggil route restorenya :

    `{{ route('post.restore', $hasil->id) }}`

- Kemudian kita akan membuat fungsi hapus data secara permanen, buat function baru di PostController :


        public function permanent_delete($id){
            //cari post yang ada ditrash
            $post = Posts::withTrashed()->where('id', $id)->first();
            $post->forceDelete();
    
            return redirect()->back()->with('success', 'Post berhasil dihapus secara permanen');
        }
        
- Pada web.php kita buat route untuk panggil permanent_deletenya :

    `//route manual permanent_delete
     Route::delete('/post/permanent_delete/{id}', 'PostController@permanent_delete')->name('post.permanent_delete');`

- Lalu pada trash.blade.php tambahkan action route ke post.permanent_delete :

    `{{ route('post.permanent_delete', $hasil->id) }}`
    
------------------------------------------- LOGIN REGISTER ----------------------------------------------

- Pada terminal ketikan :
    
     `composer require laravel/ui`
     
     `php artisan ui vue --auth`
    
  Nanti bakal ada menu pertanyaan apakah home.blade.php mau direplace pake yang baru ? nah jawab Yes
  
- Lalu ketik :

    `npm install && npm run dev`
    
  Untuk compile tampilan css dan jsnya
  
- Lalu extend template_backend.app ke views/home.blade.php :

    `@extends('layout.app')`
    
    ubah menjadi 
    
    `@extends('template_backend.home')`

- Copy script logout dari layout/app.blade.php :
            
            
            <a href="#" class="dropdown-item has-icon text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Logout
            </a>    

  menjadi

            <a class="dropdown-item has-icon text-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            
  Paste ke template backend kita template_backend/home.blade.php

- Lalu pada web.php bungkus route yang sudah kita buat kedalam route group middleware auth :


            Route::group(['middleware' => 'auth'], function (){
                Route::get('/home', 'HomeController@index')->name('home');
            
                //route manual tampil_hapus
                Route::get('/post/tampil_trash', 'PostController@tampil_trash')->name('post.tampil_trash');
                //route manual restore
                Route::get('/post/restore/{id}', 'PostController@restore')->name('post.restore');
                //route manual permanent_delete
                Route::delete('/post/permanent_delete/{id}', 'PostController@permanent_delete')->name('post.permanent_delete');
            
                Route::resource('/category','CategoryController');
                Route::resource('/tag', 'TagController');
                Route::resource('/post', 'PostController');
            });

------------------------------------------ ADD CREATOR POST -----------------------------------------------

- pertama buat migration untuk menambahkan field user_id ke tabel post, diterminal ketik :

  `php artisan make:migration tambah_field_user_ke_post`
  
- Kemudian copy dari migration softdelete ke tambah_field_user_ke_post :
    
    
            Schema::table('posts', function (Blueprint $table) {
                        //pake table bawaan softdelete dr laravel
                        $table->integer('users_id');
                    });
                    
- Lalu migrate, pada terminal ketikan :

    `php artisan migrate`
    
- Pergi ke PostController lalu dibagian store kita tambahkan users_id nya :


            $post = Posts::create([
                        'judul' => $request->judul,
                        'category_id' => $request->category_id,
                        'konten' => $request->konten,
                        'gambar' => 'public/uploads/posts/'.$new_gambar,
                        'slug' => Str::slug($request->judul),
                        'users_id' => Auth::id()
                    ]);
                    
  Jangan lupa import Authnya : 
    
  `use Auth;`
  
- Kemudian pada model post jangan lupa ditambah juga dibagian fillable users_id nya :

    
            //buat fillablenya
                protected $fillable = [
                    'judul',
                    'category_id',
                    'konten',
                    'gambar',
                    'slug',
                    'users_id',
                ];

- Lalu kita akan menampilkan user yang posting post tersebut, pada model post buat function baru dgn nama user :

            
            //buat function baru untuk tampilkan user yang posting post
            //buat relasi dengan model user dengan belongsTo
            public function users(){
                return $this->belongsTo('App\User');
            }   

- Kemudian dibagian post/index.blade.php sebelah tags tambahkan suatu kolom untuk nama usernya :


            <tr>
                        <th>No</th>
                        <th>Nama Post</th>
                        <th>Kategori</th>
                        <th>Tag</th>
                        <th>Creator</th>
                        <th>Gambar</th>
                        <th>Action</th>
            </tr>
            
  Kemudian
  
            <td>{{ $hasil->users->name }}</td>

- Lalu kita akan mengubah sedikit tampilan dari tags nya, kita akan ubah sehingga tags nya memiliki badge
- Pada post/index.blade.php kita ubah :

     `<li>{{ $tag->name }}</li>`
     
     menjadi 
     
     `<h6><span class="badge badge-primary">{{ $tag->name }}</span></h6>`

----------------------------------------- TAMPIL DATA USER -------------------------------------------

- Kita akan menampilkan data user yang telah register, pertama buat UserController :
    
    `php artisan make:controller UserController --resource`

- Kemudian kita akan menambahkan menu untuk melihat user pada sidebar.blade.php, copy paste saja lalu ganti2 dikit
    
    
            <li class="dropdown">
                  <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i> <span>User</span></a>
                  <ul class="dropdown-menu">
                      <li><a class="nav-link" href={{ route('user.index') }}>List User</a></li>
                  </ul>
            </li>
            
- Kemudian jangan lupa di web.php tambahkan route index user agar dapat diakses :

    `Route::resource('user', 'UserController');`
    
- Lalu pada UserController bagian index, tambahkan return baru yang akan mereturn ke file user/index.blade.php


            //kita akan berelasi dengan table user
            //kita buat sebuah var untuk menampung data user yang ada didatabase
            $user = User::paginate(10);
            return view('admin.user.index', compact('user'));         
            
- Lalu copy seperti biasa dari category/index.blade.php ke user/index.blade.php lalu ubah2 kata kategori menjadi user

- Kemudian kita akan menambah field baru yaitu tipe_user, pada terminal ketikan :

  `php artisan make:migration tambah_field_tipe_user_ke_user`            
            
- Lalu copy schema dari yang lalu kemudian paste kan kepada file tambah field tipe_user, lalu ganti nama fieldnya dengan tipe         
            
  
              public function up()
              {
                  Schema::table('users', function (Blueprint $table) {
                      //pake table bawaan softdelete dr laravel
                      //kalo bukan admin 0, kalo admin 1 jd defaultnya 0 aja
                      $table->boolean('tipe')->default(0);
                  });
              }
            
- Kemudian migrate ke database, pada terminal ketikan :

    `php artisan migrate`
            
- Kemudian ganti data tipe yang ada didatabase dengan 1

- Lalu pada /user/index.blade.php tambah lagi dengan tipe            
            
            
              <tr>
                  <th>No</th>
                  <th>Nama User</th>
                  <th>Email</th>
                  <th>Role Type</th>
                  <th>Action</th>
              </tr>
            
  Dan
  
            
              <td>
                    <!-- disini kalo tipenya 1 berarti yang muncul kata admin kalo 0 yang muncul kata writer -->
                    @if ($hasil->tipe)
                        <span class="badge badge-info">Admin</span>
                        @else
                        <span class="badge badge-warning">Writer</span>
                    @endif
              </td>
              
----------------------------------------- MANAGEMENT USER ---------------------------------------------

- pada user/index.blade.php ubah 

    `<a href="{{ route('category.create') }}" class="btn btn-info">Tambah User</a>`
    
    menjadi
    
    `<a href="{{ route('user.create') }}" class="btn btn-info">Tambah User</a>`
            
- Pada UserController bagian create :


            public function create()
            {
                return view('admin.user.create');
            }            
            
- Lalu buat file baru /admin/user/create.blade.php, lalu seperti biasa copas dari create.blade.php yang ada difolder lain kemudian ubah2 dikit

- Kemudian pada UserController bagian store :


            public function store(Request $request)
            {
                //kita validasi dulu datanya
                $this->validate($request, [
                    'name' => 'required|min:3|max:100',
                    'email' => 'required|email',
                    'tipe'=> 'required',
                ]);
        
                //jika ketika proses pembuatan user field password tidak diisi baik sengaja atau tidak maka akan mengeluarkan password default
                //jika kita input password maka kita akan store password yang telah dibuat oleh kita
                if ($request->input('password')){
                    $password = bcrypt($request->password);
                } else {
                    //jika tidak maka akan store default password, yaitu 1234
                    $password = bcrypt('1234');
                }
        
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'tipe' => $request->tipe,
                    //ambil data dari var password
                    'password' => $password
                ]);
        
                return redirect()->back()->with('success', 'User Berhasil Disimpan');
            }

- Lalu pada User model di bagian fillable bawaannya tambahkan tipe :


            protected $fillable = [
                'name', 'email', 'password','tipe',
            ];

- Pada /user/index.blade.php ubah route edit menjadi :


            <a href="{{ route('user.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>

- Lalu pada UserController bagian edit :

    
            public function edit($id)
            {
                $user = User::find(id);
                return view('admin.user.edit', compact('user'));
        
            }

- Kemudian buat sebuah file baru /user/edit.blade.php lalu copy seperti biasa dari create.blade.php lalu ubah2

- Lalu pada UserController bagian update :


            public function update(Request $request, $id)
            {
                //kita validasi dulu datanya
                $this->validate($request, [
                    'name' => 'required|min:3|max:100',
                    'tipe'=> 'required',
                ]);
        
                //jika password nya kita input berarti passwordnya kita ubah
                if($request->input('password')){
                    //buat var untuk menampung data
                    $user_data = [
                        'name' => $request->name,
                        'tipe' => $request->tipe,
                        'password' => bcrypt($request->password),
                    ];
                } else { //kalo kita ga input password ya berarti ga diubah diemin aja
                    //buat var untuk menampung data
                    $user_data = [
                        'name' => $request->name,
                        'tipe' => $request->tipe,
                    ];
                }
        
                $user = User::find($id);
                $user->update($user_data);
        
                return redirect()->route('user.index')->with('success', 'Data User Berhasil Diupdate');
            }

- Sekarang untuk delete user pada /user/index.blade.php ubah :
    
    `<form action="{{ route('category.destroy', $hasil->id) }}" method="POST">`
    
    menjadi
    
    `<form action="{{ route('user.destroy', $hasil->id) }}" method="POST">`

- Lalu pada UserController bagian destroy :

    
            public function destroy($id)
            {
                $user = User::find($id);
                $user->delete();
        
                return redirect()->back()->with('success', 'Data User Berhasil Dihapus');
            }

----------------------------------------- Main ke Frontend --------------------------------------

- Buka route Web.php kemudian ubah :


            Route::get('/', function () {
                 return view('welcome');
            });
            
  Menjadi
  
            Route::get('/', function () {
                 return view('blog');
            });

- Lalu edit2 dah itu template, buat folder baru buat tampung templatenya, disini saya pakai calli (kemudian buat foldernya masukan css, font, img, dan js nya ke dalam folder itu)

- Lalu buat sebuah blog controller :

    `php artisan make:controller BlogController`
    
- kemudian dalam BlogController kita import si model post tersebut :

    `use App/Posts;`
    
- Kemudian buat sebuah function baru :


            public function index(Posts $posts){
                //buat sebuah var data yg menampung post
                $data = $posts->orderBy('created_at', 'desc')->get();
                return view('blog', compact('data'));
            }

- Kemudian dalam web.php hapus :


            Route::get('/', function () {
                return view('blog');
            });
            
  Ubah menjadi
  
            Route::get('/', 'BlogController@index');
            
- Lalu kita akan mengirimkan data kita kedalam blog.blade.php, hapus section2 yg tidak dibutuhkan

- Kemudian tambahkan syntax for each pada blog.blade.php

- Jika sudah jadi kurang lebih begini :


                        @foreach($data as $post_terbaru)
                        <div class="col-md-6">
                            <div class="post">
                                <a class="post-img" href="#"><img src="{{ $post_terbaru->gambar }}" alt="" style="height: 200px"></a>
                                <div class="post-body">
                                    <div class="post-category">
                                        <!-- disini post sama category melakukan join (lihat dlm Post.php) -->
                                        <a href="#">{{ $post_terbaru->category->name }}</a>
                                    </div>
                                    <h3 class="post-title"><a href="#">{{ $post_terbaru->judul }}</a></h3>
                                    <ul class="post-meta">
                                        <li><a href="#">{{ $post_terbaru->users->name }}</a></li>
                                        <!-- <li>{{ $post_terbaru->created_at }}</li> -->
                                        <!-- kita pake function carbon (alternatif pertanggalan) -->
                                        <li>{{ $post_terbaru->created_at->diffForHumans() }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endforeach

- Kita akan membagi tampilann template blog nya menjadi beberapa komponen view, buat folder baru dengan nama :

    `template_blog`
    
- Lalu buat file baru :

    `head.blade.php`
    `widget.blade.php`
    `footer.blade.php`
    
- Lalu buat folder baru bernama blog

- Kemudian buat file dalam folder blog yang bernama isi_post.blade.php

- Lalu pada web.php tulis, untuk test, setelah itu comment saja jika sudah berhasil:
    
    
            Route::get('/isi_post', function (){
                return view('blog.isi_post');
            });

- Lalu kita akan menggunakan slug yang telah kita buat untuk seo, pada web.php ketik :

    `Route::get('/isi_post/{slug}', 'BlogController@isi_blog')->name('blog.isi');`
    
- Lalu pada blog controller buat function isi_blog :


            public function isi_blog($slug){
                $data = Posts::where('slug', $slug)->get();
                return view('blog.isi_post', compact('data'));
            }

- Pada blog.blade.php pada bagian `<a href>` berikan sebuah url dinamis yg mengarahkan ke route isi blog :

    ` <a class="post-img" href="{{ route('blog.isi', $post_terbaru->slug) }}"><img src="{{ $post_terbaru->gambar }}" alt="" style="height: 200px"></a>`
    
- Pada isi_post.blade.php modif2 edit2 htmlnya

---------------------------------------- Tambah CK Editor -----------------------------------

- copy script ini pada post/create.blade.php


            <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
            <script>
                CKEDITOR.replace( 'konten' );
            </script>

   lalu tambahkan id="konten"
   
    `<textarea class="form-control" name="konten" id="konten"></textarea>
    
