from __future__ import annotations

import os

from dotenv import load_dotenv
from flask import Flask

from grading_project.extensions import bcrypt, db, login_manager
from grading_project.models import Admin


def create_app():
    load_dotenv()

    app = Flask(__name__, template_folder="templates", static_folder="static")

    secret_key = os.getenv("FLASK_SECRET_KEY")
    if not secret_key:
        secret_key = "dev-secret-change-me"
    app.secret_key = secret_key

    db_uri = os.getenv("SQLALCHEMY_DATABASE_URI") or os.getenv("DATABASE_URL")
    if not db_uri:
        db_uri = "mysql+pymysql://root:@localhost/penilaian_db"
    app.config["SQLALCHEMY_DATABASE_URI"] = db_uri
    app.config.setdefault("SQLALCHEMY_TRACK_MODIFICATIONS", False)

    db.init_app(app)
    bcrypt.init_app(app)

    with app.app_context():
        db.create_all()
        from grading_project.db_migrations import ensure_admin_password_column

        ensure_admin_password_column()

    login_manager.init_app(app)
    login_manager.login_view = "auth.login"
    login_manager.login_message_category = "warning"

    from grading_project.blueprints import auth_bp, data_bp, main_bp

    app.register_blueprint(auth_bp, url_prefix="/auth")
    app.register_blueprint(main_bp)
    app.register_blueprint(data_bp)

    from grading_project.debug_urls import print_registered_urls

    print_registered_urls(app)

    @login_manager.user_loader
    def load_user(user_id: str):
        return db.session.get(Admin, user_id)

    return app


if __name__ == "__main__":
    app = create_app()
    app.run(debug=True)
