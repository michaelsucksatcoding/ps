#!/usr/bin/env python3
"""Seed penilaian_db with demo data for SMK grading app."""

from __future__ import annotations

from sqlalchemy import delete, select, text

from grading_project.app import create_app
from grading_project.extensions import bcrypt, db
from grading_project.models import (
    Admin,
    DetailNilai,
    Guru,
    HeaderNilai,
    Jurusan,
    Kelas,
    Pelajaran,
    Siswa,
)

ADMIN_USER_ID = "1"
ADMIN_PASSWORD = "admin123"


def _hash(plain: str) -> str:
    h = bcrypt.generate_password_hash(plain)
    return h.decode("utf-8") if isinstance(h, bytes) else h


def seed_all() -> None:
    app = create_app()
    with app.app_context():
        db.session.execute(text("SET FOREIGN_KEY_CHECKS=0"))
        for model in (DetailNilai, HeaderNilai, Siswa, Kelas, Pelajaran, Guru, Jurusan, Admin):
            db.session.execute(delete(model))
        db.session.commit()
        db.session.execute(text("SET FOREIGN_KEY_CHECKS=1"))

        db.session.add_all([
            Jurusan(kode_jurusan="RPL", jurusan="Rekayasa Perangkat Lunak"),
            Jurusan(kode_jurusan="TKJ", jurusan="Teknik Komputer Jaringan"),
        ])
        db.session.add_all([
            Guru(kode_guru="GR1", nama_depan="Budi", nama_belakang="Santoso", jenis_kelamin="L", password=_hash("guru123")),
            Guru(kode_guru="GR2", nama_depan="Siti", nama_belakang="Rahayu", jenis_kelamin="P", password=_hash("guru123")),
        ])
        db.session.flush()
        db.session.add_all([
            Kelas(kode_kelas="XRPL1", tingkat="X", kelas="RPL 1", kode_guru="GR1"),
            Kelas(kode_kelas="XTKJ1", tingkat="X", kelas="TKJ 1", kode_guru="GR2"),
        ])
        db.session.add_all([
            Pelajaran(kode_pelajaran="MTK", pelajaran="Matematika", kkm="75"),
            Pelajaran(kode_pelajaran="BIN", pelajaran="Bahasa Indonesia", kkm="75"),
            Pelajaran(kode_pelajaran="BING", pelajaran="Bahasa Inggris", kkm="75"),
        ])
        tahun = "2025 / 2026"
        db.session.add_all([
            Siswa(nis="1001", nama_depan="Andi", nama_belakang="Pratama", jenis_kelamin="L", kode_kelas="XRPL1", kode_jurusan="RPL", tahun_ajaran=tahun, password=_hash("siswa123")),
            Siswa(nis="1002", nama_depan="Bella", nama_belakang="Putri", jenis_kelamin="P", kode_kelas="XRPL1", kode_jurusan="RPL", tahun_ajaran=tahun, password=_hash("siswa123")),
            Siswa(nis="1003", nama_depan="Candra", nama_belakang="Wijaya", jenis_kelamin="L", kode_kelas="XTKJ1", kode_jurusan="TKJ", tahun_ajaran=tahun, password=_hash("siswa123")),
            Siswa(nis="1004", nama_depan="Dewi", nama_belakang="Lestari", jenis_kelamin="P", kode_kelas="XTKJ1", kode_jurusan="TKJ", tahun_ajaran=tahun, password=_hash("siswa123")),
            Siswa(nis="1005", nama_depan="Eko", nama_belakang="Saputra", jenis_kelamin="L", kode_kelas="XRPL1", kode_jurusan="RPL", tahun_ajaran=tahun, password=_hash("siswa123")),
        ])
        db.session.add(
            Admin(
                id_admin=ADMIN_USER_ID,
                nama_depan="Admin",
                nama_belakang="System",
                password=_hash(ADMIN_PASSWORD),
                level_admin="super",
            )
        )
        db.session.flush()

        headers_spec = [
            ("tugas 1", "MTK", "XRPL1", "RPL", "GR1", "1", tahun),
            ("UTS", "MTK", "XRPL1", "RPL", "GR1", "1", tahun),
            ("UAS", "MTK", "XRPL1", "RPL", "GR1", "1", tahun),
            ("tugas 1", "BIN", "XTKJ1", "TKJ", "GR2", "1", tahun),
            ("UTS", "BIN", "XTKJ1", "TKJ", "GR2", "1", tahun),
        ]
        hid = 1
        for ket, mapel, kelas, jur, guru, sem, ta in headers_spec:
            h = HeaderNilai(
                id_nilai=hid,
                keterangan_nilai=ket,
                kode_pelajaran=mapel,
                kode_kelas=kelas,
                kode_jurusan=jur,
                kode_guru=guru,
                semester=sem,
                tahun_ajaran=ta,
            )
            db.session.add(h)
            hid += 1
            db.session.flush()
            nis_list = ["1001", "1002", "1005"] if kelas == "XRPL1" else ["1003", "1004"]
            base = 78 if ket == "tugas 1" else (82 if ket == "UTS" else 85)
            for i, nis in enumerate(nis_list):
                db.session.add(DetailNilai(id_nilai=h.id_nilai, nis=nis, nilai=str(base + i)))

        db.session.commit()
        print("Database seeded successfully.")
        print(f"  Admin login — User ID: {ADMIN_USER_ID}, Password: {ADMIN_PASSWORD}")


if __name__ == "__main__":
    seed_all()
