<?php if($url != ''){ ?>
	<div class="tab-pane" id="json">
<?php }else{ ?>
	<div class="tab-pane active" id="json">
<?php } ?>

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