## Coagmento Prototype
This is the start of the next version of Coagmento, written with Laravel.

### Next Up ###
- Integration of Etherpad
	+ Import Etherpad service
	+ Make docs endpoints

## Eventually ##
- Getting Started guide
- Style guide
- Design documents
- Analytics
- Security
	+ Better way to authenticate API endpoints
	+ Filter output HTML entities
- Back-End
	+ Endpoints for follow/unfollowing a project
- Workspace
	+ Escape html entities on all output (from realtime and from blade templates)
	+ Organize Javascript and CSS

## Bugs
- For whatever reason, local Chrome sometimes (only sometimes) drops the session. Specifically, when submitting a form, it would sporadically think the user was not logged in, but then after a refresh be fine.
- Ensure that the creator of a project is always the owner. There was one case where a creator's membership was deleted.