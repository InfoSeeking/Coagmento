## Coagmento Prototype
This is a prototype of the next version of Coagmento, written with Laravel. The corresponding Firefox extension is located [here](https://bitbucket.org/kevinalbertson/coagmentoprototypeextension).

Next up:
- Make services into services :3
- Add unit tests
- Create endpoints for snippets
- Create endpoints for pages
- Create endpoints for annotations
- Comment API docs on controllers and generate docs with http://apidocjs.com/ or similar tool
- Make service classes actual services with facades?

The classes in the Services directory should only be a centralized form of the controllers (shared between API and back-end pages).Services should not use other services. Database access logic should be pushed to the models. Sharing code should be done in some other way.

## How to share code (e.g. checking membership) ##

Is there a way to have it all? Share between services and with user (hasPermissions)?
- Needs to: accept and validate request when called directly from controller
- Accept more specific arguments and skip validation when called internally
- Be located somewhere sensible

My existing decision is as follows.

- Service classes (confusing name?) should be the union of API and back-end calls. This means that they are just like regular controllers, and do not share code directly. They accept request objects and return (possibly API version of) a response.
- Service classes can share code via utility classes (tbd on exact location). These can encapsulate common functionality (checking if a user has necessary permissions for a project). They can communicate via status objects.