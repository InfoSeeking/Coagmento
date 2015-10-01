## Coagmento Prototype
This is a prototype of the next version of Coagmento, written with Laravel. The corresponding Firefox extension is located [here](https://bitbucket.org/kevinalbertson/coagmentoprototypeextension).

Next up:
- Finish unit tests
- Create endpoints for pages
- Create endpoints for annotations
- Finish API comments
- Realtime component

The classes in the Services directory should only be a centralized form of the controllers (shared between API and back-end pages).Services should not use other services. Database access logic should be pushed to the models. Sharing code should be done in some other way.