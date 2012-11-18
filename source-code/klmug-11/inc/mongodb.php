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
&lt;/script&gt;
</pre>

	<p>It's also worth noting that jQuery supports JSONp too - so assuming you have the mongoDB <a href="http://www.mongodb.org/display/DOCS/PHP+Language+Center">PHP-Drivers</a> installed, <code>ajax/insert.php</code> file accessed above could then run the following:</p>

<pre class="prettyprint linenums">
&lt;?php
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

// Create an array from the JSON data
$fields = false;
if(is_array($data)){
    foreach($data as $field){
        $fields[$field->name] = $field->value;
    }
}

// Could dump into database as is - but want to add a little structure
if(isset($data['title'])) $title = $data['title'];
if(isset($data['content'])) $content = $data['content'];

// Check for required fields
if(!isset($title) || !isset($content)){

    $progress['message'] = 'Title and Content Required';

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
if($success)
{
    $progress['success'] = true;
    $progress['message'] = 'Successfully added post';
}

// Sending progress report back as JSON keeps things simple...
// Not to mention allowing us to build up arrays with lots of information
echo json_encode($progress);
?>
</pre>

</div>