# SMK Grading (Flask + HTMX + Tailwind)

Migrasi aplikasi pengolahan nilai siswa SMKN 1 Kota Bengkulu dari PHP ke Flask.

## Persyaratan

- Python 3.10+
- MySQL dengan database `penilaian_db`

## Instalasi

Dari folder workspace (parent `grading_project`):

```powershell
cd "d:\Michael\Downloads\code\smkn1 Ver.1.1(Update Design)"
python -m venv .venv
.\.venv\Scripts\Activate.ps1
pip install -r grading_project\requirements.txt
```

Salin `.env.example` ke `.env` dan sesuaikan jika perlu:

```powershell
copy grading_project\.env.example grading_project\.env
```

Buat database di MySQL:

```sql
CREATE DATABASE IF NOT EXISTS penilaian_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Jika database sudah pernah dipakai dengan skema lama (kolom PK pendek), jalankan ulang seed — script akan mengosongkan tabel demo dan mengisi data baru. Untuk instalasi bersih, hapus dan buat ulang database `penilaian_db`.

## Seed data demo

```powershell
python -m grading_project.seed_db
```

## Menjalankan aplikasi

```powershell
python -m grading_project.app
```

Buka http://127.0.0.1:5000 — login admin:

- **User ID:** `1`
- **Password:** `admin123`

## Rute utama

| Modul | URL |
|-------|-----|
| Login | `/auth/login` |
| Dashboard | `/dashboard` |
| Data Admin | `/data/admin` |
| Data Guru | `/data/guru` |
| Data Siswa | `/data/siswa` |
| Data Pelajaran | `/data/pelajaran` |
| Data Kelas | `/data/kelas` |
| Data Jurusan | `/data/jurusan` |
| Data Nilai | `/data/nilai` |
| Kredit | `/kredit` |
