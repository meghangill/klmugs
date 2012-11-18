<?php

// Check for slugs
$uri = $_SERVER['REQUEST_URI'];
$uris = explode('/', $uri);
$url = array_pop($uris);

// Assign simplicity
$p = $_POST;

// Connect to MongoDB
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

// Get results
$results = array();
if($url != '')
{
	$results[] = $collection->findOne(array('slug'=>$url));
	$results[0]['date'] = date('D / M / Y', $results[0]['published']->sec);
}
else
{
	$all = $collection->find();
	foreach($all as $result){
		$result['date'] = date('D / M / Y', $result['published']->sec);
		$results[] = $result;
	}
};

$data['posts'] = $results;

// Extra goodness required for server-side Mustache
include('inc/class.mustache.php');
$m = new MustachePHP();
$template = file_get_contents('inc/templates.mustache.php');
$mustache = $m->render($template, $data);

// Time to build the page
// Also keeps things tidy
include('inc/header.php');
echo '<div class="container-narrow">';
	echo '<div class="tab-content" style="overflow:inherit;">';
		include('inc/page.php');
		include('inc/json.php');
		include('inc/mongodb.php');
		include('inc/handlebars.php');
		include('inc/mustache.php');
		include('inc/admin.php');
		include('inc/angular.php');
	echo '</div>';
echo '</div>';
include('inc/footer.php');
echo '<script src="js/angular.js"></script>';