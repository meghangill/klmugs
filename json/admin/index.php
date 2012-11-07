<?php

function mb_string_to_slug($src)
{
	$src = strtolower(trim($src));
	$src = preg_replace('/[^a-z0-9-]/', '-', $src);
	$src = preg_replace('/-+/', "-", $src);
	return $src;
}

$p = $_POST;
$m = new Mongo();
$db = $m->selectDB('klmug_json');
$collection = new MongoCollection($db, 'posts');
$collection->ensureIndex(array('slug'=>1), array('unique'=>true));

if(isset($p['s'])){ $slug = $p['s']; }else{ $slug = 'hello-world'; }

if(isset($p['title'])){ $title = $p['title']; }else{ $title = strtotime('today'); }
if(isset($p['content'])){ $content = $p['content']; }else{ $content = false; }

$post = array(
  "title" 		=> $title,
  "published" 	=> new MongoDate(strtotime("today")),
  "slug" 		=> mb_string_to_slug($title),
  "content" 	=> $content
);

$success = false;
if($title && $content) $success = $collection->insert($post); // insert stores as array

if($success){

	$results = $collection->findOne(
			array("slug"=>mb_string_to_slug($title))
	); // FindOne Returns Array
	echo json_encode($results);
	exit;

}

?>
<!doctype html>
<html lang='en'>
<head>
<meta charset='utf-8' />
<title>JSON and MongoDB</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.0.rc.1/handlebars.min.js"></script>

<script>

	$(document).ready(function(){
		$('.ajax2').live('click', function(e){
			$.ajax({
				url: 'index.php',
				dataType: 'JSON',
				type: 'POST',
				data: {
					title: $('input#title').val(),
					content: $('textarea#content').val()
				},
				success: function(results){
					if(results.title && results.content) alert('Successfully added post!');
				}
			});
		});
	});

</script>

</head>
<body>

	<p>Basic Example of Adding Posts via AJAX:</p>

	<p>&nbsp;</p>

	<input type="text" id="title" placeholder="title of post" /><br /><br />
	<textarea id="content" placeholder="contents of post"></textarea><br /><br />

	<button class="ajax2">SAVE TO DB</button>

	<p>&nbsp;</p>
	<p>Visit <a href="../blog/">Blog</a>.</p>

</body>
</html>
