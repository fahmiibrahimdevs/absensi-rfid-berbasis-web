<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/6de29480-ce70-4a2e-8ed5-9d809f2b431f" />

# Absensi RFID Berbasis Web

Aplikasi absensi berbasis web menggunakan Laravel + Livewire yang terintegrasi dengan pembaca RFID melalui MQTT.

Fitur utama:

-   Manajemen Mahasiswa dan Kelas
-   Pencatatan Absensi (terima data dari pembaca RFID via MQTT)
-   Integrasi MQTT yang dikonfigurasi lewat database (tabel `mqtt`)
-   Tampilan admin menggunakan template Stisla dan Livewire

## Prasyarat

-   PHP 8.0+
-   Composer
-   MySQL / MariaDB
-   Node.js & npm

## Setup cepat (development)

1. Clone repository

```bash
git clone https://github.com/fahmiibrahimdevs/absensi-rfid-berbasis-web.git
cd absensi-rfid-berbasis-web
```

2. Install dependency PHP

```bash
composer install
```

3. Salin file environment dan sesuaikan

```bash
cp .env.example .env
# edit .env: DB_*, APP_URL, dsb
```

4. Generate app key

```bash
php artisan key:generate
```

5. Jalankan migrasi dan seeder

```bash
php artisan migrate --seed
```

6. Install dan build frontend

```bash
npm install
npm run dev
```

7. Jalankan server

```bash
php artisan serve
```

8. Buka `http://localhost:8000`

## Pengaturan MQTT

-   Aplikasi mengambil konfigurasi MQTT dari tabel `mqtt`.
-   Pastikan tabel `mqtt` berisi setidaknya satu baris yang memiliki kolom: `host`, `port`, `username`, `password`.
-   Jika kosong, frontend tidak akan melakukan koneksi MQTT.

Contoh baris minimal pada tabel `mqtt`:

```text
id | host         | port | username | password
1  | xxx.xx.xx.xx | 9001 | user     | secret
```

## Catatan teknis

-   Komponen Livewire `app/Livewire/MasterData/Mahasiswa.php` dan `Absensi.php` memuat konfigurasi MQTT dari DB pada `mount()` dan mengeksposnya ke view.
-   View `resources/views/livewire/master-data/*.blade.php` memasukkan nilai tersebut ke JS dengan `@json($mqtt_host)`.
-   JS menggunakan Paho MQTT client (`mqttws31.min.js`) untuk koneksi WebSocket ke broker MQTT.

## Troubleshooting

-   Tidak bisa terhubung ke broker MQTT? Periksa:

    -   Nilai `host`/`port` di tabel `mqtt`.
    -   Broker menerima koneksi WebSocket (bukan hanya MQTT/TCP).
    -   Kredensial username/password.

-   Periksa log Laravel: `storage/logs/laravel.log`.

## Lisensi

MIT
