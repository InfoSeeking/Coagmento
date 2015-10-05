## Coagmento Prototype
This is the start of the next version of Coagmento, written with Laravel.

Tentative timeline:

### By 10/4 ###
- Finish unit tests
- Query parsing and endpoints
- Create endpoints for annotations
- Finish API comments

### By 10/11 ###
- Realtime component
- Firefox extension prototype

### By 10/18 ###
- Prototype of workspace

## Eventually ##
- Getting Started guide
- Style guide
- Design documents

The classes in the Services directory should only be a centralized form of the controllers (shared between API and back-end pages).Services should not use other services. Database access logic should be pushed to the models. Sharing code should be done in some other way.