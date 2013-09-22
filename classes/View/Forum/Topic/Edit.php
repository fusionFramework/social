<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic_Edit extends View_Forum {
	/**
	 * @var string URL to submit the form to
	 */
	public $submit_link = '';

	/**
	 * @var bool Can the currently logged in user sticky posts?
	 */
	public $can_sticky = false;

	public $options = array();
}
