<!doctype html>
<html lang='en' ng-app>
<head>
<meta charset='utf-8' />
<title>JSON and The Argonauts</title>
<script src="js/angular.js"></script>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.mb.js"></script>
<link media="all" type="text/css" href="css/less.css" id="less-css" rel="stylesheet">
<style>
.page-header {
	height: 50px;
	margin: 50px 0 0;
}
.page-header h1 {
	font-size: 35px;
}
.page-header.top h1 {
	text-align: center;
}
table .centered {
	text-align: center;
}
.navbar-fixed-top {
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
	-o-border-radius: 0;
	border-radius: 0;
	border-right: none;
	border-left: none;
	padding-left: 0;
	padding-right: 0;
	width: 100%;
}
</style>
<script>

	var post_count = <?php echo count($results); ?>;
	function AngularBlog($scope) {
		$scope.posts = [];
		<?php foreach($results as $result){ ?>

			$scope.posts.push({
				title: "<?php echo $result['title']; ?>",
				content: "<?php echo $result['content']; ?>",
				slug: "<?php echo $result['slug']; ?>"
			});

		<?php } ?>
	};

	$(document).ready(function(){
		$('form.form-fluid').live('submit', function(e){
			var form = $(this);
			var data = $(form).serializeArray();
			var json = JSON.stringify(data);
			e.preventDefault();
			$.ajax({
				url: 'ajax/insert.php',
				dataType: 'JSON',
				type: 'POST',
				data: json,
				success: function(results){
					if(results.message) alert(results.message);
					if(results.success) $(form).find('input, textarea').val('');
				}
			});
		});
	});

</script>
</head>
<body>

<div class="navbar-inner navbar-fixed-top">

		<div class="container-narrow">
			<ul class="nav nav-pills" style="margin:10px 0;">
				<li class="active"><a href="#json" data-toggle="tab">JSON</a></li>
				<li><a href="#mongodb" data-toggle="tab">MongoDB</a></li>
				<li><a href="#handlebars" data-toggle="tab">Handlebars</a></li>
				<li><a href="#angular" data-toggle="tab">AngularJS</a></li>
				<li><a href="#mustache" data-toggle="tab">Mustache</a></li>
				<li class="pull-right"><a href="#admin" class="btn btn-small btn-tiny" data-toggle="tab">ADD NEW POST</a></li>
			</ul>
		</div>

</div>

<header class="jumbotron subhead" id="overview">
	<div class="container with-navbar">
		<h1>JSON and The Argonauts!</h1>
		<p class="lead">Guided tour of MongoDB with Handlebars, Mustache or AngularJS</p>
	</div>
</header>
