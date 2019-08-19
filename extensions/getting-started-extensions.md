## ![Coagmento Logo](https://raw.githubusercontent.com/InfoSeeking/Coagmento/master/core/public/images/logo-small.png) Coagmento Core v3.0 ##


### Description ###
Coagmento Core is the main web application and API. Look at the source for the public version [here](https://coagmento.org).

###### What's New
In v3.0, the study creation mechanism has been blanketed in an easy-to-use interface; jump to [Interface](#interface) to read more. 

### Table of Contents 
* [Description](#description)
* [Installation](#installation)
* [Interface](#interface)
	* [Users](#users)
	* [Emails](#emails)
	* [Tasks](#tasks)
	* [Questionnaires](#questionnaires)
	* [Stages](#stages)
* [Programming](#programming)
* [InfoSeeking](#infoSeeking)

### Installation
See the [wiki](https://github.com/InfoSeeking/Coagmento/wiki/Coagmento-Core-Installation) for installation instructions and more information.

### Interface
Using [Laravel](https://laravel.com), Coagmento's web application allows users to build their own custom studies. Without the programming of unique parts to their studies, researchers use Coagmento to operate through a web-based administrative service to generate questionnaires, tasks, and stages. Anyone can try this out.

#### Users
The manage users tab allows the researcher to quickly create user accounts for participants; the "Add User" button creates a user object with random credentials attributed to the username, email, and password. For debugging purposes, the password is shown to the administrative user. This will be removed in a later version of Coagmento. 
Edit each individual user to provide them with:
* Administrative Access
* Active status

If both the administrative and active status are selected, those credentials will be permitted access to alter and create study components at the same level as the creator. Be careful, since they do have access to remove administrative access from the main account. 
If only the active status is set to true, then this user will be able to access the study at a participant level (the user will be able to take the study).
Leaving the user in default settings, where both attributes are set to false, will keep the user from accessing any part of the study.

#### Emails
Create emails by providing a title accompanied by text. These email templates can be created for sending user credentials and reminders for study participants. Since this feature is being developed, it is currently unavailable for use. 

#### Tasks
Tasks are comprised of descriptions along with attributes if any were created.  By visiting the task settings page, you can manage your attributes. The first half of the pages display the current lists of attributes that are available for edits or removal. The second half allow the researcher to create different attribute types. These attribute settings will be set to null until the user decides to assign a value to the task. Current types of attributes that the admin may create are select and text attributes.

#### Questionnaires
Questionnaires are built and rendered by using [formbuilder](https://formbuilder.readthedocs.io/en/latest/) (read the documentation here). 
Comprised of many questionnaires, the researcher can quickly create questionnaires by dragging questions from a sidebar into the main container. Each question can be altered to have a description, more options, required validation, and more. 
 
#### Stages
When managing stages, there is a draggable list feature that allows the user to easily change the stage order for a user study. To create a stage, the user may add widgets and drag them to the preferred order. The researcher has the choice to choose from questionnaires, tasks, text areas, resources, and confirmation buttons when adding a widget.
The switch to toggling the extension is an option that allows the user to enable the chrome extension for Coagmento when the user taking the study.

### Programming
If you need to program specific parts of the code, refer to the [API reference](http://new.coagmento.org/apidoc/) for a list of available endpoints.
Coagmento is still in development and the user may come across bugs throughout their use of the application. If any problems arise, please email diana.soltani@rutgers.edu for any inquiries about the program. 

### InfoSeeking
To see more from the creators, visit our [website](http://www.infoseeking.org/) or look at our [github](https://github.com/InfoSeeking) for more resources and content.
