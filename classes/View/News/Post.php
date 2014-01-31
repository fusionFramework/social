<?php defined('SYSPATH') OR die('No direct script access.');

class View_News_Post extends Views {
	public $title = 'News';

	/**
	 * @var array Contains loaded news post
	 */
	public $post = array();

	/**
	 * @var array Contains loaded replies
	 */
	public $replies = array();

	/**
	 * @var string Link to submit a new comment to
	 */
	public $comment_link = '';

	/**
	 * @var string Contains parsed pagination template
	 */
	public $pagination = '';

	/**
	 * Standardise posts
	 *
	 * @return array
	 */
	public function replies()
	{
		$replies = array();

		if(count($this->replies) > 0)
		{
			foreach($this->replies as $reply)
			{
				Plug::listen('news.render', [$reply]);

				$replies[] = array(
					'date' => $reply->created_at,
					'content' => nl2br($reply->content),
					'owned' => (Fusion::$user != null && $reply->user->id == Fusion::$user->id),
					'user' => $reply->user->username,
					'link' => Route::url('user.profile', array('id' => $reply->user->id), true),
					'id' => $reply->id
				);
			}
		}
		return $replies;
	}
}
