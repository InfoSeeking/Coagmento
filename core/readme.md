## Coagmento Prototype
This is the start of the next version of Coagmento, written with Laravel.

Tentative timeline:

### Next Up ###
Back-End
	- Create endpoints for annotations
	- Create endpoints for follow/unfollow

Workspace
	- Organize Javascript (consider Backbone) and incorporate realtime

Realtime component
	- Add chat room

Firefox Extension

## Eventually ##
- Getting Started guide
- Style guide
- Design documents
- Analytics

## Bugs
- For whatever reason, local Chrome sometimes (only sometimes) drops the session. Specifically, when submitting a form, it would sporadically think the user was not logged in, but then after a refresh be fine.
- Ensure that the creator of a project is always the owner. There was one case where a creator's membership was deleted.

## Front-end
- Test out Backbone, see if applicable
- Currently, the demo needs a bit of cleaning
	- JS handling of API errors (other than alerts)