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
		$reply_permissions = ['edit' => Fusion::$user->hasAccess('forum.reply.edit'), 'delete' => Fusion::$user->hasAccess('forum.reply.delete')];
		$replies = array();

		//only show the original post on the first page
		if(Request::$initial->param('page') == 1)
		{
			Plug::fire('forum.render', [$this->topic]);

			$replies[0] = array(
				'author' => $this->topic->user->username,
				'author_profile' => Route::url('user.profile', array('id' => $this->topic->user->id), true),
				'edit' => (($allow['topics']['edit'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id) || Fusion::$user->hasAccess('forum.topic.edit')),
				'edit_link' => Route::url('forum.topic.edit', array(
					'id' => $this->topic->category_id,
					'topic' => $this->topic->id)
				),
				'delete' => (($allow['topics']['delete'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id) || Fusion::$user->hasAccess('forum.topic.delete')),
				'delete_link' => Route::url('forum.topic.delete', array(
					'id' => $this->topic->category_id,
					'topic' => $this->topic->id)
				),
				'delete_type' => 'topic',
				'content' => $this->topic->content,
				'date' => $this->topic->created_at
			);
		}

		if(count($this->replies) > 0)
		{
			foreach($this->replies as $reply)
			{
				Plug::fire('forum.render', [$reply]);
				$replies[] = array(
					'date' => $reply->created_at,
					'author' => $reply->user->username,
					'edit' => (($allow['replies']['edit'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id) || $reply_permissions['edit']),
					'edit_link' => Route::url('forum.topic.reply.edit', array(
						'id' => $this->topic->category_id,
						'topic' => $reply->topic->id,
						'reply' => $reply->id)
					),
					'delete' => (($allow['replies']['delete'] && Fusion::$user != null && $this->topic->user->id == Fusion::$user->id) || $reply_permissions['delete']),
					'delete_link' => Route::url('forum.topic.reply.delete', array(
						'id' => $this->topic->category_id,
						'topic' => $reply->topic->id,
						'reply' => $reply->id)
					),
					'delete_type' => 'reply',
					'content' => $reply->content,
					'author_profile' => Route::url('user.profile', array('id' => $reply->user->id), true)
				);
			}
		}
		return $replies;
	}

	public function can_reply()
	{
		return (Fusion::$user != null);
	}
}
