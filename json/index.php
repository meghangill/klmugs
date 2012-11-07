<?php

$p = $_POST;
$m = new Mongo();
$db = $m->selectDB('klmug_json');
$collection = new MongoCollection($db, 'posts');
$collection->ensureIndex(array('slug'=>1), array('unique'=>true));

if(isset($p['s'])){ $slug = $p['s']; }else{ $slug = 'hello-world'; }

$post = array(
  "title" 		=> "Hello World",
  "published" 	=> new MongoDate(strtotime("today")),
  "slug" 		=> $slug,
  "content" 	=> "<p>Hello <a href='#'>JSON</a> World</p>",
  "tags" 		=> array("categories","test","etc")
);

$success = $collection->insert($post); // insert stores as array

if($success){

	$results = $collection->findOne(
			array("slug"=>'hello-world')
	); // FindOne Returns Array
	if(isset($_POST['s'])) {
		echo json_encode($results);
		exit;
	}

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
		$('.ajax1').live('click', function(e){
			$.ajax({
				url: 'index.php',
				dataType: 'JSON',
				type: 'POST',
				data: {
					s: 'hello-world'
				},
				success: function(results){
					var source		= $("#handlebar-content").html();
					var template	= Handlebars.compile(source);
					var html		= template(results);
					$('#handlebar-content').html(html);
					$('#handlebar-content').find('p.temp span').remove();
				}
			});
		});
	});

</script>

</head>
<body>

	<p>Default Content Added to DB when first opening this page:</p>
	<?php if(!isset($_POST['s'])) var_dump($results); ?>

	<p>&nbsp;</p>

	<button class="ajax1">Get Hello-World Default Content via AJAX and process template using Handlebars.js</button>

	<p>&nbsp;</p>

	<div id="handlebar-content">
		<div class="entry">
			<p class="temp">This is what <span>un-</span>processed Handlebars syntax looks like:</p>
			<h1>{{title}}</h1>
			{{{content}}}
		</div>
	</div>

	<p>&nbsp;</p>
	<p>Goto <a href="admin/">Admin</a> to get started with AngularJS.</p>

</body>
</html>
