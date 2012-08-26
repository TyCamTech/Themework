<!DOCTYPE html>
<html>
<head>
<title><?php pr($pageTitle); ?></title>
<?php
js();
css();
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

	<p><strong>Congratulations! And welcome to ThemeWork!</strong></p>
	<p>ThemeWork is the first PHP Framework that delivers easy to use theming options. Views are separated by themes right from the start but more importantly than that, you can be up and running with JQuery, Moo Tools, JQuery-UI, Bootstrap and other JS/CSS frameworks with a simple setting in your config! It's all handled for you. OR, you can leave them out and build your own.</p>
	<p><strong>What you do with ThemeWork is up to you!</strong></p>
	<p>You can actually start working with <strong>ThemeWork</strong> right away without fixing any of the warnings below! It's ready to go!<br />
	However, for maximum functionality and customization, it is recommended that you take care of these things now before you get going on everything else.</p>
	<p>Now for the checklist:</p>

	<h3>Configuration</h3>
	<h4>App Config</h4>
	<?php if( $app_config ): ?>
	<p class="alert-success" style="padding: 3px;"><span class="icon-check" style="padding: 3px;"></span>Your config.php file looks great!</p>
	<?php else: ?>
	<p class="alert-info" style="padding: 3px;"><span class="icon-flag" style="padding: 3px;"></span>It looks like you're missing your <em>/app/config/config.php</em> file.</p>
	This framework will actually run quite fine without it, as it comes with it's own core configuration options that should work just fine. But to ensure that your code runs as efficiently and properly as it should, you should consider creating your own config.php file.
	<?php endif; ?>
	<h4>Theme Config</h4>
	<?php if( $theme_config): ?>
	<p class="alert-success"><span class="icon-check"></span>Your theme has it's own 'theme specific' config file! Good work!</p>
	<?php else: ?>
	<p class="alert-info"><span class="icon-flag"></span>Your theme does not currently have it's own config.php file.</p>
	<p>You do not need a config file for your theme but it is good practice to have one. This comes with a LOT of added benefits, for example:
	<ul>
	<li>Each theme can have it's own database set up.</li>
	<li>Each theme can have it's own settings such as pagination counts</li>
	<li>Each theme can enable and disable specific settings, such as debugging</li>
	</ul></p>
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

	<h3>Features</h3>
	<p>If you want to see some of the main features of this framework, which set it apart from all the others, <a href="<?php pr(site_url('features')); ?>">click here</a>.</p>
</div>
</body>
</html>