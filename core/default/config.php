<?php

/**
 * Set your active theme here.
 * This MUST match the folder name found in /app/theme/
 **/
$config['theme'] = 'default';

/**
 * Path and URL information
 * Please be sure to add a trailing slash to your URLs!
 **/
$config['base_url'] = 'http://localhost/ThemeWork/';

/**
 * Turn debugging output on and off.
 * In the future, this will be done by setting development/production modes
 **/
$config['debug'] = true;

/**
 * Database Information
 **/
$config['db_driver'] = 'MySQL';
$config['db_host'] = 'localhost';
$config['db_user'] = '';
$config['db_pass'] = '';
$config['db_database'] = '';

# Name and Tagline - kind of like Wordpress
$config['site_name'] = 'ThemeWork';
$config['tag_line'] = 'A lightweight, highly theme / skinable MVC php framework';

/** Auto Inject JS/CSS Frameworks
 * 
 * For auto injection to work, you MUST HAVE the js() and css() tags in your templates.
 * It doesn't matter if you add your own params to those functions. They must be there.
 * 
Available frameworks?
JS:
	Angular
	JQuery
	MooTools
 **/
$config['auto_inject_package'] = 'Bootstrap';