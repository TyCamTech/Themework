<?php
/** JavaScript **/
$inject['JS']['angularjs'] = 'http://ajax.googleapis.com/ajax/libs/angularjs/1.0.1/angular.min.js';
$inject['JS']['chrome frame'] = 'http://ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js';
$inject['JS']['dojo'] = 'http://ajax.googleapis.com/ajax/libs/dojo/1.8.0/dojo/dojo.js';
$inject['JS']['ext core'] = 'http://ajax.googleapis.com/ajax/libs/ext-core/3.1.0/ext-core.js';
$inject['JS']['bootstrap'] = 'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/js/bootstrap.min.js';
$inject['JS']['mootools'] = 'http://ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js';
$inject['JS']['prototype'] = 'http://ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js';
$inject['JS']['script.aculo.us'] = 'http://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js';
$inject['JS']['swfobject'] = 'http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js';
$inject['JS']['webfont loader'] = 'http://ajax.googleapis.com/ajax/libs/webfont/1.0.28/webfont.js';

$inject['JS']['jquery'] = 'http://code.jquery.com/jquery-latest.min.js';
$inject['JS']['jquery ui'] = 'http://code.jquery.com/ui/jquery-ui-git.js';
$inject['JS']['jquery mobile'] = 'http://code.jquery.com/mobile/latest/jquery.mobile.min.js';

/** CSS **/
$inject['CSS']['bootstrap'] = 'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/css/bootstrap-combined.min.css';
$inject['CSS']['jquery ui'] = 'http://code.jquery.com/ui/jquery-ui-git.css';
$inject['CSS']['jquery mobile'] = 'http://code.jquery.com/mobile/latest/jquery.mobile.min.css';

/**
 * Packages for injection
 * For example, injecting the bootstrap package means including the jquery.js, bootstrap.js and bootstrap.css files
 **/
$inject['Package']['bootstrap'] = array(
	'JS' => array(
		'jquery',
		'bootstrap'
	),
	'CSS' => array(
		'bootstrap'
	) 
);

$inject['Package']['jquery ui'] = array(
	'JS' => array(
		'jquery',
		'jquery ui'
	),
	'CSS' => array(
		'jquery ui'
	)
);

$inject['Package']['jquery mobile'] = array(
	'JS' => array(
		'jquery mobile'
	),
	'CSS' => array(
		'jquery mobile'
	)
);