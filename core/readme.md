## ![Coagmento Logo](https://raw.githubusercontent.com/InfoSeeking/Coagmento/master/core/public/images/logo-small.png) Coagmento Core v3.0 ##


#### Description ####
Coagmento Core is the main web application and API. Look at the source for the public version [here](https://coagmento.org).

###### What's New
In v3.0, the study creation mechanism has been blanketed in an easy-to-use interface; jump to [Interface](#Interface) to read more. 

####Table of Contents ####
* [Description](#Description)
* [Installation](#Installation)
* [Interface](#Interface)
* [Programming](#Programming)
* [InfoSeeking](#InfoSeeking)

####Installation
See the [wiki](https://github.com/InfoSeeking/Coagmento/wiki/Coagmento-Core-Installation) for installation instructions and more information.

####Interface
Using [Laravel](https://laravel.com), Coagmento's web application allows users to build their own studies.

#####Users
The manage users tab allows the generation of random users. Edit each individual user to provide them with:
* Adminstrative Access
* Active status

#####Emails
Create emails by providing a title accompanied by text.

#####Tasks
Tasks are comprised of descriptions along with attributes if you created any.  By visiting the task settings page, you can manage your attributes. Current types of attributes are select and text attributes.

#####Questionnaires
Questionnaires are built and rendered by using [formbuilder](https://formbuilder.readthedocs.io/en/latest/) (read the documents here).
 
#####Stages
Draggable list of stages allows the user to easily change the stage order for a user study.

#### Programming
If you need to program specific parts of the code, refer to the [API reference](http://new.coagmento.org/apidoc/) for a list of available endpoints.

####InfoSeeking
To see more from the creators, visit our [website](http://www.infoseeking.org/) or look at our [github](https://github.com/InfoSeeking) for more resources and content.
