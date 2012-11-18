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