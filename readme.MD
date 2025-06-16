# Daftar Informasi Publik (DIP) Kabupaten Bulungan

Aplikasi berbasis web (PHP & MySQL) untuk pengelolaan Daftar Informasi Publik sesuai standar layanan PPID Kabupaten Bulungan. Aplikasi ini memiliki sistem manajemen berbasis user dengan dashboard terpisah antara admin dan user biasa.

## 📁 Struktur Direktori

dip/
├── admin/
│ ├── dashboard.php
│ ├── files.php
│ ├── manage_users.php
│ ├── tambah_user.php
│ ├── export_pdf.php
│ └── export_excel.php
│
├── user/
│ ├── dashboard.php
│ ├── files.php
│ ├── tambah.php
│ ├── edit.php
│ ├── detail.php
│ ├── hapus.php
│ ├── export_pdf.php
│ └── export_excel.php
│
├── public/
│ ├── login.php
│ └── logout.php
│
├── includes/
│ ├── header.php
│ ├── footer.php
│ ├── sidebar.php
│ ├── sidebar_user.php
│ └── functions.php
│
├── config/
│ ├── config.php
│ └── auth.php
│
├── assets/
│ └── images/
│ └── ppid_logo.png
│ └── kopsurat.jpg
│
├── uploads/
│ └── [username]/[tahun]/[semester]/...file...
│
├── vendor/
│ └── (Composer libraries)
│
├── index.php
└── README.md

👤 Role & Fitur
Admin:
Kelola data user (tambah/edit/hapus)
Melihat seluruh file dari semua OPD
Filter data berdasarkan OPD, Jenis Informasi, Tahun, Semester
Export data ke PDF & Excel
Statistik: Jumlah file, pengguna aktif, grafik tren upload

User Biasa:
Hanya bisa melihat dan mengelola file miliknya sendiri
Upload file ke folder otomatis: uploads/{username}/{tahun}/{semester}
Filter dan export data miliknya sendiri
Statistik file pribadi

📌 Fitur CRUD Informasi Publik
Semester (1 atau 2)
Tahun
Jenis Informasi:
Informasi Berkala
Informasi Setiap Saat
Informasi Serta Merta
Ringkasan Isi Informasi
Unit/Satker
Penanggung Jawab
Waktu & Tempat Pembuatan
Bentuk Informasi:
- Cetak
- Rekam
- Online
Retensi Arsip
Upload file (.pdf, .docx, .xlsx)

🖼️ Export PDF
Format landscape
Kop surat (gambar: kopsurat.jpg)
Judul: "DAFTAR INFORMASI PUBLIK<br>TAHUN (Tahun) SEMESTER (Semester)<br>(OPD)"

🔐 Keamanan
Session berbasis role (admin dan user)
Validasi login dan autentikasi halaman
File upload dengan validasi jenis file dan direktori aman per-user

🛠️ Pengembangan
Silakan kembangkan fitur tambahan seperti:
Riwayat upload
Notifikasi admin
Tanda tangan digital
API publik

👨‍💻 Pengembang
Dinas Komunikasi, Informatika, dan Persandian Kabupaten Bulungan
Aplikasi dikembangkan oleh PPID Bulungan bersama tim internal
