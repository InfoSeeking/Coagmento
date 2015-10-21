## Coagmento Prototype
This is the start of the next version of Coagmento, written with Laravel.

Tentative timeline:

### Next Up ###
- Prototype of workspace
- Create endpoints for annotations
- Create endpoints for follow/unfollow
- Realtime component
- Firefox extension prototype

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