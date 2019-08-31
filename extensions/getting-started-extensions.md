## ![Coagmento Logo](https://raw.githubusercontent.com/InfoSeeking/Coagmento/master/core/public/images/logo-small.png) Coagmento Extension ##


### Description ###

Coagmento's Chrome extension is its primary browsing data collection tool. It was developed in JavaScript. In its current state, Coagmento records data on passive inputs (clicks, scrolls, keystrokes, etc.), changes in the browser (tab and window actions), and web navigation (pages and queries), and pushes it to a mySQL database. See documentation below.


### Table of Contents
* [Description](#description)
* [Installation](#installation)
* [Data Collected](#data_collected)
* [Known Bugs](#bugs)


### Installation
The installation of the extension comes with the installation of the core. See the [wiki](https://github.com/InfoSeeking/Coagmento/wiki/Coagmento-Core-Installation) for instructions and more information.

### Data_Collected

See [this spreadsheet](https://github.com/InfoSeeking/Coagmento/blob/study/extensions/chrome/docs/Passive_Inputs_and_Actions.xlsx) for details on the data collected (passive inputs and actions). It describes the specific properties the extension collects. Note that properties in gray boxes are not currently included in Coagmento, but have the capability to be. Also, the general event properties may or may not be included when the extension reads a particular event, as it depends on the particular event itself.

See [this spreadsheet](https://github.com/InfoSeeking/Coagmento/blob/study/extensions/chrome/docs/Passive_Input_Use_Cases.xlsx) for use cases pertaining to the data collected through the extension.


### Known_Bugs

See [this spreadsheet](https://github.com/InfoSeeking/Coagmento/blob/study/extensions/chrome/docs/Known_Bugs.xlsx) for descriptions of known bugs.

### Extra Notes ###
In v3.0, the Firefox extension. It is assumed that users/searchers participating in your studies will use the Chrome extension.

The extension was developed and tested using Chrome Version 76.0.3809.100 (64-bit)
