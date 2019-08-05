<?php
namespace AbqOutdoorTrails\AbqBike\Test;

use AbqOutdoorTrails\AbqBike\{User, Route};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * Full PHPUnit test for the Comment class.  Tests *ALL* MySQL/PDO enabled methods for both invalid and valid inputs
 *
 * @see Comment
 * @author wharris21@cmm.edu
 **/
class CommentTest extends AbqBikeTest {
	/**
	 * User that created the Comment; this is for foreign key relations
	 *
	 * @var User user
	 **/
	protected $user = null;

	/**
	 * Route the Comment is on; this is for foreign key relations
	 *
	 * @var Route route
	 **/
	protected $route = null;

	/**
	 * valid user hash to create the User object to own the test
	 *
	 * @var Uuid VALID_USER_HASH
	 **/
	protected $VALID_USER_HASH;

	/**
	 * content of the Comment
	 *
	 * @var string VALID_COMMENTCONTENT
	 **/
	protected $VALID_COMMENTCONTENT = "PHPUnit test passing?!?";

	/**
	 * timestamp of the comment; this starts as null and is assigned later
	 *
	 * @var \DateTime VALID_COMMENTDATE
	 **/
	protected $VALID_COMMENTDATE = null;
}