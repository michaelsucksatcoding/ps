from __future__ import annotations

from flask import Blueprint, abort, flash, redirect, render_template, request, url_for
from flask_login import login_required
from sqlalchemy import select

from grading_project.entity_config import ENTITY_CONFIG
from grading_project.extensions import db
from grading_project.models import Guru, HeaderNilai, Jurusan, Kelas, Siswa
from grading_project.services import nilai as nilai_svc
from grading_project.services.crud import (
    create_row,
    delete_row,
    get_or_404,
    hash_password,
    list_all,
    update_row,
)

data_bp = Blueprint("data", __name__, url_prefix="/data")


def _config(entity: str):
    if entity not in ENTITY_CONFIG:
        abort(404)
    return ENTITY_CONFIG[entity]


def _form_context(cfg, row=None, is_edit=False):
    return {
        "entity": cfg,
        "row": row,
        "is_edit": is_edit,
        "gurus": db.session.execute(select(Guru).order_by(Guru.nama_depan)).scalars().all(),
        "kelas_list": db.session.execute(select(Kelas).order_by(Kelas.tingkat)).scalars().all(),
        "jurusan_list": db.session.execute(select(Jurusan).order_by(Jurusan.jurusan)).scalars().all(),
    }


def _parse_form(cfg, is_edit: bool) -> dict:
    data = {}
    for field in cfg["form_fields"]:
        name = field["name"]
        if is_edit and field.get("pk"):
            continue
        raw = request.form.get(name, "")
        if field["type"] == "password":
            if not raw and is_edit:
                continue
            if raw and field.get("hash"):
                raw = hash_password(raw)
            elif not raw and field.get("required") and not is_edit:
                raw = hash_password("changeme")
        if raw == "" and not field.get("required"):
            data[name] = None
        else:
            data[name] = raw.strip() if isinstance(raw, str) else raw
    return data


# ---- Nilai (static paths before /<entity>) ----


@data_bp.get("/nilai")
@login_required
def nilai_index():
    filters = {
        "kode_pelajaran": request.args.get("kode_pelajaran", ""),
        "kode_kelas": request.args.get("kode_kelas", ""),
        "kode_jurusan": request.args.get("kode_jurusan", ""),
        "semester": request.args.get("semester", ""),
        "tahun_ajaran": request.args.get("tahun_ajaran", ""),
        "tugas_ke": request.args.get("tugas_ke", ""),
        "nis": request.args.get("nis", ""),
    }
    options = nilai_svc.get_filter_options()
    tugas_options = nilai_svc.get_tugas_options(filters)
    rows = nilai_svc.get_filtered_nilai_rows(filters)
    ctx = dict(filters=filters, options=options, tugas_options=tugas_options, rows=rows)
    if request.headers.get("HX-Request"):
        return render_template("data/nilai/_table.html", **ctx)
    return render_template("data/nilai/index.html", **ctx)


@data_bp.route("/nilai/input", methods=["GET", "POST"])
@login_required
def nilai_input():
    options = nilai_svc.get_filter_options()
    gurus = db.session.execute(select(Guru).order_by(Guru.nama_depan)).scalars().all()

    if request.method == "GET":
        filters = {k: request.args.get(k, "") for k in (
            "kode_pelajaran", "kode_kelas", "kode_jurusan", "semester",
            "tahun_ajaran", "keterangan_nilai", "kode_guru",
        )}
        students = []
        if filters.get("kode_kelas") and filters.get("kode_jurusan"):
            stmt = select(Siswa).where(
                Siswa.kode_kelas == filters["kode_kelas"],
                Siswa.kode_jurusan == filters["kode_jurusan"],
            )
            students = db.session.execute(stmt.order_by(Siswa.nama_depan)).scalars().all()
        return render_template(
            "data/nilai/input.html",
            options=options,
            gurus=gurus,
            filters=filters,
            students=students,
        )

    header_data = {
        "kode_kelas": request.form.get("kode_kelas", "").strip(),
        "kode_jurusan": request.form.get("kode_jurusan", "").strip(),
        "kode_guru": request.form.get("kode_guru", "").strip(),
        "kode_pelajaran": request.form.get("kode_pelajaran", "").strip(),
        "keterangan_nilai": request.form.get("keterangan_nilai", "").strip(),
        "semester": request.form.get("semester", "").strip(),
        "tahun_ajaran": request.form.get("tahun_ajaran", "").strip(),
    }
    id_nilai = request.form.get("id_nilai", "").strip()
    if id_nilai:
        header_data["id_nilai"] = id_nilai

    ok, err = nilai_svc.bulk_input_nilai(
        header_data,
        request.form.getlist("nis"),
        request.form.getlist("nilai"),
    )
    if not ok:
        flash(err or "Gagal input nilai", "error")
        return redirect(url_for("data.nilai_input"))
    flash("Berhasil input nilai.", "success")
    return redirect(url_for("data.nilai_index"))


