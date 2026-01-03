# Absensi RFID Berbasis Web

## Ringkasan

Aplikasi ini adalah sistem absensi berbasis web yang dibuat dengan Laravel dan Livewire. Sistem menerima input dari pembaca RFID melalui broker MQTT (WebSocket), lalu mencatat data absensi mahasiswa ke database. Aplikasi memiliki panel admin untuk mengelola mahasiswa, kelas, dan melihat riwayat absensi.

## Tujuan

-   Menyediakan antarmuka web untuk mencatat dan mengelola absensi mahasiswa.
-   Integrasi dengan perangkat pembaca RFID melalui MQTT untuk otomatisasi perekaman.

## Fitur Utama

-   Manajemen Mahasiswa: tambah, edit, hapus.
-   Manajemen Kelas.
-   Pencatatan Absen otomatis melalui MQTT (UID card diterima, diproses, dan disimpan).
-   Pencarian dan pagination pada tabel data.
-   UI admin menggunakan template Stisla.

## Arsitektur & Alur Data

1. Pembaca RFID membaca kartu dan mengirim UID ke broker MQTT menggunakan topik tertentu (mis. `ABSENSI/REGISTER_UID`).
2. Aplikasi web (frontend JS menggunakan Paho MQTT client) terhubung ke broker via WebSocket dan subscribe ke topic tersebut.
3. Saat pesan UID datang, Livewire akan menerima dan menyimpan UID ke field input (atau langsung memproses check-in jika ada integrasi back-end lain).
4. Data absensi disimpan ke tabel `absensi` di database.
5. Admin dapat melihat dan mengedit data melalui panel.

## Integrasi MQTT

-   Konfigurasi koneksi MQTT disimpan di tabel `mqtt` (kolom `host`, `port`, `username`, `password`).
-   Livewire components memuat konfigurasi dari DB pada `mount()` dan meneruskan ke view agar JS bisa melakukan koneksi.
-   Client JS menggunakan `paho-mqtt` (mqttws31.min.js) untuk koneksi WebSocket.

## Petunjuk Deploy Singkat

-   Pastikan broker MQTT tersedia dan mendukung WebSocket.
-   Atur konfigurasi `mqtt` pada database.
-   Deploy Laravel normal (PHP-FPM + Nginx/Apache), jalankan `composer install`, `php artisan migrate --seed`, dan build assets `npm run build`.

## Catatan Untuk Artikel

-   Tekankan integrasi IoT (RFID -> MQTT -> Web) sebagai poin utama.
-   Sertakan diagram alur data dan contoh topik MQTT yang digunakan.
-   Jelaskan bagaimana konfigurasi MQTT bisa diubah tanpa deploy ulang (disimpan di DB).
-   Tunjukkan potensi pengembangan: autentikasi token, validasi UID, notifikasi real-time, integrasi kamera untuk verifikasi.

## Kontak

Fahmi Ibrahim (pemilik repo) atau dokumentasi di repo untuk detail lebih lanjut.
