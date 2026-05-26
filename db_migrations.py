from __future__ import annotations

import logging

from sqlalchemy import text

from grading_project.extensions import db

logger = logging.getLogger(__name__)

BCRYPT_MIN_COLUMN_LEN = 60


def ensure_admin_password_column() -> None:
    """Widen legacy CHAR(15) admin.password so bcrypt hashes are not truncated."""
    with db.engine.connect() as conn:
        max_len = conn.execute(
            text(
                """
                SELECT CHARACTER_MAXIMUM_LENGTH
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'admin'
                  AND COLUMN_NAME = 'password'
                """
            )
        ).scalar()

        if max_len is None:
            return

        if max_len < BCRYPT_MIN_COLUMN_LEN:
            conn.execute(
                text(
                    "ALTER TABLE admin MODIFY COLUMN password VARCHAR(255) NOT NULL"
                )
            )
            conn.commit()
            logger.info(
                "Migrated admin.password from CHAR(%s) to VARCHAR(255)",
                max_len,
            )
