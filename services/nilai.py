from __future__ import annotations

from typing import Any

from sqlalchemy import func, select

from grading_project.extensions import db
from grading_project.models import DetailNilai, HeaderNilai, Jurusan, Kelas, Pelajaran, Siswa


def count_tugas(
    kode_pelajaran: str,
    kode_kelas: str,
    semester: str,
    tahun_ajaran: str,
) -> int:
    if not all([kode_pelajaran, kode_kelas, semester, tahun_ajaran]):
        return 0
    rows = db.session.execute(
        select(HeaderNilai.keterangan_nilai).where(
            HeaderNilai.kode_pelajaran == kode_pelajaran,
            HeaderNilai.kode_kelas == kode_kelas,
            HeaderNilai.semester == semester,
            HeaderNilai.tahun_ajaran == tahun_ajaran,
            HeaderNilai.keterangan_nilai.like("tugas%"),
        )
    ).scalars().all()
    return len(rows)


def get_header_id(
    keterangan_nilai: str,
    kode_kelas: str,
    kode_jurusan: str,
    kode_pelajaran: str,
    semester: str,
    tahun_ajaran: str,
) -> int | None:
    hn = db.session.execute(
        select(HeaderNilai).where(
            HeaderNilai.keterangan_nilai == keterangan_nilai,
            HeaderNilai.kode_kelas == kode_kelas,
            HeaderNilai.kode_jurusan == kode_jurusan,
            HeaderNilai.kode_pelajaran == kode_pelajaran,
            HeaderNilai.semester == semester,
            HeaderNilai.tahun_ajaran == tahun_ajaran,
        )
    ).scalar_one_or_none()
    return hn.id_nilai if hn else None


def get_nilai_for_nis(id_nilai: int | None, nis: str) -> str:
    if not id_nilai:
        return "-"
    d = db.session.execute(
        select(DetailNilai).where(
            DetailNilai.id_nilai == id_nilai,
            DetailNilai.nis == nis,
        )
    ).scalar_one_or_none()
    return d.nilai if d and d.nilai else "-"


def get_filtered_nilai_rows(filters: dict[str, str]) -> list[dict]:
    """Build per-student tugas/UTS/UAS columns when filters are complete."""
    kode_pelajaran = filters.get("kode_pelajaran", "")
    kode_kelas = filters.get("kode_kelas", "")
    kode_jurusan = filters.get("kode_jurusan", "")
    semester = filters.get("semester", "")
    tahun_ajaran = filters.get("tahun_ajaran", "")
    tugas_ke = filters.get("tugas_ke", "")

    if not all([kode_pelajaran, kode_kelas, kode_jurusan, semester, tahun_ajaran]):
        students = db.session.execute(select(Siswa).order_by(Siswa.nama_depan)).scalars().all()
        return [
            {
                "nis": s.nis,
                "nama": f"{s.nama_depan} {s.nama_belakang}".strip(),
                "tugas": "-",
                "uts": "-",
                "uas": "-",
            }
            for s in students
        ]

    stmt = select(Siswa).where(
        Siswa.kode_kelas == kode_kelas,
        Siswa.kode_jurusan == kode_jurusan,
    )
    if tahun_ajaran:
        stmt = stmt.where(Siswa.tahun_ajaran == tahun_ajaran)
    students = db.session.execute(stmt.order_by(Siswa.nama_depan)).scalars().all()

    tugas_ket = tugas_ke or "tugas 1"
    tugas_id = get_header_id(tugas_ket, kode_kelas, kode_jurusan, kode_pelajaran, semester, tahun_ajaran)
    uts_id = get_header_id("UTS", kode_kelas, kode_jurusan, kode_pelajaran, semester, tahun_ajaran)
    uas_id = get_header_id("UAS", kode_kelas, kode_jurusan, kode_pelajaran, semester, tahun_ajaran)

    rows = []
    for s in students:
        rows.append(
            {
                "nis": s.nis,
                "nama": f"{s.nama_depan} {s.nama_belakang}".strip(),
                "tugas": get_nilai_for_nis(tugas_id, s.nis),
                "uts": get_nilai_for_nis(uts_id, s.nis),
                "uas": get_nilai_for_nis(uas_id, s.nis),
            }
        )
    return rows


