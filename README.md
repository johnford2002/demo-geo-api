# demo-geo-api

This is a simple REST API designed to provide basic functionality for various geographic tasks like fetching cities in a given state, cities within a given radius, or marking visited locations of a user. It is built on top of silex and dependencies are managed via composer.

# Setup

Begin by installing composer. You can use a local package manager if one is available, or use the following command within the root project directory:

	$ curl -sS https://getcomposer.org/installer | php

After composer is installed, use it fetch the silex framework (and any other dependencies) with the following command:

	$ composer.phar install