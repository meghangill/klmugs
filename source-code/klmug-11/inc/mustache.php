<div class="tab-pane" id="mustache">

	<div class="page-header top">
		<h1>Mustache :: Swings Both Ways</h1>
	</div>
	<p class="clearfix">&nbsp;</p>
	<p>The one thing that AngularJS or Handlebars cannot offer is server-side templating - extremely important from the context of creating a blog as it means the search engines and other bot-like mechanisms that spawn the internet will have a hard time figuring out what's going on.</p>
	<p>This is why I eventually ended-up with Mustache, as it allowed me to use the same template tags within my HTML to be processed both by the server before the page is loaded, and via AJAX using JavaScript to re-process the DOM.</p>

	<hr>
	
	<p>So the markup (in this case) is exactly the same as used with Handlebars:</p>
	<p>Again separated into 3 blocks to prevent parent braces from spoiling PrettyPrint styling :p</p>

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

	<p>In order to use Mustache on the server-side with PHP - we can do something like this:</p>

<pre class="prettyprint linenums">
&lt;?php
include('inc/class.mustache.php');
$m = new MustachePHP();
$template = file_get_contents('inc/template.mustache.php');
$mustache = $m->render($template, $results);
echo $mustache;
?&gt;
</pre>

	<p>The end results would get rendered on the server with plenty of SEO juice - as follows:</p>

	<hr>

	<?php echo $mustache; ?>

</div>