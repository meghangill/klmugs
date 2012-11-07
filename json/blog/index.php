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

	$all = $collection->find();
	$results = array();
	foreach($all as $result){
		$results[] = $result;
	}
	if(isset($_POST['s'])) {
		echo json_encode($results);
		exit;
	}

}

?>
<!doctype html>
<html lang='en' ng-app>
<head>
<meta charset='utf-8' />
<title>Blog Powered by MongoDB and AngularJS</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.0.2/angular.min.js"></script>

<script>

    function AngularBlog($scope) {
		$scope.posts = [];
		<?php foreach($results as $result){ ?>

			$scope.posts.push({
				title: "<?php echo $result['title']; ?>",
				content: "<?php echo $result['content']; ?>"
			});

		<?php } ?>
    };

</script>

</head>
<body>

	<p>This example Blog is powered by MongoDB and AngularJS:</p>
	<p>&nbsp;</p>

	<div ng-controller="AngularBlog">
		<div class="entry" ng-repeat="post in posts">
			<h1>{{post.title}}</h1>
			<div ng-bind-html-unsafe="post.content"></div>
		</div>
	</div>

	<p>&nbsp;</p>
	<p>Visit <a href="../admin/">Admin</a>.</p>

</body>
</html>