def get_filter_options() -> dict[str, list]:
    pelajaran = db.session.execute(select(Pelajaran).order_by(Pelajaran.pelajaran)).scalars().all()
    kelas = db.session.execute(select(Kelas).order_by(Kelas.tingkat, Kelas.kelas)).scalars().all()
    jurusan = db.session.execute(select(Jurusan).order_by(Jurusan.jurusan)).scalars().all()
    return {
        "pelajaran": pelajaran,
        "kelas": kelas,
        "jurusan": jurusan,
        "semester": ["1", "2"],
        "tahun_ajaran": _tahun_ajaran_options(),
    }


def _tahun_ajaran_options() -> list[str]:
    from datetime import date

    y = date.today().year
    return [f"{i} / {i + 1}" for i in range(y, 2017, -1)]


def get_tugas_options(filters: dict[str, str]) -> list[str]:
    n = count_tugas(
        filters.get("kode_pelajaran", ""),
        filters.get("kode_kelas", ""),
        filters.get("semester", ""),
        filters.get("tahun_ajaran", ""),
    )
    if n < 1:
        n = 1
    return [f"tugas {i}" for i in range(1, n + 1)]


def bulk_input_nilai(
    header_data: dict[str, Any],
    nis_list: list[str],
    nilai_list: list[str],
) -> tuple[bool, str | None]:
    id_nilai = header_data.get("id_nilai")
    if id_nilai:
        try:
            id_nilai = int(id_nilai)
        except (TypeError, ValueError):
            id_nilai = None

    if id_nilai:
        header = db.session.get(HeaderNilai, id_nilai)
        if not header:
            return False, "Header nilai tidak ditemukan."
    else:
        header = db.session.execute(
            select(HeaderNilai).where(
                HeaderNilai.kode_kelas == header_data["kode_kelas"],
                HeaderNilai.kode_jurusan == header_data["kode_jurusan"],
                HeaderNilai.kode_guru == header_data["kode_guru"],
                HeaderNilai.kode_pelajaran == header_data["kode_pelajaran"],
                HeaderNilai.keterangan_nilai == header_data["keterangan_nilai"],
                HeaderNilai.semester == header_data["semester"],
                HeaderNilai.tahun_ajaran == header_data["tahun_ajaran"],
            )
        ).scalar_one_or_none()
        if not header:
            header = HeaderNilai(
                kode_kelas=header_data["kode_kelas"],
                kode_jurusan=header_data["kode_jurusan"],
                kode_guru=header_data["kode_guru"],
                kode_pelajaran=header_data["kode_pelajaran"],
                keterangan_nilai=header_data["keterangan_nilai"],
                semester=header_data["semester"],
                tahun_ajaran=header_data["tahun_ajaran"],
            )
            db.session.add(header)
            db.session.flush()
        id_nilai = header.id_nilai

    for nis, nilai in zip(nis_list, nilai_list):
        if not nis:
            continue
        existing = db.session.execute(
            select(DetailNilai).where(
                DetailNilai.id_nilai == id_nilai,
                DetailNilai.nis == nis,
            )
        ).scalar_one_or_none()
        if existing:
            existing.nilai = str(nilai) if nilai else None
        else:
            db.session.add(DetailNilai(id_nilai=id_nilai, nis=nis, nilai=str(nilai) if nilai else None))
    db.session.commit()
    return True, None


def list_headers_for_delete() -> list[HeaderNilai]:
    return (
        db.session.execute(
            select(HeaderNilai).order_by(HeaderNilai.id_nilai.desc())
        )
        .scalars()
        .all()
    )
