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
</style>
</head>
<body>
<div class="container">
	<h1><span class="large">Theme</span>Work</h1>
	<h6>A lightweight, highly theme / skinable MVC php framework</h6>

	<p>Congratulations! And welcome to ThemeWork!!</p>
	<p>ThemeWork is the first PHP Framework that delivers easy to use theming options. Views are separated by themes right from the start but more importantly than that, you can be up and running with JQuery, Moo Tools, JQuery-UI, Bootstrap and other JS/CSS frameworks with a simple setting in your config! It's all handled for you. OR, you can leave them out and build your own.</p>
	<p><strong>What you do with ThemeWork is up to you!</strong></p>
	<p>Let's check that you have everything in place to get rocking right away.</p>

	<h3>Configuration</h3>
	<?php if( $config_exists ): ?>
	<p class="alert-success" style="padding: 3px;"><span class="icon-fire" style="padding: 3px;"></span>Your config.php file looks great!</p>
	<?php else: ?>
	<p class="alert-info" style="padding: 3px;"><span class="icon-flag" style="padding: 3px;"></span>It looks like you're missing your <em>/app/config/config.php</em> file.</p>
	<p>ThemeWork has a core config file, which it is pulling from now, but to use your own configuration settings, you must create a file in your /app/config/ folder called config.php.<br />
	You can create more config files, if you wish to separate your configuration settings and call them independently as well.</p>
	<?php endif; ?>

	<h3>Database</h3>
	<p class="alert-error" style="padding: 3px;">
		<span class="icon-fire"></span> Your database is not yet set up.
	</p>
	<p>You can edit your database information in <em>/app/config/database.php</em> to set up your database connection.</p>
</div>
</body>
</html>