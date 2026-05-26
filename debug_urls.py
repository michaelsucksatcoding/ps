from __future__ import annotations

from flask import Flask


def print_registered_urls(app: Flask) -> None:
    """Print all active endpoints/routes for debugging."""
    output = []
    for rule in sorted(app.url_map.iter_rules(), key=lambda r: (r.rule, r.methods)):
        methods = ",".join(sorted(rule.methods or []))
        output.append(f"{rule.endpoint:40s} {methods:15s} {rule.rule}")
    print("\n=== Registered URLs ===")
    print("\n".join(output))
    print("=== End URLs ===\n")

