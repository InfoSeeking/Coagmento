## Coagmento Docs ##

Coagmento Docs is built on top of [Etherpad Lite](https://github.com/ether/etherpad-lite). Once Etherpad lite is installed, you need only change a few settings in setting.json.

We recommend you change `requireSession` to true to ensure that only documents created from Coagmento are accessible.

We recommend you use MySQL as the database, so change `dbType` and `dbSettings` accordingly.

Once the Etherpad server is running make sure the .env file in Coagmento Core has the correct values for the `ETHERPAD_SERVER` and `ETHERPAD_APIKEY`. Also, ensure that both the Etherpad server and Coagmento Core are running on the same root domain, since authentication is done through shared domain cookies.