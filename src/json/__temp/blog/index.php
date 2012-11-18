<?php

$uri = $_SERVER['REQUEST_URI'];
$uris = explode('/', $uri);
$url = array_pop($uris);

$p = $_POST;
$m = new Mongo();
$db = $m->selectDB('klmug_json');
$collection = new MongoCollection($db, 'posts');
$collection->ensureIndex(array('slug'=>1), array('unique'=>true));

if(isset($p['s'])){ $slug = $p['s']; }else{ $slug = $url; }

$post = array(
  "title" 		=> "Hello World",
  "published" 	=> new MongoDate(strtotime("today")),
  "slug" 		=> $slug,
  "content" 	=> "<p>Hello <a href='#'>JSON</a> World</p>",
  "tags" 		=> array("categories","test","etc")
);

$success = $collection->insert($post); // insert stores as array

if($success){
	$results = array();
	if($slug != 'blog' && $slug != '') $results[] = $collection->findOne(array('slug'=>$slug));
	else {
		$all = $collection->find();
		foreach($all as $result){
			$results[] = $result;
		}
	}
	if(isset($_POST['s'])) {
		echo json_encode($results);
		exit;
	}

}

?><!doctype html>
<html lang='en' ng-app>
	<head>
		<meta charset='utf-8' />
		<title>JSON and The Argonauts</title>
		<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.0.2/angular.min.js"></script>
		<script src="jquery.js"></script>
		<script src="bootstrap.mb.js"></script>
		<link media="all" type="text/css" href="less.css" id="less-css" rel="stylesheet">
		<style>
		.page-header {
			height: 50px;
			margin: 50px 0 0;
		}
		.page-header h1 {
			font-size: 35px;
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

		</script>
	</head>
	<body>

		<div class="navbar-inner">
			<div class="container-narrow">
				<br />
				<ul class="nav nav-pills">
					<li class="active"><a href="#mongodb" data-toggle="tab">MongoDB</a></li>
					<li><a href="#angular" data-toggle="tab">AngularJS</a></li>
					<li><a href="#handlebars" data-toggle="tab">Handlebars</a></li>
					<li><a href="#mustache" data-toggle="tab">Mustache</a></li>
					<li class="pull-right"><a href="#admin" class="btn btn-small" data-toggle="tab">ADD NEW POST</a></li>
				</ul>
			</div>
		</div>

		<header class="jumbotron subhead" id="overview">
			<div class="container">
				<h1>JSON and The Argonauts!</h1>
				<p class="lead">MongoDB + Handlebars / Mustache + AngularJS</p>
			</div>
		</header>

		<div class="container-narrow">

			<div class="tab-content" style="overflow:inherit;">

				<div class="tab-pane active" id="mongodb">

					<div class="page-header">
						<h1>MongoDB :: JSON to BSON</h1>
					</div>
					<p class="clearfix">&nbsp;</p>
					<p>Before we can dive-in and take a closer look at the other Argonauts, we first need data.</p>
					<p>In order to start adding data to MongoDB via AJAX and JSON whilst using PHP and jQuery, we will need a <code>&lt;form&gt;</code>. The source code for the "add new post" feature looks like this:</p>

<pre class="prettyprint linenums">

</pre>

					<p>And then we can apply some simple jQuery to it:</p>

<pre class="prettyprint linenums">

</pre>

					<p>Which would access the following PHP script:</p>

<pre class="prettyprint linenums">
// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

// Quick and dirty way to ensure slugs are unique right out of the gate
$collection->ensureIndex(array('slug'=>1), array('unique'=>true));

// Decode JSON data
if(isset($_POST['data'])) $data = json_decode($_POST['data']);
else $data = false;
if(isset($data['s'])) $slug = $data['s'];
else $slug = 'hello-world';
if(isset($data['title'])) $title = $data['title'];
else $title = strtotime('today');
if(isset($data['content'])) $content = $data['content'];
else{ $content = false;

$post = array(
  "title" => $title,
  "published" => new MongoDate(strtotime("today")),
  "slug" => mb_string_to_slug($title),
  "content" => $content
);

$success = false;
if($title && $content) $success = $collection->insert($post);
// insert stores as array

if($success){

    $results = $collection->findOne(
        array("slug"=>mb_string_to_slug($title))
    ); // FindOne Returns Array
    echo json_encode($results);
    exit;

}
</pre>

				</div>
				
				<div class="tab-pane" id="angular">

					<div class="page-header">
						<h1>AngularJS :: Events on Steroids</h1>
					</div>
					<p class="clearfix">&nbsp;</p>

					<h3>Source Code: <a href="#angular_source" class="btn btn-small btn-info pull-right" style="margin-top:5px;">Live Demo</a></h3>
					<p>This source is the same code being used to power the blog section below...</p>
					<p>Remember to replace the square braces with curly ones!</p>

<pre class="prettyprint linenums">
&lt;div class="container-narrow" ng-controller="AngularBlog"&gt;
    &lt;section ng-repeat="post in posts"&gt;
        &lt;h1&gt;
            &lt;a href="&#91;&#91;post.slug&#93;&#93;"&gt;&#91;&#91;post.title&#93;&#93;&lt;/a&gt;
            &lt;label class="label label-info pull-right"&gt;&#91;&#91;post.date&#93;&#93;&lt;/label&gt;
        &lt;/h1&gt;
        &lt;div ng-bind-html-unsafe="post.content"&gt;&lt;/div&gt;
    &lt;/section&gt;
&lt;/div&gt;
</pre>

					<p>It gets it's data via the following PHP (in the header):</p>

<pre class="prettyprint linenums">
// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

$results = array();
$all = $collection->find();
foreach($all as $result){
	$results[] = $result;
}
</pre>

					<p>Which then gets slapped around with the following spaghetti-based mishmash:</p>

<pre class="prettyprint linenums">
&lt;script&gt;
var post_count = $lt;?php echo count($results); ?&gt;;
function AngularBlog($scope) {
    $scope.posts = [];
    &lt;?php foreach($results as $result){ ?&gt;
        $scope.posts.push({
            title: "&lt;?php echo $result['title']; ?&gt;",
            content: "&lt;?php echo $result['content']; ?&gt;",
            slug: "&lt;?php echo $result['slug']; ?&gt;"
        });
    &lt;?php } ?&gt;
};
&lt;/script&gt;
</pre>

					<p id="angular_source">
						<br />
						The end results are as follows:</p>
					<hr>

					<div ng-controller="AngularBlog">
						<section ng-repeat="post in posts">
							<div class="page-header">
								<h1>
									<?php if(count($results)>1){ ?>
										<a href="{{post.slug}}">
									<?php } ?>
									{{post.title}}
									<?php if(count($results)>1){ ?>
									</a><?php } ?>
									<label class="label label-info pull-right">
										{{post.published}}
									</label>
								</h1>
							</div>
							<p class="clearfix">&nbsp;</p>
							<div ng-bind-html-unsafe="post.content"></div>
						</section>
					</div>

					<p class="clearfix">&nbsp;</p>
					<hr>

				</div>

				<div class="tab-pane" id="handlebars">

					<div class="page-header">
						<h1>Handlebars :: Minimal Templating</h1>
					</div>
					<p class="clearfix">&nbsp;</p>

					<h3>Source Code: <a href="#handlebars_source" class="btn btn-small btn-info pull-right" style="margin-top:5px;">Live Demo</a></h3>
					<p>This source is the same code being used to power the blog section below...</p>
					<p>Remember to replace the square braces with curly ones!</p>

<pre class="prettyprint linenums">
{{#posts}}
    &lt;section&gt;
        &lt;h1&gt;
            &lt;a href="&#91;&#91;slug&#93;&#93;"&gt;&#91;&#91;title&#93;&#93;&lt;/a&gt;
            &lt;label class="label label-info pull-right"&gt;&#91;&#91;published&#93;&#93;&lt;/label&gt;
        &lt;/h1&gt;
        &#91;&#91;&#91;content&#93;&#93;&#93;
    &lt;/section&gt;
{{/posts}}
</pre>

					<p>It gets it's data via the following PHP (in the header):</p>

<pre class="prettyprint linenums">
// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

$results = array();
$all = $collection->find();
foreach($all as $result){
	$results[] = $result;
}
</pre>

				</div>
				<div class="tab-pane" id="mustache"></div>

				<div class="tab-pane" id="admin">

					<div class="page-header">
						<h1>Add New Post :: with jQuery</h1>
					</div>
					<p class="clearfix">&nbsp;</p>
					<form class="form-fluid">
						<input type="text" id="title" name="title" placeholder="Title (also used for slug)" />
						<textarea id="content" name="content" placeholder="Blog post content"></textarea>
						<button type="submit" class="btn btn-info pull-right">Submit</button>
						<p class="clearfix">&nbsp;</p>
					</form>

				</div>

			</div>

			<p class="clearfix">&nbsp;</p>
			<p class="clearfix">&nbsp;</p>
			<hr><p class="clearfix">&nbsp;</p>
			<p>&copy; <?php echo date('Y'); ?> - JSON DOMovan <span class="pull-right"><a href="http://mongodb.my">KL MUG</a> is Powered by <a href="#">MongoStrap</a></span></p>
			<p class="clearfix">&nbsp;</p>

		</div>

	</body>
</html>