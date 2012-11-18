<div class="tab-pane" id="handlebars">

	<div class="page-header top">
		<h1>Handlebars :: Minimal Templating</h1>
	</div>
	<p class="clearfix">&nbsp;</p>

	<h3>Source Code: <a href="#" class="btn btn-small btn-info pull-right scrollto" data-scrollto="handlebars_source" style="margin-top:5px;">Live Demo</a></h3>
	<p>This source is the same code being used to power the blog section below...</p>
	<p>Separated into 3 blocks here to prevent parent braces from spoiling PrettyPrint styling :p</p>

<pre class="prettyprint linenums">
&#91;&#91;#posts&#93;&#93; // The hash essentially starts a foreach
</pre>

<pre class="prettyprint linenums">
&lt;section&gt;
    &lt;h1&gt;
        &lt;a href="&#91;&#91;slug&#93;&#93;"&gt;&#91;&#91;title&#93;&#93;&lt;/a&gt;
        &lt;label class="label label-info pull-right"&gt;&#91;&#91;date&#93;&#93;&lt;/label&gt;
    &lt;/h1&gt;
    &#91;&#91;&#91;content&#93;&#93;&#93;
&lt;/section&gt;
</pre>

<pre class="prettyprint linenums">
&#91;&#91;/posts&#93;&#93; // The end of the foreach
</pre>

	<p style="font-weight: bold">Remember to change the square braces to curly braces!</p>

	<hr>
	
	<p>Handlebars.js gets it's data via the following PHP (in the header):</p>

<pre class="prettyprint linenums">
// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

$results = array();
$all = $collection->find();
foreach($all as $result){
    $result['date'] = date('D / M / Y', $result['published']->sec);
    $results[] = $result;
}
</pre>

	<p>Which is used with the following spaghetti-based mishmash:</p>

<pre class="prettyprint linenums">
&lt;script&gt;
var handlebars_data = new Object();
handlebars_data.posts = new Array();
&lt;?php foreach($results as $result){ ?&gt;
    handlebars_data.posts.push({
        title: "&lt;?php echo $result['title']; ?&gt;",
        content: "&lt;?php echo $result['content']; ?&gt;",
        date: "&lt;?php echo $result['date']; ?&gt;",
        slug: "&lt;?php echo $result['slug']; ?&gt;"
    });
&lt;?php } ?&gt;
var source = $("#handlebars-controller").html();
var template = Handlebars.compile(source);
var html = template(handlebars_data);
$("#handlebars-controller").html(html);
&lt;/script&gt;
</pre>


	<p id="handlebars_source">
		<br />
		The end results are as follows:</p>
	<hr>

	<?php /* */ ?>
	<div id="handlebars-controller">
		{{#posts}}
			<section>
				<div class="page-header">
					<h1>
						<?php if(count($results)>1){ ?>
							<a href="{{slug}}">
						<?php } ?>
						{{title}}
						<?php if(count($results)>1){ ?></a><?php } ?>
						<label class="label label-info pull-right">
							{{date}}
						</label>
					</h1>
				</div>
				<p class="clearfix">&nbsp;</p>
				{{{content}}}
			</section>
		{{/posts}}
	</div>
	

</div>
