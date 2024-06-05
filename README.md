# STUDI KASUS LARAVEL MYSQL

## POINT UTAMA

### 1. Instalasi

-   Minimal versi PHP ada versi 8,

-   Dan composer versi 2 atau lebih,

-   Upgarde project Laravel dari versi 9 ke versi 10

-   Link: https://laravel.com/docs/11.x/upgrade#main-content

-   Clone dari project sebelumnya, link: https://github.com/HOFii/Studi-Kasus-Laravel-Dasar

-   Gunakan perintah `git clone` "url repository yang ingin di clone" nama folder.

---

### 2. Membuat Database

-   Buat database menggunakan mysql dengan nama `belajar_laravel_web_todolist`,

-   Ubah konfigurasi database di file `.env`.

---

### 3. User Model

-   Secara default Laravel sudah menyediakan database migration yang tersimpan didalam folder `database/migration`.

-   Di dalam folder tersebut sudah disediakan file untuk membuat tabel users.

-   Jadi tinggal di jalankan saya database migration nya dengan menggunakan perintah `php artisan migrate`.

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

## PERTANYAAN & CATATAN TAMBAHAN

-   Tidak ada.

---

### KESIMPULAN

-
