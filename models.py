from __future__ import annotations

from flask_sqlalchemy import SQLAlchemy
from sqlalchemy import ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship

# NOTE:
# Models must use the single shared SQLAlchemy instance from extensions.

from grading_project.extensions import db




from flask_login import UserMixin



class Admin(UserMixin, db.Model):
    __tablename__ = "admin"

    id_admin: Mapped[str] = mapped_column(db.String(15), primary_key=True)

    def get_id(self) -> str:
        return str(self.id_admin)

    nama_depan: Mapped[str] = mapped_column(db.String(255), nullable=False)
    nama_belakang: Mapped[str] = mapped_column(db.String(255), nullable=False)
    tempat: Mapped[str] = mapped_column(db.String(255), nullable=True)
    tgl_lahir: Mapped[str] = mapped_column(db.String(50), nullable=True)
    jenis_kelamin: Mapped[str] = mapped_column(db.String(50), nullable=True)
    alamat: Mapped[str] = mapped_column(db.String(500), nullable=True)
    password: Mapped[str] = mapped_column(db.String(255), nullable=False)
    level_admin: Mapped[str] = mapped_column(db.String(50), nullable=True)


class Guru(db.Model):
    __tablename__ = "guru"

    kode_guru: Mapped[str] = mapped_column(db.String(50), primary_key=True)
    nuptk: Mapped[str] = mapped_column(db.String(100), nullable=True)
    nama_depan: Mapped[str] = mapped_column(db.String(255), nullable=False)
    nama_belakang: Mapped[str] = mapped_column(db.String(255), nullable=False)
    tempat: Mapped[str] = mapped_column(db.String(255), nullable=True)
    tgl_lahir: Mapped[str] = mapped_column(db.String(50), nullable=True)
    jenis_kelamin: Mapped[str] = mapped_column(db.String(50), nullable=True)
    alamat: Mapped[str] = mapped_column(db.String(500), nullable=True)
    password: Mapped[str] = mapped_column(db.String(255), nullable=False)

    kelas: Mapped[list["Kelas"]] = relationship(
        "Kelas",
        back_populates="guru",
        cascade="all, delete-orphan",
    )

    header_nilai_entries: Mapped[list["HeaderNilai"]] = relationship(
        "HeaderNilai",
        back_populates="guru",
        cascade="all, delete-orphan",
    )


class Jurusan(db.Model):
    __tablename__ = "jurusan"

    kode_jurusan: Mapped[str] = mapped_column(db.String(50), primary_key=True)
    jurusan: Mapped[str] = mapped_column(db.String(255), nullable=False)

    students: Mapped[list["Siswa"]] = relationship(
        "Siswa",
        back_populates="jurusan",
        cascade="all, delete-orphan",
    )

    header_nilai_entries: Mapped[list["HeaderNilai"]] = relationship(
        "HeaderNilai",
        back_populates="jurusan",
        cascade="all, delete-orphan",
    )


class Kelas(db.Model):
    __tablename__ = "kelas"

    kode_kelas: Mapped[str] = mapped_column(db.String(50), primary_key=True)
    tingkat: Mapped[str] = mapped_column(db.String(50), nullable=True)
    kelas: Mapped[str] = mapped_column(db.String(50), nullable=True)
    kode_guru: Mapped[str] = mapped_column(ForeignKey("guru.kode_guru"), nullable=False)

    guru: Mapped["Guru"] = relationship("Guru", back_populates="kelas")
    students: Mapped[list["Siswa"]] = relationship(
        "Siswa",
        back_populates="kelas",
        cascade="all, delete-orphan",
    )

    header_nilai_entries: Mapped[list["HeaderNilai"]] = relationship(
        "HeaderNilai",
        back_populates="kelas",
        cascade="all, delete-orphan",
    )


