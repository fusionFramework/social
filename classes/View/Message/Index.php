<?php defined('SYSPATH') OR die('No direct script access.');

class View_Message_Index extends Views {
	public $title = 'Messages';

	/**
	 * @var string Link to create a new message
	 */
	public $create_link = '';

	/**
	 * @var array List of sent or received messages
	 */
	public $messages = array();

	/**
	 * Simplify the messages' format for the view
	 *
	 * @return array
	 */
	public function messages()
	{
		$msgs = array();

		if(count($this->messages) > 0)
		{
			foreach($this->messages as $msg)
			{
				$type = ($msg->sender_id == Fusion::$user->id) ? 'sender' : 'receiver';

				$format = array(
					'title' => $msg->{$type}->username,
					'link' => Route::url('message', array('id' => $msg->id), true),
					'unread' => ($msg->get('unread_'.$type) > 0) ? array('total' => $msg->get('unread_'.$type)) : false,
					'active' => ($msg->id == $this->active_message)
				);

				//check if the last sent post was sent by the logged in user and read by the receiver
				if($format['unread'] == false)
				{
					$last_post = $msg->posts->order_by('created_at', 'DESC')->find();
					$format['read'] = ($last_post->sender_id == Fusion::$user->id &&  !in_array($last_post->read_at, array('', '0')));
				}

				$msgs[] = $format;
			}
		}
		return $msgs;
	}

	/**
	 * @var integer|null The id of the message that's currently being read
	 */
	public $active_message = false;

	/**
	 * @var string Link to submit new replies to
	 */
	public $reply_link = '';

	/**
	 * @var array A list of replies in the active message
	 */
	public $replies = array();

	/**
	 * @var string The role the currently logged in user has in this discussion
	 */
	public $role = '';

	public function replies()
	{
		$replies = array();

		if($this->active_message != false)
		{
			foreach($this->replies as $reply)
			{
				$replies[] = array(
					'avatar' => $reply->sender->avatar(),
					'profile_link' => Route::url('user.profile', array('id' => $reply->sender_id)),
					'username' => $reply->sender->username,
					'time' => $reply->created_at,
					'formatted_time' => Fusion::date($reply->created_at),
					'content' => nl2br($reply->content),
					'orientation' => ($this->role == 'sender' && $reply->sender_id == Fusion::$user->id) ? 'left' : 'right'
				);
			}
		}

		return $replies;
	}
}
