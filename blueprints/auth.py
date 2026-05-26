from __future__ import annotations

from flask import Blueprint, flash, redirect, render_template, request, url_for
from flask_login import current_user, login_required, login_user, logout_user

from grading_project.auth_password import hash_admin_password, verify_admin_password
from grading_project.extensions import db
from grading_project.models import Admin

auth_bp = Blueprint("auth", __name__)


@auth_bp.get("/login")
def login():
    if current_user.is_authenticated:
        return redirect(url_for("main.dashboard"))
    return render_template("login.html")


@auth_bp.post("/login")
def login_post():
    user_id = request.form.get("userid", "").strip()
    password = request.form.get("password", "")

    if not user_id or not password:
        flash("User ID and password are required", "error")
        return render_template("login.html"), 400

    admin = db.session.get(Admin, user_id)
    if not admin:
        flash("Invalid credentials", "error")
        return render_template("login.html"), 401

    password_ok, should_rehash, _ = verify_admin_password(admin.password, password)
    if not password_ok:
        flash("Invalid credentials", "error")
        return render_template("login.html"), 401

    if should_rehash:
        admin.password = hash_admin_password(password)
        db.session.commit()

    login_user(admin)
    return redirect(url_for("main.dashboard"))


@auth_bp.post("/logout")
@login_required
def logout():
    logout_user()
    return redirect(url_for("auth.login"))