class Siswa(db.Model):
    __tablename__ = "siswa"

    nis: Mapped[str] = mapped_column(db.String(50), primary_key=True)
    nama_depan: Mapped[str] = mapped_column(db.String(255), nullable=False)
    nama_belakang: Mapped[str] = mapped_column(db.String(255), nullable=False)
    tempat: Mapped[str] = mapped_column(db.String(255), nullable=True)
    tgl_lahir: Mapped[str] = mapped_column(db.String(50), nullable=True)
    nama_ortu: Mapped[str] = mapped_column(db.String(255), nullable=True)
    jenis_kelamin: Mapped[str] = mapped_column(db.String(50), nullable=True)
    agama: Mapped[str] = mapped_column(db.String(50), nullable=True)
    alamat: Mapped[str] = mapped_column(db.String(500), nullable=True)
    password: Mapped[str] = mapped_column(db.String(255), nullable=False)

    kode_kelas: Mapped[str] = mapped_column(ForeignKey("kelas.kode_kelas"), nullable=False)
    kode_jurusan: Mapped[str] = mapped_column(ForeignKey("jurusan.kode_jurusan"), nullable=False)
    tahun_ajaran: Mapped[str] = mapped_column(db.String(50), nullable=True)

    kelas: Mapped["Kelas"] = relationship("Kelas", back_populates="students")
    jurusan: Mapped["Jurusan"] = relationship("Jurusan", back_populates="students")

    detail_nilai_entries: Mapped[list["DetailNilai"]] = relationship(
        "DetailNilai",
        back_populates="siswa",
        cascade="all, delete-orphan",
    )


class Pelajaran(db.Model):
    __tablename__ = "pelajaran"

    kode_pelajaran: Mapped[str] = mapped_column(db.String(50), primary_key=True)
    pelajaran: Mapped[str] = mapped_column(db.String(255), nullable=False)
    kkm: Mapped[str] = mapped_column(db.String(50), nullable=True)

    header_nilai_entries: Mapped[list["HeaderNilai"]] = relationship(
        "HeaderNilai",
        back_populates="pelajaran",
        cascade="all, delete-orphan",
    )


class HeaderNilai(db.Model):
    __tablename__ = "header_nilai"

    id_nilai: Mapped[int] = mapped_column(db.Integer, primary_key=True, autoincrement=True)

    kode_kelas: Mapped[str] = mapped_column(ForeignKey("kelas.kode_kelas"), nullable=False)
    kode_jurusan: Mapped[str] = mapped_column(ForeignKey("jurusan.kode_jurusan"), nullable=False)
    kode_guru: Mapped[str] = mapped_column(ForeignKey("guru.kode_guru"), nullable=False)
    kode_pelajaran: Mapped[str] = mapped_column(ForeignKey("pelajaran.kode_pelajaran"), nullable=False)

    keterangan_nilai: Mapped[str] = mapped_column(db.String(255), nullable=True)
    semester: Mapped[str] = mapped_column(db.String(50), nullable=True)
    tahun_ajaran: Mapped[str] = mapped_column(db.String(50), nullable=True)

    kelas: Mapped["Kelas"] = relationship("Kelas", back_populates="header_nilai_entries")
    jurusan: Mapped["Jurusan"] = relationship("Jurusan", back_populates="header_nilai_entries")
    guru: Mapped["Guru"] = relationship("Guru", back_populates="header_nilai_entries")
    pelajaran: Mapped["Pelajaran"] = relationship("Pelajaran", back_populates="header_nilai_entries")

    details: Mapped[list["DetailNilai"]] = relationship(
        "DetailNilai",
        back_populates="header",
        cascade="all, delete-orphan",
    )


class DetailNilai(db.Model):
    __tablename__ = "detail_nilai"

    # Legacy queries join on dn.id_nilai = hn.id_nilai and dn.nis ties to siswa.
    # Likely unique per (id_nilai, nis).
    id_nilai: Mapped[int] = mapped_column(
        ForeignKey("header_nilai.id_nilai"),
        primary_key=True,
    )
    nis: Mapped[str] = mapped_column(
        ForeignKey("siswa.nis"),
        primary_key=True,
    )
    nilai: Mapped[str] = mapped_column(db.String(50), nullable=True)

    header: Mapped["HeaderNilai"] = relationship("HeaderNilai", back_populates="details")
    siswa: Mapped["Siswa"] = relationship("Siswa", back_populates="detail_nilai_entries")

