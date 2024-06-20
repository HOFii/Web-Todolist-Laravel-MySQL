# STUDI KASUS LARAVEL MYSQL

## POINT UTAMA

### 1. Instalasi

-   Minimal versi PHP ada versi 8,

-   Dan composer versi 2 atau lebih,

-   Upgarde project Laravel dari versi 9 ke versi 10

-   Link [upgrade](https://laravel.com/docs/11.x/upgrade#main-content)

-   Clone dari project sebelumnya, [project](https://github.com/HOFii/Studi-Kasus-Laravel-Dasar)

-   Gunakan perintah `git clone` "url repository yang ingin di clone" nama folder.

---

### 2. Membuat Database

-   Buat database menggunakan mysql dengan nama `belajar_laravel_web_todolist`,

-   Ubah konfigurasi database di file `.env`.

---

### 3. User Model

-   Secara default Laravel sudah menyediakan database migration yang tersimpan didalam folder `database/migration`.

-   Di dalam folder tersebut sudah disediakan file untuk membuat tabel users.

-   Jadi tinggal di jalankan saja database migration nya dengan menggunakan perintah `php artisan migrate`.

---

### 4. User Service

-   Kode User Service Impl

    ```PHP
    function login(string $email, string $password): bool
    {
        return Auth::attempt([ // attempt, coba untuk melakukan login dengan cek ke database
            "email" => $email,
            "password" => $password
        ]);
    }
    ```

---

### 5. User Test

-   Buat seeder untuk mengirim email dan password ke database menggunakan perintah `php artisan make:seed UserSeeder`.

-   Kode User Seeder

    ```PHP
    public function run(): void
    {
        $user = new User();
        $user->name = "Gusti Alifiraqsha Akbar";
        $user->email = "gusti@localhost";
        $user->password = bcrypt("rahasia"); // bcrypt atau hash passowrd
        $user->save();
    }
    ```

-   Kode User Test

    ```PHP
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from users"); // hapus data dari tabel users

        $this->userService = $this->app->make(UserService::class);
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);
        self::assertTrue($this->userService->login("gusti@localhost", "rahasia"));
    }
    ```

-   Cek user menggunakan perintah `php artisan db:seed --class=UserSeeder`, untuk mengirimkan data dari User Seeder ke database.

-   kode User Controller

    ```PHP
     protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from users"); // hapus data dari tabel users
    }

    public function testLoginSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('/login', [
            "user" => "gusti@localhost",
            "password" => "rahasia"
        ])->assertRedirect("/")
            ->assertSessionHas("user", "gusti@localhost");
    }
    ```

---

### 6. Todo Model

-   Gunakan perintah `php artisan make:model Todo --seed --migration`, untuk membuat Todo Model,

-   kode Todo Migartion

    ```PHP
     public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->string("id")->nullable(false)->primary();
            $table->string("todo", 500)->nullable(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('todos');
    }
    ```

-   Kode Todo Model

    ```PHP
    class Todo extends Model
    {
        protected $table = "todos";
        protected $primaryKey = "id";
        protected $keyType = "string";
        protected $fillable = [
            "id",
            "todo"
        ];

        public $timestamps = true;
    }
    ```

-   Setelah todo migration dan model sudah di buat, jalakan migrtion nya,

-   Gunakan perintah `php artisan migrate`.

---

### 7. Todo Service

-   Kode Todo Service

    ```PHP
    interface TodolistService
    {
        public function saveTodo(string $id, string $todo): void; // simpan

        public function getTodolist(): array; // kembalikan

        public function removeTodo(string $todoId); // hapus
    }
    ```

-   kode Todo impl

    ```PHP
    public function saveTodo(string $id, string $todo): void
    {
        $todo = new Todo([
            "id" => $id,
            "todo" => $todo
        ]);
        $todo->save();
    }

    public function getTodolist(): array
    {
        return Todo::query()->get()->toArray();
    }

    public function removeTodo(string $todoId)
    {
        $todo = Todo::query()->find($todoId);
        if ($todo != null) {
            $todo->delete();
        }
    }
    ```

---

### 8. Todo Test

-   Kode Todo Service Test

    ```PHP
    protected function setUp(): void
    {
        parent::setUp();

        DB::delete("delete from todos"); // hapus dari tabel todos

        $this->todolistService = $this->app->make(TodolistService::class);
    }

    public function testSaveTodo() // test simpan todo
    {
        $this->todolistService->saveTodo("1", "Gusti");

        $todolist = $this->todolistService->getTodolist();
        foreach ($todolist as $value) {
            self::assertEquals("1", $value['id']);
            self::assertEquals("Gusti", $value['todo']);
        }
    }
    ```

-   Kode Todo Seeder

    ```PHP
    public function run(): void
    {
        $todo = new Todo();
        $todo->id = "1";
        $todo->todo = "Gusti";
        $todo->save();

        $todo = new Todo();
        $todo->id = "2";
        $todo->todo = "Alifiraqsha";
        $todo->save();
    }
    ```

-   Kode Todo Controller Test

    ```PHP
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from todos"); // hapus dari tabel todos
    }

    public function testTodolist()
    {
        $this->seed(TodoSeeder::class);

        $this->withSession([
            "user" => "akbar"
        ])->get('/todolist')
            ->assertSeeText("1")
            ->assertSeeText("Gusti")
            ->assertSeeText("2")
            ->assertSeeText("Alifiraqsha");
    }
    ```

---

## PERTANYAAN & CATATAN TAMBAHAN

-   Tidak ada.

---

### KESIMPULAN

-   Tidak ada
