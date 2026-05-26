from __future__ import annotations

import secrets

from grading_project.extensions import bcrypt

# Bcrypt hashes are 60 characters; shorter $2* values are truncated DB data.
BCRYPT_HASH_LEN = 60


def hash_admin_password(plain: str) -> str:
    hashed = bcrypt.generate_password_hash(plain)
    if isinstance(hashed, bytes):
        return hashed.decode("utf-8")
    return hashed


def verify_admin_password(stored: str, plain: str) -> tuple[bool, bool, str]:
    """
    Returns (password_ok, should_rehash, verify_mode).
    verify_mode is one of: bcrypt, legacy_plain, truncated_bcrypt, empty.
    """
    if not stored:
        return False, False, "empty"

    if stored.startswith("$2"):
        if len(stored) < BCRYPT_HASH_LEN:
            return False, False, "truncated_bcrypt"
        try:
            return bcrypt.check_password_hash(stored, plain), False, "bcrypt"
        except ValueError:
            return False, False, "bcrypt"

    if secrets.compare_digest(stored, plain):
        return True, True, "legacy_plain"

    return False, False, "legacy_plain"
