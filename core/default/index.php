<!DOCTYPE html>
<html>
<head>
<title><?php pr($pageTitle); ?></title>
<?php
js(array(
	'http://code.jquery.com/jquery-latest.min.js',
	'http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/js/bootstrap.min.js'
));
css('http://netdna.bootstrapcdn.com/twitter-bootstrap/2.1.0/css/bootstrap-combined.min.css');
?>
<style>
h1 {
	color: #CCC;
	margin: 0px;
	text-shadow: #CCC 0.05em 0.05em 0.1em
}
h1 .large {
	color: #888;
	text-shadow: #888 0.05em 0.05em 0.1em
}
h6 {
	color: #666;
	margin: 0px 0px 30px 0px;
}
span, p {
	padding: 3px;
}
</style>
</head>
<body>
<div class="container">
	<h1><span class="large">Theme</span>Work</h1>
	<h6>A lightweight, highly theme / skinable MVC php framework</h6>

	<p><?php pr($output); ?></p>
	<p>ThemeWork is the first PHP Framework that delivers easy to use theming options. Views are separated by themes right from the start but more importantly than that, you can be up and running with JQuery, Moo Tools, JQuery-UI, Bootstrap and other JS/CSS frameworks with a simple setting in your config! It's all handled for you. OR, you can leave them out and build your own.</p>
	<p><strong>What you do with ThemeWork is up to you!</strong></p>
	<p>You can actually start working with <strong>ThemeWork</strong> right away without fixing any of the warnings below! It's ready to go!<br />
	However, for maximum functionality and customization, it is recommended that you take care of these things now before you get going on everything else.</p>
	<p>Now for the checklist:</p>

	<h3>Configuration</h3>
	<?php if( $config_exists ): ?>
	<p class="alert-success" style="padding: 3px;"><span class="icon-check" style="padding: 3px;"></span>Your config.php file looks great!</p>
	<?php else: ?>
	<p class="alert-info" style="padding: 3px;"><span class="icon-flag" style="padding: 3px;"></span>It looks like you're missing your <em><?php pr(APP_CONFIG_PATH); ?>config.php</em> file.</p>
	<p><strong>ThemeWork</strong> has a core config file, which it is pulling from now, but to use your own configuration settings, you must create a file in your /app/config/ folder called config.php.<br />
	You can create more config files, if you wish to separate your configuration settings and call them independently as well.</p>
	<?php endif; ?>

	<h3>Database</h3>
	<?php if( $database_set ): ?>
		<?php if( $database_connection ): ?>
			<p class="alert-success"><span class="icon-check"></span>Valid Connection!</p>
		<?php else: ?>
			<p class="alert-error"><span class="icon-fire"></span>Unable to connect to database.</p>
			<p>It looks like you've set up your database information but <em>ThemeWork</em> is unable to make a connection to it.<br />
			Please ensure that you have spelled everything correctly and that you've checked for case sensitivity.</p>
		<?php endif; ?>
	<?php else: ?>
		<p class="alert-info"><span class="icon-flag"></span>Database is not set</p>
		<p>You have not yet set up your database information. This is actually fine if you do not intend to use a database or if you plan on implementing it another way.<br />
		But if you do plan on using the database, now would be a good time to set up the information in <em><?php pr(APP_CONFIG_PATH); ?>config.php</em></p>
	<?php endif; ?>
</div>
</body>
</html>