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
	<ul>
	<li><strong>Super fast!</strong> <br/>
	The entire core of the framework is only 12 files!</li>
	<li><strong>Based on themes</strong><br/>
	Extends the functionality to limitless possibilities:
		<ul>
			<li>Change designs in an instant</li>
			<li>Use a completely different database for any or all themes</li>
			<li>Enable settings such as debugging per theme</li>
			<li>Allow for theme specific information, such as # of items per page in your pagination (since the # may vary depending on design)</li>
			<li>Allow for seasonal themes! Switch from Summer to Fall to Winter to Spring and back again! You can even code it in to happen automatically!</li>
		</ul>
	</li>
	<li><strong>JS/CSS framework injection!</strong><br />
	With one setting in your config file, you can have the latest JS and CSS files automagically injected into your themes for you to work with.
		<ul>
			<li>In your config file, set $inject['Package'] = 'BootStrap'; and 2 JS files and 1 CSS file will immediately appear in the &lt;head&gt; tag of your theme. Or leave blank to use your own JS/CSS files.</head></li>
			<li>Files are loaded from superior CDN servers ensuring the <strong>latest versions and the fastest load times</strong>.</li>
		</ul>
	</li>
	<li><strong>Superior debugging information</strong><br/>
	See what files are called, in what order, what information is passed and more. See the bottom of this page for an example.</li>
	<li><strong>Drivers</strong> <br />
	You know how most frameworks only support certain databases/datasources and that's it? If you want to use something else, you need to write a library or a plugin. <br />
	With ThemeWork, you can create your own drivers without having to change anything in the core. Just add a single file and you're good to go. <br />
	Also, if you think you can write a better driver than we can, you can override our drivers by adding one of your own with the same name! <br /></li>
	</ul>
</div>
</body>
</html>