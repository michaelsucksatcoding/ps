from __future__ import annotations

from typing import Any, Type

from flask import abort
from sqlalchemy.exc import IntegrityError

from grading_project.extensions import bcrypt, db


def hash_password(plain: str) -> str:
    hashed = bcrypt.generate_password_hash(plain)
    if isinstance(hashed, bytes):
        return hashed.decode("utf-8")
    return hashed


def list_all(model: Type) -> list:
    return db.session.execute(db.select(model)).scalars().all()


def get_or_404(model: Type, pk: Any):
    row = db.session.get(model, pk)
    if row is None:
        abort(404)
    return row


def create_row(model: Type, data: dict) -> tuple[bool, str | None]:
    try:
        row = model(**data)
        db.session.add(row)
        db.session.commit()
        return True, None
    except IntegrityError as exc:
        db.session.rollback()
        return False, str(exc.orig) if exc.orig else "Duplicate or invalid reference."
    except Exception as exc:
        db.session.rollback()
        return False, str(exc)


def update_row(row, data: dict) -> tuple[bool, str | None]:
    try:
        for key, value in data.items():
            if hasattr(row, key):
                setattr(row, key, value)
        db.session.commit()
        return True, None
    except IntegrityError as exc:
        db.session.rollback()
        return False, str(exc.orig) if exc.orig else "Update failed."
    except Exception as exc:
        db.session.rollback()
        return False, str(exc)


def delete_row(row) -> tuple[bool, str | None]:
    try:
        db.session.delete(row)
        db.session.commit()
        return True, None
    except IntegrityError as exc:
        db.session.rollback()
        return False, str(exc.orig) if exc.orig else "Cannot delete: related records exist."
    except Exception as exc:
        db.session.rollback()
        return False, str(exc)
