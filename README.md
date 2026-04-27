# 📄 SIPAS LAPAS

### Sistem Informasi Pengarsipan Surat & Stok Barang

📍 Lapas Kelas IIB Purwakarta

---

## 📌 Deskripsi

**SIPAS LAPAS** adalah aplikasi berbasis web yang dirancang untuk membantu pengelolaan **arsip surat** dan **stok persediaan barang** secara terintegrasi.
Sistem ini bertujuan untuk meningkatkan efisiensi, ketertiban administrasi, serta mempermudah proses pencarian dan pelaporan data.

---

## 🚀 Fitur Utama

### 📄 Manajemen Surat

* Tambah data surat
* Upload arsip digital (PDF, DOC, dll)
* Pencarian surat
* Tracking status (Diterima, Diproses, Selesai)
* Disposisi surat
* Edit & hapus data

### 📦 Manajemen Stok Barang

* Input data barang
* Monitoring stok
* Informasi stok minimum
* Data lokasi penyimpanan
* Keterangan tambahan

### 👨‍💼 Role & Workflow

* **User**: Menginput data surat/barang
* **Admin**: Memproses & mengubah status
* Workflow persetujuan (approval system)

---

## 🛠️ Teknologi yang Digunakan

* PHP Native
* MySQL
* Bootstrap 5
* XAMPP / Laragon

---

## ⚙️ Cara Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/username/sipas-lapas.git
```

### 2. Pindahkan ke Folder Server

```plaintext
C:\xampp\htdocs\
```

### 3. Import Database

* Buka **phpMyAdmin**
* Buat database: `arsip`
* Import file `.sql`

### 4. Jalankan di Browser

```plaintext
http://localhost/arsip
```

---

## 🔐 Login Default (jika ada)

```plaintext
Username: admin
Password: password
```

---

## 📊 Struktur Database (Contoh)

### Tabel Surat

* id
* nomor_surat
* tanggal
* pengirim
* perihal
* file
* status
* disposisi_ke

---

## 🎯 Tujuan Pengembangan

* Digitalisasi arsip surat
* Monitoring stok barang secara real-time
* Meningkatkan efisiensi kerja di lingkungan Lapas

---

## 💡 Pengembangan Selanjutnya

* Import data dari Excel
* Dashboard statistik
* Notifikasi status
* Multi-user login system
* Export laporan

---

## 👤 Developer

**Nurul Hadi**
S1 Teknik Informatika

---

## 📌 Catatan

Project ini dibuat sebagai bagian dari kegiatan magang dan pengembangan inovasi sistem informasi.

---

## ⭐ Dukungan

Jika project ini bermanfaat, jangan lupa ⭐ di repository ini!
