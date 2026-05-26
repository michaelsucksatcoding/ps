from __future__ import annotations

import logging
from collections import defaultdict

from flask import (
    Blueprint,
    flash,
    make_response,
    redirect,
    render_template,
    request,
    url_for,
)
from flask_login import current_user, login_required

from grading_project.extensions import db
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
from grading_project.services import dashboard as dash_svc
from grading_project.services.crud import hash_password

logger = logging.getLogger(__name__)
main_bp = Blueprint("main", __name__)


def _hx_redirect(location: str):
    resp = make_response("", 204)
    resp.headers["HX-Redirect"] = location
    return resp


@main_bp.get("/")
def index():
    if current_user.is_authenticated:
        return redirect(url_for("main.dashboard"))
    return redirect(url_for("auth.login"))


@main_bp.route("/signup", methods=["GET", "POST"])
def signup():
    if request.method == "GET":
        return render_template("signup.html")

    username = (request.form.get("username") or "").strip()
    password = request.form.get("password") or ""
    role = (request.form.get("role") or "").strip().lower()

    if not username or not password or not role:
        flash("Username, password, and role are required", "error")
        return render_template("signup.html"), 400

    try:
        if role == "admin":
            nama_depan = (request.form.get("nama_depan") or "Admin").strip()
            nama_belakang = (request.form.get("nama_belakang") or "User").strip()
            if db.session.get(Admin, username):
                flash("Username already taken", "error")
                return render_template("signup.html"), 409
            db.session.add(
                Admin(
                    id_admin=username,
                    nama_depan=nama_depan,
                    nama_belakang=nama_belakang,
                    password=hash_password(password),
                    level_admin="admin",
                )
            )
        elif role == "guru":
            if db.session.get(Guru, username):
                flash("Username already taken", "error")
                return render_template("signup.html"), 409
            db.session.add(
                Guru(
                    kode_guru=username,
                    nama_depan=request.form.get("nama_depan", "").strip(),
                    nama_belakang=request.form.get("nama_belakang", "").strip(),
                    password=hash_password(password),
                )
            )
        elif role == "siswa":
            if db.session.get(Siswa, username):
                flash("Username already taken", "error")
                return render_template("signup.html"), 409
            db.session.add(
                Siswa(
                    nis=username,
                    nama_depan=request.form.get("nama_depan", "").strip(),
                    nama_belakang=request.form.get("nama_belakang", "").strip(),
                    kode_kelas=request.form.get("kode_kelas", "").strip(),
                    kode_jurusan=request.form.get("kode_jurusan", "").strip(),
                    password=hash_password(password),
                )
            )
        else:
            flash("Invalid role", "error")
            return render_template("signup.html"), 400
        db.session.commit()
    except Exception:
        logger.exception("Signup failed")
        db.session.rollback()
        flash("Database error during signup", "error")
        return render_template("signup.html"), 500

    flash("Signup successful. Please log in.", "success")
    return redirect(url_for("auth.login"))


@main_bp.get("/dashboard")
@login_required
def dashboard():
    totals = dash_svc.get_totals()
    stats = dash_svc.get_statistik()
    siswa_list = dash_svc.get_semua_siswa()
    rekap = dash_svc.get_rekap_nilai()
    guru_mapel = dash_svc.get_guru_mapel_summary()
    return render_template(
        "dashboard.html",
        totals=totals,
        stats=stats,
        siswa_list=siswa_list,
        rekap=rekap,
        guru_mapel=guru_mapel,
    )


@main_bp.get("/kredit")
@login_required
def kredit():
    return render_template("kredit.html")


def _grade_summary_for_nis(nis: str) -> list[dict]:
    headers = {h.id_nilai: h for h in db.session.execute(db.select(HeaderNilai)).scalars()}
    pelajaran_map = {
        p.kode_pelajaran: p
        for p in db.session.execute(db.select(Pelajaran)).scalars()
    }
    details = db.session.execute(
        db.select(DetailNilai).where(DetailNilai.nis == nis)
    ).scalars().all()
    rows = []
    for d in details:
        hn = headers.get(d.id_nilai)
        if not hn:
            continue
        pel = pelajaran_map.get(hn.kode_pelajaran)
        rows.append(
            {
                "id_nilai": d.id_nilai,
                "nis": d.nis,
                "pelajaran": pel.pelajaran if pel else hn.kode_pelajaran,
                "kkm": pel.kkm if pel else None,
                "semester": hn.semester,
                "keterangan_nilai": hn.keterangan_nilai,
                "nilai": d.nilai,
            }
        )
    return rows


@main_bp.get("/dashboard/grades/<nis>")
@login_required
def student_grades_partial(nis: str):
    grades = _grade_summary_for_nis(nis)
    return render_template(
        "partials/student_grades_panel.html",
        nis=nis,
        grades=grades,
    )


@main_bp.get("/dashboard/add-grade")
@login_required
def add_grade_form():
    nis = request.args.get("nis", "")
    students = db.session.execute(
        db.select(Siswa).order_by(Siswa.nama_depan.asc())
    ).scalars().all()
    pelajaran = db.session.execute(
        db.select(Pelajaran).order_by(Pelajaran.pelajaran.asc())
    ).scalars().all()
    return render_template(
        "partials/add_grade_form.html",
        students=students,
        pelajaran=pelajaran,
        nis=nis,
    )


