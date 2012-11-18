<?php
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

var_dump($data); exit;

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
if(isset($post)) $success = $collection->insert($post);
if(isset($success))
{
	$progress['success'] = true;
	$progress['message'] = 'Successfully added post';
}

// Sending progress report back as JSON keeps things simple...
// Not to mention allowing us to build up arrays with lots of information
echo json_encode($progress);