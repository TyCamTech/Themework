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
 * Database Information
 **/
$config['data']['default']['driver'] = 'MySQL';
$config['data']['default']['interface'] = true;
$config['data']['default']['host'] = 'localhost';
$config['data']['default']['user'] = '';
$config['data']['default']['pass'] = '';
$config['data']['default']['database'] = '';

$config['data']['wordpress']['driver'] = 'MySQL';
$config['data']['wordpress']['interface'] = false;
$config['data']['wordpress']['host'] = 'localhost';
$config['data']['wordpress']['user'] = 'wp';
$config['data']['wordpress']['pass'] = 'wp';
$config['data']['wordpress']['database'] = 'wp';

/**
 * Name and Tagline - kind of like Wordpress
 **/
$config['site_name'] = 'ThemeWork';
$config['tag_line'] = 'A lightweight, highly theme / skinable MVC php framework';


/**
 * Class Prefixes
 * In this framework, you can use your own core controller or core model
 * classes in the /app/core/ folder.
 * You just need to specify the prefix in which to use to distinguish your files from the core ThemeWork files.
 * For example:
 * $config['class_prefix'] = 'My';
 * means that ThemeWork will look for /app/core/My_controller.php file and class.
 * 
 * If you do use this method, be sure that your My_controller class extends Controller
 **/
$config['class_prefix'] = 'My';

/**
 * Turn debugging output on and off.
 * In the future, this will be done by setting development/production modes
 **/
$config['debug'] = true;


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