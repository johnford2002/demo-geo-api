# demo-geo-api

This is a simple REST API designed to provide basic functionality for various geographic tasks like fetching cities in a given state, cities within a given radius, or marking visited locations of a user. It is built on top of silex and dependencies are managed via composer.

# Setup

##Composer

Begin by installing composer. You can use a local package manager if one is available, or use the following command within the root project directory:

	$ curl -sS https://getcomposer.org/installer | php

After composer is installed, use it fetch the silex framework (and any other dependencies) with the following command:

	$ composer.phar install

For other installation options, see the [composer documentation](https://getcomposer.org/doc/00-intro.md) for assistance.

## Silex

On Apache, you may need to make changes to your .htaccess file, particularly if the app isn't at the root directory. For other web servers and assistance, see the [silex documentation](http://silex.sensiolabs.org/doc/web_servers.html).

## Database

The schema used by the app can be found in the schema folder with a file for each of the various tables used. In order to setup your connection, populate the database.json file located in the config folder with the values for your connection. Initial data sets to load the City, State, and User table can be found in the test folder.