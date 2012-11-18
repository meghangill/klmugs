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