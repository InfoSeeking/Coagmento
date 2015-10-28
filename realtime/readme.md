This is the realtime component of Coagmento, written in NodeJS.

Realtime
--------
One websocket namespace is for feed, another will be for chat.

The model will publish to the node server via http requests.

Clients will subscribe with websockets getting data as it comes in.
Edge cases:

1. Client loses connection with websocket temporarily.
	The client should prompt the user to refresh the data list, which will call the API to repopulate.

2. There's a conflicting change:
	a. Another user deletes/modifies an item as a user is editing/viewing
		Prompt the user that the item was modified, let them either update the item or overwrite it.
	b. You delete/update an item which another user is editing/viewing
		Do nothing, only the effected user should be prompted.

3. The client is out of sync.
	E.g. this could be detected if the user recieves feed data for an update/delete on an item which
	is missing from their local list. In which case, this should prompt a refresh.

4. The project itself is deleted, or the user is kicked out by an owner.
	The user should be prompted and redirected.


Authentication
--------------
It will be simple to authenticate from the back-end application to the realtime application. However, it will
be more complex to do so for the client.

For simplicity, let's have the chat send POST requests directly to the back-end, which will forward them to
the realtime server. In the future, this could be changed to be direct communication with the realtime server
with a buffer periodically being saved.