# Coagmento
Source for Public Coagmento
(Copied over text from CoagmentoSpring repo)

Coagmento
=========

This repository will contain server-side code and the Firefox extension for [Coagmento](http://coagmento.org/)

Developer Notes
---------------

Error log located in /www/coagmento.org/logs/error_log

Spring 2015 Developer Notes
---------------------------

ProjectID will be used for groups

Bugs
----
- file accumulation at /var/spool/mail/chirags.  Do a tail on it, time permitting.

Future Directions
-----------------
- rewrite all of Coagmento from scratch
  + have a central authenticated API for CRUD access to all data objects
  + API access will be based on permission (public, user, admin, internal)
  + API will be used by Firefox extension, Chrome extension, (maybe customized [Breach](http://breach.cc/) browser)
  + use a separate node server for publish/subscribe notifications
  + (maybe) use a separate node server for chat
- rewrite Firefox extension from scratch using newer Firefox SDK (see sdk_extension)


Config File Parameters
----------------------
- Base URL
- Base folder
- Time zone
- Database format
  + MySQL: username, password, database name
- Study flow?
