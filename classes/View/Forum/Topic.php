<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Topic extends View_Forum {

	/**
	 * @var array Contains related replies
	 */
	public $replies = array();

	/**
	 * @var string Contains parsed pagination template
	 */
	public $pagination = '';

	/**
	 * @var string Link to create a reply
	 */
	public $create_reply = '';

	/**
	 * Standardise replies
	 *
	 * @return array
	 */
	public function replies()
	{
		$allow = Kohana::$config->load('forums.allow_owner');
		$replies = array();

		//only show the original post on the first page
		if(Request::$initial->param('page') == 1)
		{
			$replies[0] = array(
				'author' => $this->topic->user->username,
				'author_profile' => Route::url('user.profile', array('id' => $this->topic->user->id), true),
				'edit' => ($allow['topics']['edit'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id),
				'edit_link' => Route::url('forum.topic.edit', array(
					'id' => $this->topic->category_id,
					'topic' => $this->topic->id)
				),
				'delete' => ($allow['topics']['delete'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id),
				'delete_link' => Route::url('forum.topic.delete', array(
					'id' => $this->topic->category_id,
					'topic' => $this->topic->id)
				),
				'delete_type' => 'topic',
				'content' => nl2br($this->topic->content),
				'date' => $this->topic->created_at
			);
		}

		if(count($this->replies) > 0)
		{
			foreach($this->replies as $reply)
			{
				$replies[] = array(
					'date' => $reply->created_at,
					'author' => $reply->user->username,
					'edit' => ($allow['replies']['edit'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id),
					'edit_link' => Route::url('forum.topic.reply.edit', array(
						'id' => $this->topic->category_id,
						'topic' => $reply->topic->id,
						'reply' => $reply->id)
					),
					'delete' => ($allow['replies']['delete'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id),
					'delete_link' => Route::url('forum.topic.reply.delete', array(
						'id' => $this->topic->category_id,
						'topic' => $reply->topic->id,
						'reply' => $reply->id)
					),
					'delete_type' => 'reply',
					'content' => nl2br($reply->content),
					'author_profile' => Route::url('user.profile', array('id' => $reply->user->id), true)
				);
			}
		}
		return $replies;
	}
}
