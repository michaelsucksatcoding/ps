from __future__ import annotations

from grading_project.models import Admin, Guru, Jurusan, Kelas, Pelajaran, Siswa

# Shared field definitions for generic CRUD templates.

ENTITY_CONFIG = {
    "admin": {
        "model": Admin,
        "pk": "id_admin",
        "title": "Data Admin",
        "list_columns": [
            ("id_admin", "ID"),
            ("nama_depan", "Nama Depan"),
            ("nama_belakang", "Nama Belakang"),
            ("level_admin", "Level"),
        ],
        "form_fields": [
            {"name": "id_admin", "label": "ID Admin", "type": "text", "required": True, "pk": True},
            {"name": "nama_depan", "label": "Nama Depan", "type": "text", "required": True},
            {"name": "nama_belakang", "label": "Nama Belakang", "type": "text", "required": True},
            {"name": "tempat", "label": "Tempat", "type": "text"},
            {"name": "tgl_lahir", "label": "Tanggal Lahir", "type": "date"},
            {"name": "jenis_kelamin", "label": "Jenis Kelamin", "type": "select", "options": [("L", "Laki-laki"), ("P", "Perempuan")]},
            {"name": "alamat", "label": "Alamat", "type": "textarea"},
            {"name": "password", "label": "Password", "type": "password", "hash": True},
            {"name": "level_admin", "label": "Level Admin", "type": "text"},
        ],
    },
    "guru": {
        "model": Guru,
        "pk": "kode_guru",
        "title": "Data Guru",
        "list_columns": [
            ("kode_guru", "Kode"),
            ("nama_depan", "Nama Depan"),
            ("nama_belakang", "Nama Belakang"),
            ("nuptk", "NUPTK"),
        ],
        "form_fields": [
            {"name": "kode_guru", "label": "Kode Guru", "type": "text", "required": True, "pk": True},
            {"name": "nuptk", "label": "NUPTK", "type": "text"},
            {"name": "nama_depan", "label": "Nama Depan", "type": "text", "required": True},
            {"name": "nama_belakang", "label": "Nama Belakang", "type": "text", "required": True},
            {"name": "tempat", "label": "Tempat", "type": "text"},
            {"name": "tgl_lahir", "label": "Tanggal Lahir", "type": "date"},
            {"name": "jenis_kelamin", "label": "Jenis Kelamin", "type": "select", "options": [("L", "Laki-laki"), ("P", "Perempuan")]},
            {"name": "alamat", "label": "Alamat", "type": "textarea"},
            {"name": "password", "label": "Password", "type": "password", "hash": True, "required": True},
        ],
    },
    "siswa": {
        "model": Siswa,
        "pk": "nis",
        "title": "Data Siswa",
        "list_columns": [
            ("nis", "NIS"),
            ("nama_depan", "Nama Depan"),
            ("nama_belakang", "Nama Belakang"),
            ("jenis_kelamin", "JK"),
        ],
        "form_fields": [
            {"name": "nis", "label": "NIS", "type": "text", "required": True, "pk": True},
            {"name": "nama_depan", "label": "Nama Depan", "type": "text", "required": True},
            {"name": "nama_belakang", "label": "Nama Belakang", "type": "text", "required": True},
            {"name": "tempat", "label": "Tempat", "type": "text"},
            {"name": "tgl_lahir", "label": "Tanggal Lahir", "type": "date"},
            {"name": "nama_ortu", "label": "Nama Ortu", "type": "text"},
            {"name": "jenis_kelamin", "label": "Jenis Kelamin", "type": "select", "options": [("L", "Laki-laki"), ("P", "Perempuan")]},
            {"name": "agama", "label": "Agama", "type": "text"},
            {"name": "alamat", "label": "Alamat", "type": "textarea"},
            {"name": "kode_kelas", "label": "Kelas", "type": "fk_kelas", "required": True},
            {"name": "kode_jurusan", "label": "Jurusan", "type": "fk_jurusan", "required": True},
            {"name": "tahun_ajaran", "label": "Tahun Ajaran", "type": "text"},
            {"name": "password", "label": "Password", "type": "password", "hash": True, "required": True},
        ],
    },
    "pelajaran": {
        "model": Pelajaran,
        "pk": "kode_pelajaran",
        "title": "Data Pelajaran",
        "list_columns": [
            ("kode_pelajaran", "Kode"),
            ("pelajaran", "Mata Pelajaran"),
            ("kkm", "KKM"),
        ],
        "form_fields": [
            {"name": "kode_pelajaran", "label": "Kode Pelajaran", "type": "text", "required": True, "pk": True},
            {"name": "pelajaran", "label": "Mata Pelajaran", "type": "text", "required": True},
            {"name": "kkm", "label": "KKM", "type": "text"},
        ],
    },
    "kelas": {
        "model": Kelas,
        "pk": "kode_kelas",
        "title": "Data Kelas",
        "list_columns": [
            ("kode_kelas", "Kode"),
            ("tingkat", "Tingkat"),
            ("kelas", "Kelas"),
            ("kode_guru", "Wali (Kode Guru)"),
        ],
        "form_fields": [
            {"name": "kode_kelas", "label": "Kode Kelas", "type": "text", "required": True, "pk": True},
            {"name": "tingkat", "label": "Tingkat", "type": "text"},
            {"name": "kelas", "label": "Kelas", "type": "text"},
            {"name": "kode_guru", "label": "Wali Kelas (Guru)", "type": "fk_guru", "required": True},
        ],
    },
    "jurusan": {
        "model": Jurusan,
        "pk": "kode_jurusan",
        "title": "Data Jurusan",
        "list_columns": [
            ("kode_jurusan", "Kode"),
            ("jurusan", "Jurusan"),
        ],
        "form_fields": [
            {"name": "kode_jurusan", "label": "Kode Jurusan", "type": "text", "required": True, "pk": True},
            {"name": "jurusan", "label": "Nama Jurusan", "type": "text", "required": True},
        ],
    },
}
