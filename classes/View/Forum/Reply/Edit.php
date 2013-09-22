<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Reply_Edit extends View_Forum {
	/**
	 * @var string URL to submit the form to
	 */
	public $submit_link = '';

	/**
	 * @var Model_Quill_Reply
	 */
	public $reply = null;
}
