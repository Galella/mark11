## 🏁 Rangkuman Akhir Perencanaan Proyek TMS

### I. Tujuan dan Ruang Lingkup Proyek

| Kategori | Deskripsi |
| :--- | :--- |
| **Nama Proyek** | Terminal Management System (TMS) Multi-Site |
| **Cakupan Lokasi** | Jakarta, Karawang, Semarang, Surabaya |
| **Fokus Utama** | Pencatatan Keluar-Masuk (Gate In/Out) Kontainer melalui moda **Truk** dan **Kereta Api (ITT)**. |
| **Prioritas Utama** | Integritas data (akurasi) dan Skalabilitas (Multi-Terminal). |

### II. Dasar Teknis dan Arsitektur

| Aspek | Pilihan | Implementasi Kunci |
| :--- | :--- | :--- |
| **Backend** | Laravel 12 | Digunakan untuk mengelola *business logic* dan *database* (Eloquent ORM). |
| **Frontend/UI** | AdminLTE | Digunakan untuk tampilan *dashboard* dan formulir *responsive* (Kartu, DataTables, Select2). |
| **Skema DB Inti** | Multi-Site (Normalisasi) | Semua tabel transaksional terikat pada `terminal_id` untuk segmentasi data antar lokasi. |
| **Validasi Kritis** | Custom Logic | Implementasi validasi **Check Digit ISO 6346** dan **Unique Constraint Komposit** untuk mencegah duplikasi di lokasi yang sama. |

### III. Daftar Modul Fungsional yang Dirancang

Sistem ini terdiri dari 7 modul utama yang saling terintegrasi:

#### 1. Modul Operasi (Gate & Rail)
* **Gate In/Out (Truk):** Pencatatan keluar masuk harian.
* **Rail In/Out (ITT):** Pencatatan *Load on Rail* (LOR) dan *Unload from Rail* (UFR), termasuk penentuan Terminal Tujuan/Asal.
* **Logika Handover:** Penghapusan dari *active\_inventory* Terminal Asal saat `RAIL_OUT` dan Penambahan di Terminal Tujuan saat `RAIL_IN`.

#### 2. Modul Inventori Real-Time
* Tabel **`active_inventory`** sebagai sumber kebenaran (Source of Truth) tentang kontainer yang **sedang berada** di setiap terminal.

#### 3. Modul Manajemen Kereta Api (Rail Master)
* Pencatatan Master Data Kereta (`trains`) dan Gerbong (`wagons`) dengan validasi kapasitas (2 TEUs per Gerbong).
* Manajemen Jadwal (`rail_schedules`) untuk *trip* ITT.

#### 4. Modul Manajemen Pengguna (RBAC)
* Sistem Peran dan Izin (Roles & Permissions) yang mengikat pengguna ke `terminal_id` mereka, memastikan Operator Jakarta hanya melihat data Jakarta.

#### 5. Modul Dashboard & Reporting
* Dashboard *real-time* dengan Filter Lokasi Global.
* KPI: Total Inventori Aktif, Volume Harian (In/Out), dan Tren Moda Transportasi (Truk vs Rail).
* Laporan *actionable* seperti Daftar Kontainer dengan *Dwell Time* tinggi.
