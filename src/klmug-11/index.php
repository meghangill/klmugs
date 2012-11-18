<?php

$uri = $_SERVER['REQUEST_URI'];
$uris = explode('/', $uri);
$url = array_pop($uris);

$p = $_POST;
$m = new Mongo();
$db = $m->selectDB('argonauts');
$collection = new MongoCollection($db, 'posts');

$results = array();
if($url != '') $results[] = $collection->findOne(array('slug'=>$url));
else {
	$all = $collection->find();
	foreach($all as $result){
		$results[] = $result;
	}
};

// Time to build the page
// Also keeps things tidy
include('inc/header.php');
echo '<div class="container-narrow">';
	echo '<div class="tab-content" style="overflow:inherit;">';
		include('inc/json.php');
		include('inc/mongodb.php');
		include('inc/angular.php');
		include('inc/handlebars.php');
		include('inc/mustache.php');
		include('inc/admin.php');
	echo '</div>';
echo '</div>';
