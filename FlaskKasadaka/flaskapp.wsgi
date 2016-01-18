#!/usr/bin/python
import sys
import os
import logging
logging.basicConfig(stream=sys.stderr)
sys.path.insert(0,"/var/www/FlaskKasadaka/")

from FlaskKasadaka import app as application
application.secret_key = os.urandom(24)