@main_bp.post("/dashboard/add-grade")
@login_required
def add_grade():
    form = request.form
    nis = form.get("nis", "").strip()
    kode_pelajaran = form.get("kode_pelajaran", "").strip()
    semester = form.get("semester", "").strip()
    tahun_ajaran = form.get("tahun_ajaran", "").strip()
    nilai = form.get("nilai", "").strip()
    keterangan_nilai = (form.get("keterangan_nilai", "").strip() or "tugas 1")

    if not (nis and kode_pelajaran and nilai):
        return "<div class='text-sm text-red-600'>Missing required fields.</div>", 400

    siswa = db.session.get(Siswa, nis)
    if not siswa:
        return "<div class='text-sm text-red-600'>Student not found.</div>", 404

    kelas = db.session.get(Kelas, siswa.kode_kelas)
    if not kelas:
        return "<div class='text-sm text-red-600'>Class not found.</div>", 404

    header = db.session.execute(
        db.select(HeaderNilai).where(
            HeaderNilai.kode_kelas == siswa.kode_kelas,
            HeaderNilai.kode_jurusan == siswa.kode_jurusan,
            HeaderNilai.kode_guru == kelas.kode_guru,
            HeaderNilai.kode_pelajaran == kode_pelajaran,
            HeaderNilai.keterangan_nilai == keterangan_nilai,
            HeaderNilai.semester == semester,
            HeaderNilai.tahun_ajaran == tahun_ajaran,
        )
    ).scalar_one_or_none()

    if header is None:
        header = HeaderNilai(
            kode_kelas=siswa.kode_kelas,
            kode_jurusan=siswa.kode_jurusan,
            kode_guru=kelas.kode_guru,
            kode_pelajaran=kode_pelajaran,
            keterangan_nilai=keterangan_nilai,
            semester=semester,
            tahun_ajaran=tahun_ajaran,
        )
        db.session.add(header)
        db.session.flush()

    detail = db.session.execute(
        db.select(DetailNilai).where(
            DetailNilai.id_nilai == header.id_nilai,
            DetailNilai.nis == nis,
        )
    ).scalar_one_or_none()

    if detail is None:
        db.session.add(DetailNilai(id_nilai=header.id_nilai, nis=nis, nilai=str(nilai)))
    else:
        detail.nilai = str(nilai)
    db.session.commit()

    if request.headers.get("HX-Request"):
        return _hx_redirect(url_for("main.dashboard"))
    return redirect(url_for("main.dashboard"))


@main_bp.get("/dashboard/edit-grade")
@login_required
def edit_grade_form():
    id_nilai = request.args.get("id_nilai", type=int)
    nis = request.args.get("nis", "").strip()
    detail = db.session.execute(
        db.select(DetailNilai).where(
            DetailNilai.id_nilai == id_nilai,
            DetailNilai.nis == nis,
        )
    ).scalar_one_or_none()
    if not detail:
        return "<div class='text-sm text-red-600'>Grade not found.</div>", 404

    header = db.session.get(HeaderNilai, detail.id_nilai)
    pel = db.session.get(Pelajaran, header.kode_pelajaran) if header else None
    grade = {
        "id_nilai": detail.id_nilai,
        "nis": detail.nis,
        "nilai": detail.nilai,
        "semester": header.semester if header else None,
        "pelajaran": pel.pelajaran if pel else None,
        "kkm": pel.kkm if pel else None,
    }
    return render_template("partials/edit_grade_form.html", grade=grade)


@main_bp.post("/dashboard/edit-grade")
@login_required
def edit_grade():
    form = request.form
    id_nilai = int(form.get("id_nilai", "0"))
    nis = form.get("nis", "").strip()
    nilai = form.get("nilai", "").strip()

    detail = db.session.execute(
        db.select(DetailNilai).where(
            DetailNilai.id_nilai == id_nilai,
            DetailNilai.nis == nis,
        )
    ).scalar_one_or_none()
    if not detail:
        if request.headers.get("HX-Request"):
            return _hx_redirect(url_for("main.dashboard"))
        return redirect(url_for("main.dashboard"))

    detail.nilai = str(nilai) if nilai else None
    db.session.commit()

    header = db.session.get(HeaderNilai, id_nilai)
    pel = db.session.get(Pelajaran, header.kode_pelajaran) if header else None
    grade = {
        "id_nilai": detail.id_nilai,
        "nis": detail.nis,
        "nilai": detail.nilai,
        "semester": header.semester if header else None,
        "pelajaran": pel.pelajaran if pel else None,
        "kkm": pel.kkm if pel else None,
        "keterangan_nilai": header.keterangan_nilai if header else None,
    }

    if request.headers.get("HX-Request"):
        return render_template("partials/student_grade_row.html", g=grade)
    return redirect(url_for("main.dashboard"))


@main_bp.delete("/dashboard/delete-grade")
@login_required
def delete_detail():
    id_nilai = int(request.args.get("id_nilai", 0))
    nis = request.args.get("nis", "").strip()

    detail = db.session.execute(
        db.select(DetailNilai).where(
            DetailNilai.id_nilai == id_nilai,
            DetailNilai.nis == nis,
        )
    ).scalar_one_or_none()
    if detail:
        db.session.delete(detail)
        db.session.commit()

    if request.headers.get("HX-Request"):
        return "", 204
    return redirect(url_for("main.dashboard"))
