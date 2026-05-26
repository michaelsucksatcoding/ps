"""Legacy shim — blueprints live in grading_project.blueprints."""

from grading_project.blueprints import auth_bp, data_bp, main_bp

__all__ = ["auth_bp", "main_bp", "data_bp"]