@data_bp.route("/nilai/hapus", methods=["GET", "POST"])
@login_required
def nilai_hapus():
    headers = nilai_svc.list_headers_for_delete()
    if request.method == "POST":
        id_nilai = request.form.get("id_nilai", type=int)
        if not id_nilai:
            flash("Pilih header nilai.", "error")
            return redirect(url_for("data.nilai_hapus"))
        header = db.session.get(HeaderNilai, id_nilai)
        if header:
            delete_row(header)
            flash("Header nilai dihapus.", "success")
        return redirect(url_for("data.nilai_index"))
    return render_template("data/nilai/hapus.html", headers=headers)


# ---- Generic CRUD ----


def _rows_as_dicts(rows, columns, pk):
    out = []
    for r in rows:
        d = {col: getattr(r, col, None) for col, _ in columns}
        d[pk] = getattr(r, pk)
        out.append(d)
    return out


@data_bp.get("/<entity>")
@login_required
def list_entity(entity: str):
    cfg = _config(entity)
    raw = list_all(cfg["model"])
    rows = _rows_as_dicts(raw, cfg["list_columns"], cfg["pk"])
    return render_template("data/list.html", entity_name=entity, cfg=cfg, rows=rows)


@data_bp.route("/<entity>/new", methods=["GET", "POST"])
@login_required
def create_entity(entity: str):
    cfg = _config(entity)
    if request.method == "GET":
        return render_template(
            "data/form.html",
            entity_name=entity,
            **_form_context(cfg, is_edit=False),
        )
    data = _parse_form(cfg, is_edit=False)
    pk_name = cfg["pk"]
    if not data.get(pk_name):
        flash(f"{pk_name} is required.", "error")
        return render_template(
            "data/form.html", entity_name=entity, **_form_context(cfg, is_edit=False)
        ), 400
    ok, err = create_row(cfg["model"], data)
    if not ok:
        flash(err or "Create failed", "error")
        return render_template(
            "data/form.html", entity_name=entity, **_form_context(cfg, is_edit=False)
        ), 400
    flash("Data berhasil ditambahkan.", "success")
    return redirect(url_for("data.list_entity", entity=entity))


@data_bp.route("/<entity>/<pk>/edit", methods=["GET", "POST"])
@login_required
def edit_entity(entity: str, pk: str):
    cfg = _config(entity)
    row = get_or_404(cfg["model"], pk)
    if request.method == "GET":
        return render_template(
            "data/form.html",
            entity_name=entity,
            **_form_context(cfg, row=row, is_edit=True),
        )
    data = _parse_form(cfg, is_edit=True)
    ok, err = update_row(row, data)
    if not ok:
        flash(err or "Update failed", "error")
        return render_template(
            "data/form.html", entity_name=entity, **_form_context(cfg, row=row, is_edit=True)
        ), 400
    flash("Data berhasil diubah.", "success")
    return redirect(url_for("data.list_entity", entity=entity))


@data_bp.route("/<entity>/<pk>/delete", methods=["GET", "POST"])
@login_required
def delete_entity(entity: str, pk: str):
    cfg = _config(entity)
    row = get_or_404(cfg["model"], pk)
    if request.method == "POST":
        ok, err = delete_row(row)
        if not ok:
            flash(err or "Delete failed", "error")
            return redirect(url_for("data.delete_entity", entity=entity, pk=pk))
        flash("Data berhasil dihapus.", "success")
        return redirect(url_for("data.list_entity", entity=entity))
    return render_template(
        "data/delete_confirm.html", entity_name=entity, cfg=cfg, row=row, pk=pk
    )


@data_bp.route("/<entity>/delete", methods=["GET", "POST"])
@login_required
def delete_entity_select(entity: str):
    cfg = _config(entity)
    raw = list_all(cfg["model"])
    rows = _rows_as_dicts(raw, [(cfg["pk"], cfg["pk"])], cfg["pk"])
    pk_name = cfg["pk"]
    if request.method == "POST":
        pk = request.form.get(pk_name, "").strip()
        if not pk:
            flash("Pilih data yang akan dihapus.", "error")
            return redirect(url_for("data.delete_entity_select", entity=entity))
        return redirect(url_for("data.delete_entity", entity=entity, pk=pk))
    return render_template(
        "data/delete_select.html", entity_name=entity, cfg=cfg, rows=rows
    )
