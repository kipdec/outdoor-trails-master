<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use AbqOutdoorTrails\AbqBike\{ User, Route, Comment};

/**
 * api for the Comment class
 *
 * @author wharris21@cnm.edu
 **/

// verify the session, start if inactive
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	// grab the MySQL connection
	$secrets = new \Secrets("etc/apache2/capstone-mysql/abqbiketrails");
	$pdo = $secrets->getPdoObject();

	// determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentRouteId = filter_input(INPUT_GET, "commentRouteId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentUserId = filter_input(INPUT_GET, "commentUserId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentContent = filter_input(INPUT_GET, "commentContent", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$commentDate = filter_input(INPUT_GET, "commentDate", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id))) {
		throw(new \InvalidArgumentException("id cannot be empty or negative", 405));
	}

	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie():

		// get a specific comment based on arguments provided or all the comments by route id and update reply
		if(empty($id) === false) {
			$reply->data = Comment::getCommentByCommentId($pdo, $id);
		} else if(empty($commentRouteId) === false) {
			$reply->data = Comment::getCommentsByRouteId($pdo, $commentRouteId)->toArray();
		}
	} else if($method === "POST") {
		// enforce the user has a XSRF token
		verifyXsrf();

		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestContent = file_get_contents("php://input");

		// This decodes the JSON package and stores that result in $requestObject
		$requestObject = json_decode($requestContent);

		// make sure comment content is available (required field)
		if(empty($requestObject->commentContent) === true) {
			throw(new \InvalidArgumentException("No content for Comment.", 405));
		}

		// make sure comment date is accurate (required field)
		if(empty($requestObject->commentDate) === true) {
			throw(new \InvalidArgumentException("No date set for Comment.", 405));
		} else {
			// if the date exists, milliseconds since the beginning of time must be converted
			$commentDate = DateTime::createFromFormat("U.u", $requestObject->commentDate / 1000);
			if($commentDate === false) {
				throw(new RuntimeException("Invalid Comment date", 400));
			}
			$requestObject->commentDate = $commentDate;
		}

		// perform the actual post
		if($method === "POST") {
			// enforce the user is signed in
			if(empty($_SESSION["user"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to post comments", 403));
			}

			// create new Comment and insert into the database
			$comment = new Comment(generateUuidV4(), $_SESSION["route"]->getRouteId, $_SESSION["user"]->getUserId, $requestObject->commentContent, null);
			$comment->insert($pdo);

			// update reply
			$reply->message = "Comment created OK";
		}
	}
}