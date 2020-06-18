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

- Pada CategoryController ubah $category = Category::all(); menjadi $category = Category::paginate(*isi nomor pagination*);

- Lalu pada /admin/category/index.blade.php, link pagination yang diambil dari controller {{ $category->links() }}

- Pada /view/template_backend/home.blade.php, buat @yield agar judulnya dapat dipakai ditiap page, dan tidak sama judulnya (co, category, article, dll) dengan @yield('sub-judul')

- Lalu pada /admin/category/index.blade.php, import section sub-judul dibagian atas dengan @section('sub-judul', 'Kategori')

- Lalu pada /admin/category/index.blade.php, tambah dibagian atas : 


    <a href="{{ route('category.create') }}" class="btn btn-info">Tambah Kategori</a>
            <br><br>
    <!-- category.create itu routenya -->
    

- Lalu pada /admin/category/ buat file baru create.blade.php samakan isi import nya dengan index.blade.php, ganti sub-judulnya lalu tambahkan form

- Pada CategoryController kita akan memasukan / menyimpan data yang tabel nya kita panggil dari model kategori, sebelumnya import str helper use Illuminate\Support\Str;

	
	$category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        
        return redirect()->back();
        
        
- Pada model Category kita harus menentukan field mana saja yang boleh dimasukan dalam database : 


    protected $fillable = ['name', 'slug'];
    

- Edit template sidebar menjadi kategori dan <li> nya menjadi List Kategori : 
	
	
	<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Kategori</span></a>
        <ul class="dropdown-menu">
        <li><a class="nav-link" href={{ route('category.index') }}>List Kategori</a></li>


-------------------------------------------- VALIDATION AND FLASH ------------------------------------------------------------------

- Pada CategoryController, tambah validasi ketika menginputkkan data jd jika data yang diinputkan kosong akan muncul validasi bahwa data tidak boleh kosong :

	
	$this->validate($request, [
		'name' => 'required|min:4', 
		//minimal ada 4 hruf yg kita inputkan
	]);
	
- pada admin/category/create.blade.php dibawah section, buat sebuah kondisi jika errornya ada maka akan mengeluarkan message error
    
    
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
    @endif
    	
- Pada CategoryController, tambah with untuk mengeluarkan flash data berhasil disimpan :
	
	
	return redirect()->back()->with('success','Kategori berhasil disimpan');
	
- pada admin/category/create.blade.php, buat sebuah kondisi jika data berhasil dimasukan maka akan mengeluarkan flash berhasil	
    
    
    @if(Session::has('success')) <!-- jika si Session punya success sama seperti yang dikontroller maka kita akan tampilkan -->
        <div class="alert alert-danger" role="alert">
            {{ Session('success') }}
        </div>
    @endif
    	
----------------------------------------------EDIT AND UPDATE DATA---------------------------------------------------------------------------
	
- pada admin/category/create.blade.php, tambahkan href route pada button edit :
	
	
	<a href="{{ route('category.edit', $hasil->id) }}" class="btn btn-primary btn-sm">Edit</a>
	
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
    
    
    <form>
    <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Tag</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href={{ route('category.index') }}>List Tag</a></li>
        </ul>
    </li>
    </form>
    
- Kemudian buat model untuk tag pada terminal ketikan :
    
    
    php artisan make:model Tags -m
    <!-- -m berarti kita buat model sekaligus dimigrasi -->

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


    php artisan migrate
    
- Lalu tambahkan data secara manual pada database table tags, lalu kita memperoleh data yang telah secara manual kita tambahkan pada tabel tags

- Buat controller untuk mengeluarkan data tags yang ada di database :

    
    php artisan make:controller TagController --resource

- Pada web.php tambahkan route baru untuk mengakses controllernya :

    
    Route::resource('/tag', 'TagController');
    
- Jalankan command pada terminal, untuk melihat route list yang dapat digunakan :

    
    php artisan route:list    
    
- Untuk menampilkan data kita perlu route tag.index, pergi ke sidebar.blade.php lalu ubah :

    
    <li><a class="nav-link" href={{ route('category.index') }}>List Kategori</a></li>

    menjadi
    
    <li><a class="nav-link" href={{ route('tag.index') }}>List Kategori</a></li>


- Pada TagController dibagian index ketikan :

    
    //return sebuah view yang merujuk pada sebuah folder /admin/tag/index.blade.php
    return view('admin.tag.index');
    
    
- Buat sebuah file baru di folder /admin

- Copy semua dr folder /admin/category/index.blade.php ke /admin/tag/index.blade.php, lalu ubah sedikit dr category ke tag

- Pada folder TagController bagian index, tambahkan variabel tag (sama kaya category) :

    
    public function index()
        {
            //kita tambah sebuah variabel yang akan memanggil semua data dari category model
            //$tag = Category::all();
    
            $tag = Tag::paginate(10);
    
            //return sebuah view yang merujuk pada sebuah folder /admin/tag/index.blade.php
            return view('admin.tag.index', compact('tag'));
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

    
    protected $fillable = ['name', 'slug'];
    

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
