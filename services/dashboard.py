from __future__ import annotations

from sqlalchemy import Float, cast, func, select

from grading_project.extensions import db
from grading_project.models import DetailNilai, Guru, HeaderNilai, Jurusan, Kelas, Pelajaran, Siswa


def get_totals() -> dict[str, int]:
    return {
        "guru": db.session.scalar(select(func.count()).select_from(Guru)) or 0,
        "siswa": db.session.scalar(select(func.count()).select_from(Siswa)) or 0,
        "mapel": db.session.scalar(select(func.count()).select_from(Pelajaran)) or 0,
    }


def get_statistik() -> dict:
    rata = db.session.scalar(
        select(func.round(func.avg(cast(DetailNilai.nilai, Float)), 2))
    )
    return {"rata_rata": float(rata or 0)}


def get_semua_siswa() -> list[dict]:
    rows = db.session.execute(
        select(
            Siswa.nis,
            Siswa.nama_depan,
            Siswa.nama_belakang,
            Siswa.jenis_kelamin,
            Kelas.kelas,
            Kelas.tingkat,
            Jurusan.jurusan,
        )
        .join(Kelas, Siswa.kode_kelas == Kelas.kode_kelas, isouter=True)
        .join(Jurusan, Siswa.kode_jurusan == Jurusan.kode_jurusan, isouter=True)
        .order_by(Kelas.kelas, Siswa.nama_depan)
    ).all()

    result = []
    for r in rows:
        nama = f"{r.nama_depan or ''} {r.nama_belakang or ''}".strip()
        kelas_label = f"{r.tingkat or ''} {r.kelas or ''}".strip() or "-"
        result.append(
            {
                "nis": r.nis,
                "nama_siswa": nama,
                "kelas": kelas_label,
                "jurusan": r.jurusan or "-",
                "jenis_kelamin": r.jenis_kelamin or "",
            }
        )
    return result


def get_rekap_nilai() -> list[dict]:
    """Per-student grade summary with KKM tuntas counts."""
    students = get_semua_siswa()
    if not students:
        return []

    details = db.session.execute(
        select(
            DetailNilai.nis,
            DetailNilai.nilai,
            Pelajaran.kkm,
        )
        .join(HeaderNilai, DetailNilai.id_nilai == HeaderNilai.id_nilai)
        .join(Pelajaran, HeaderNilai.kode_pelajaran == Pelajaran.kode_pelajaran)
    ).all()

    per_nis: dict[str, list[tuple[float, float | None]]] = {}
    for d in details:
        try:
            nilai_f = float(d.nilai) if d.nilai else 0.0
        except (TypeError, ValueError):
            nilai_f = 0.0
        try:
            kkm_f = float(d.kkm) if d.kkm else 75.0
        except (TypeError, ValueError):
            kkm_f = 75.0
        per_nis.setdefault(d.nis, []).append((nilai_f, kkm_f))

    rekap = []
    for s in students:
        scores = per_nis.get(s["nis"], [])
        jumlah = len(scores)
        if jumlah:
            rata = sum(x[0] for x in scores) / jumlah
            tuntas = sum(1 for x in scores if x[0] >= x[1])
            belum = jumlah - tuntas
        else:
            rata = 0.0
            tuntas = 0
            belum = 0

        rekap.append(
            {
                **s,
                "jumlah_mapel": jumlah,
                "rata_rata": round(rata, 2),
                "tuntas": tuntas,
                "belum_tuntas": belum,
            }
        )
    return rekap


def get_guru_mapel_summary() -> list[dict]:
    gurus = db.session.execute(select(Guru).order_by(Guru.nama_depan)).scalars().all()
    result = []
    for g in gurus:
        headers = (
            db.session.execute(
                select(HeaderNilai, Pelajaran)
                .join(Pelajaran, HeaderNilai.kode_pelajaran == Pelajaran.kode_pelajaran)
                .where(HeaderNilai.kode_guru == g.kode_guru)
            )
            .all()
        )
        mapel_names = []
        seen = set()
        for _hn, p in headers:
            if p.kode_pelajaran not in seen:
                seen.add(p.kode_pelajaran)
                mapel_names.append(p.pelajaran)
        result.append(
            {
                "kode_guru": g.kode_guru,
                "nama_guru": f"{g.nama_depan} {g.nama_belakang}".strip(),
                "jenis_kelamin": g.jenis_kelamin or "",
                "mapel_diampu": ", ".join(mapel_names) if mapel_names else "-",
                "jumlah_mapel": len(seen),
            }
        )
    return result
