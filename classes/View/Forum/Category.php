<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Category extends View_Forum {

	/**
	 * @var array Contains loaded news post
	 */
	public $topics = array();

	/**
	 * @var string Contains parsed pagination template
	 */
	public $pagination = '';

	/**
	 * @var string Link to create a topic
	 */
	public $create_topic = '';

	/**
	 * Standardise posts
	 *
	 * @return array
	 */
	public function topics()
	{
		$topics = array();

		if(count($this->topics) > 0)
		{
			foreach($this->topics as $topic)
			{
				$topics[] = array(
					'date' => $topic->updated_at,
					'can_reply' => (Fusion::$user != null && $topic->status == 'active'),
					'title' => $topic->title,
					'user' => $topic->last_user->username,
					'author' => $topic->user->username,
					'link' => Route::url('forum.topic', array('id' => $topic->category->id, 'topic' => $topic->id), true),
					'id' => $topic->id,
					'sticky' => $topic->stickied,
					'archived' => ($topic->status != 'active'),
					'replies' => $topic->reply_count
				);
			}
		}
		return $topics;
	}
}
