# 📄 SIPADU

### Sistem Informasi Pengarsipan dan Persediaan Terpadu

📍 Lapas Kelas IIB Purwakarta

---

## 📌 Deskripsi

**SIPADU (Sistem Informasi Pengarsipan dan Persediaan Terpadu)** adalah aplikasi berbasis web yang dirancang untuk mengelola **arsip surat** dan **stok persediaan barang** secara terintegrasi dalam satu sistem.

Aplikasi ini membantu meningkatkan efisiensi administrasi, mempermudah pencarian data, serta mendukung proses monitoring dan pelaporan.

---

## 🚀 Fitur Utama

### 📄 Manajemen Surat

* Input data surat
* Upload arsip digital (PDF, DOC, dll)
* Pencarian surat
* Tracking status (Diterima, Diproses, Selesai)
* Disposisi surat
* Edit & hapus data

---

### 📦 Manajemen Persediaan Barang

* Input data barang
* Monitoring stok
* Informasi stok minimum
* Lokasi penyimpanan
* Keterangan barang

---

### 👨‍💼 Workflow Sistem

* **User**: Menginput data surat/barang
* **Admin**: Memproses dan mengubah status
* Sistem berbasis alur kerja (workflow) sederhana

---

## 🛠️ Teknologi

* PHP Native
* MySQL
* Bootstrap 5
* XAMPP / Laragon

---

## ⚙️ Cara Menjalankan

### 1. Clone Repository

```bash id="9u6kzd"
git clone https://github.com/username/sipadu.git
```

### 2. Pindahkan ke Folder Server

```plaintext id="8r3g7v"
C:\xampp\htdocs\
```

### 3. Import Database

* Buka phpMyAdmin
* Buat database: `arsip`
* Import file `.sql`

### 4. Jalankan

```plaintext id="q7y0sf"
http://localhost/sipadu
```

---

## 🔐 Login Default

```plaintext id="v6y4w3"
Username: admin  
Password: password  
```

---

## 📊 Struktur Database (Surat)

* id
* nomor_surat
* tanggal
* pengirim
* perihal
* file
* status
* disposisi_ke

---

## 🎯 Tujuan

* Digitalisasi arsip surat
* Pengelolaan stok barang yang terstruktur
* Meningkatkan efisiensi administrasi

---

## 💡 Pengembangan Selanjutnya

* Import data dari Excel
* Dashboard statistik
* Notifikasi status
* Multi-user system
* Export laporan

---

## 👤 Developer

**Nurul Hadi**
S1 Teknik Informatika

---

## 📌 Catatan

Project ini dibuat sebagai bagian dari kegiatan magang di Lapas Kelas IIB Purwakarta.

---

⭐ Jika project ini bermanfaat, jangan lupa beri star!
