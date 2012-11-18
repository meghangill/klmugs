<?php

$uri = $_SERVER['REQUEST_URI'];
$uris = explode('/', $uri);
$url = array_pop($uris);

$p = $_POST;
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

$results = array();
if($url != '') $results[] = $collection->findOne(array('slug'=>$url));
else {
	$all = $collection->find();
	foreach($all as $result){
		$results[] = $result;
	}
};
?><!doctype html>
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
					e.preventDefault();
					$.ajax({
						url: 'ajax/insert.php',
						dataType: 'JSON',
						type: 'POST',
						data: $(this).serializeArray(),
						success: function(results){
							if(results) alert(results);
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

		<div class="container-narrow">

			<div class="tab-content" style="overflow:inherit;">

				<div class="tab-pane active" id="json">

					<div class="page-header top">
						<h1>JSON :: The Full Story</h1>
					</div>
					<p class="clearfix">&nbsp;</p>
					<p>Let's first take a moment to consider what <a href="http://json.org">JSON</a> really is.</p>
					<p>It seems the website might be the same original one released in 1999, when <a href="http://en.wikipedia.org/wiki/Douglas_Crockford">Douglas Crockford</a> first thought-up JSON, which was originally conceived as a sub-set of functions specifically for JavaScript, which is why it was named JavaScript Object Notation.</p>
					<p>It quickly became adopted as a <strong>language-independent</strong> data-format, and the rest is history...</p>
					<p>In PHP, we often transport data around using arrays;</p>

<pre class="prettyprint linenums">
$data = array(
    'name' => 'Mark Smalley',
    'twitter' => 'm_smalley',
    'blog' => 'smalley.my'
);
</pre>
					<p>If we converted that into a JSON array and then echoed or printed the results:</p>

<pre class="prettyprint linenums">
$json_data = json_encode($data);
echo $json_data;
var_dump($json_data);
</pre>
					<p>The end result would be the following JSON-formatted <code>(string)</code>:</p>

<pre class="prettyprint linenums">
{"name":"Mark Smalley","twitter":"m_smalley","blog":"smalley.my"}
</pre>
					<p>As a string, it can be easily transported through <code>$_GET</code> or <code>$_POST</code> requests.</p>
					<p>And that's it. <strong>It's just data as a string</strong>.</p>

					<hr>

					<h3>So what's this JSONp all about...?</h3>
					<p>The " <strong>p</strong> " stands for <strong>padding</strong>, but not with pixels, rather with a function. It's one of those hacks that became standardized and eventually accepted as the real way to do things. It all comes down to security and cross-domain policies that make it really tricky to use AJAX requests from one site to another, especially when it comes to transporting data.</p>
					<p>If our JSON array looked like this:</p>

<pre class="prettyprint linenums">
{
    "name" : "Mark Smalley",
    "twitter" : "m_smalley",
    "blog" : "smalley.my"
}
</pre>
					<p>JSONp would turn it into something a little like this:</p>

<pre class="prettyprint linenums">
{jsonp_callback_function({
    "name" : "Mark Smalley",
    "twitter" : "m_smalley",
    "blog" : "smalley.my"
})}
</pre>

					<p>Rather than trying to transport data, you transport a function, then call that function from within the same domain to prevent all the cross-site headaches.</p>

					<hr>

					<h3>BSON :: B = Binnary</h3>

					<p>Last but most certainly not least, there's also BSON, but before I dive into that, let's first take a step back and return to JSON, which is after all a data-format.</p>
					<p>Data formats would not be very useful if they could only store one kind of data type, and so far - we've only be talking about strings.</p>
					<p>JSON features 6 data-types, whereas BSON features 11 and was built by <a href="http://10gen.com">10gen</a> specifically for mongoDB - so let's compare the available data-types:</p>

					<hr>

					<table class="table table-striped">
						<thead>
							<tr>
							  <th>TYPE</th>
							  <th class="centered">JSON</th>
							  <th class="centered">BSON</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
							  <td>Number</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>String</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Boolean</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Array</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Object</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Null</td>
							  <td class="centered">X</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Float</td>
							  <td class="centered">-</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Date</td>
							  <td class="centered">-</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>Regular Expression</td>
							  <td class="centered">-</td>
							  <td class="centered">X</td>
							</tr>
							<tr>
							  <td>JavaScript Code</td>
							  <td class="centered">-</td>
							  <td class="centered">X</td>
							</tr>
						</tbody>
					</table>

					<p>But wait, there's only ten data types listed above and you said BSON features 11.</p>
					<p>BSON can store BSON arrays and Byte arrays - listed above as just arrays.</p>

					<hr>

					<p>And so ends our history lesson for the day.</p>
					<p>For more detailed information - please visit <a href="https://www.google.com.my/search?q=getting+started+with+json">this link</a> for <strong style="font-size:200%">ALL</strong> your questions and answers.</p>

				</div>

				<div class="tab-pane" id="mongodb">

					<div class="page-header top">
						<h1>MongoDB :: JSON to BSON</h1>
					</div>
					<p class="clearfix">&nbsp;</p>
					<p>Before we can dive-in and take a closer look at the other Argonauts, we first need data.</p>
					<p>In order to start adding data to <a href="http://mongodb.org">MongoDB</a> via AJAX and JSON whilst using PHP and jQuery, we will need a <code>&lt;form&gt;</code>. The source code for the " <strong>add new post</strong> " feature looks like this:</p>

<pre class="prettyprint linenums">
&lt;form class="form-fluid"&gt;
    &lt;input type="text" id="title" name="title" placeholder="Title" /&gt;
    &lt;textarea id="content" name="content" placeholder="Content"&gt;&lt;/textarea&gt;
    &lt;button type="submit" class="btn btn-info pull-right"&gt;Submit&lt;/button&gt;
&lt;/form&gt;
</pre>

					<p>And then we can apply some simple <a href="http://jquery.com">jQuery</a> to it:</p>

<pre class="prettyprint linenums">
&lt;script&gt;
$(document).ready(function(){
    $('form.form-fluid').live('submit', function(e){
        e.preventDefault();
        $.ajax({
            url: 'ajax/insert.php',
            dataType: 'JSON',
            type: 'POST',
            data: $(this).serializeArray(),
            success: function(results){
                if(results.message) alert(results.message);
            }
        });
    });
});
&lt;/script&gt;
</pre>

					<p>It's also worth noting that jQuery supports JSONp too - so assuming you have the mongoDB <a href="http://www.mongodb.org/display/DOCS/PHP+Language+Center">PHP-Drivers</a> installed, <code>ajax/insert.php</code> file accessed above could then run the following:</p>

<pre class="prettyprint linenums">
// Function for converting titles to slugs
function mb_string_to_slug($src)
{
    $src = strtolower(trim($src));
    $src = preg_replace('/[^a-z0-9-]/', '-', $src);
    $src = preg_replace('/-+/', "-", $src);
    return $src;
}

// Array for sending back progress
$progress['success'] = false;
$progress['message'] = 'Unable to add post';

// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

// Quick and dirty way to ensure slugs are unique right out of the gate
$collection->ensureIndex(array('slug'=>1), array('unique'=>true));

// Decode JSON data into PHP array
if(isset($_POST['data'])) $data = json_decode($_POST['data']);
else $data = false;

// Could dump into database as is - but want to add a little structure
if(isset($data['title'])) $title = $data['title'];
if(isset($data['content'])) $content = $data['content'];

// Check for required fields
if(!isset($title) || !isset($content)){

    $progress['message'] = 'Title and Content Required';
    $post = false;

}else{

    // Build new array to store in mongoDB
    $post = array(
        "title" => $title,
        "published" => new MongoDate(strtotime("today")),
        "slug" => mb_string_to_slug($title),
        "content" => $content
    );

}

// Try to insert data into MongoDB
$success = $collection->insert($post);
if($success) {
    $progress['success'] = true;
    $progress['message'] = 'Successfully added post';
}

// Sending progress report back as JSON keeps things simple...
// Not to mention allowing us to build up arrays with lots of information
echo json_encode($progress);
</pre>

				</div>
				
				<div class="tab-pane" id="angular">

					<div class="page-header top">
						<h1>AngularJS :: Events on Steroids</h1>
					</div>
					<p class="clearfix">&nbsp;</p>

					<p>We do not have the time to cover AngularJS events here, but at it's core, it is also a template-engine that utilizes inline data-attributes similar to Bootstrap.</p>

					<hr>

					<h3>Source Code: <a href="#" class="btn btn-small btn-info pull-right scrollto" data-scrollto="angular_source" style="margin-top:5px;">Live Demo</a></h3>
					<p>This source is the same code being used to power the blog section below...</p>
					<p style="font-weight: bold;">Remember to replace the square braces with curly ones!</p>

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
var post_count = &lt;?php echo count($results); ?&gt;;
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

					<div class="page-header top">
						<h1>Handlebars :: Minimal Templating</h1>
					</div>
					<p class="clearfix">&nbsp;</p>

					<h3>Source Code: <a href="#" class="btn btn-small btn-info pull-right scrollto" data-scrollto="handlebars_source" style="margin-top:5px;">Live Demo</a></h3>
					<p>This source is the same code being used to power the blog section below...</p>
					<p style="font-weight: bold;">Remember to replace the square braces with curly ones!</p>

<pre class="prettyprint linenums">
&#91;&#91;#posts&#93;&#93; &lt;!-- The hash starts a foreach --&gt;
    &lt;section&gt;
        &lt;h1&gt;
            &lt;a href="&#91;&#91;slug&#93;&#93;"&gt;&#91;&#91;title&#93;&#93;&lt;/a&gt;
            &lt;label class="label label-info pull-right"&gt;&#91;&#91;published&#93;&#93;&lt;/label&gt;
        &lt;/h1&gt;
        &#91;&#91;&#91;content&#93;&#93;&#93; &lt;!-- Notice three curly ones for HTML --&gt;
    &lt;/section&gt;
&#91;&#91;/posts&#93;&#93;
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

					<p id="handlebars_source">
						<br />
						The end results are as follows:</p>
					<hr>

				</div>

				<div class="tab-pane" id="mustache">

					<div class="page-header top">
						<h1>Mustache :: Swings Both Ways</h1>
					</div>
					<p class="clearfix">&nbsp;</p>
					<p>The one thing that AngularJS or Handlebars cannot offer is server-side templating - extremely important from the context of creating a blog as it means the search engines are other bot-like mechanisms that spawn the internet will have a hard time figuring out what's going on.</p>
					<p>This is why I eventually ended-up with Mustache, as it allowed me to use the same template tags within my HTML to be processed both by the server before the page is loaded, and via AJAX using JavaScript to re-process the DOM.</p>

				</div>

				<div class="tab-pane" id="admin">

					<div class="page-header top">
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
			<p>&copy; <?php echo date('Y'); ?> - JSON DOMovan <span class="pull-right"><a href="http://mongodb.my">KL MUG</a></span></p>
			<p class="clearfix">&nbsp;</p>

		</div>

	</body>
</html>