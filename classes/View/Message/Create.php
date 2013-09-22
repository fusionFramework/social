<?php defined('SYSPATH') OR die('No direct script access.');

class View_Message_Create extends Views {
	public $title = 'Messages';

	/**
	 * @var string Link to post the new message
	 */
	public $post_link = '';

	/**
	 * @var string Preset username
	 */
	public $username = '';

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
					'active' => false
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
}
