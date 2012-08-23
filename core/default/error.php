<!DOCTYPE html>
<html>
<head>
<title></title>
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
	<h3>Error: <?php pr($status); ?></h3>
	<div class="alert">
		<a class="close" data-dismiss="alert">×</a>
		<span class="icon-fire"></span> &nbsp; <?php echo $error; ?>
	</div>
</div>
</body>
</html>