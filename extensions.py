from flask_bcrypt import Bcrypt
from flask_login import LoginManager
from flask_sqlalchemy import SQLAlchemy

# Extensions Pattern
# Instantiate once here; bind in app factory.

db = SQLAlchemy()
bcrypt = Bcrypt()
login_manager = LoginManager()

