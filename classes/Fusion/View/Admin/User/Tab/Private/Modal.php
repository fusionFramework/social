<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Tab_Private_Modal {
	public $player_id = null;

	public $pages = 0;

	public $next_page = false;

	public $messages = [];

	public function has_next_page()
	{
		return ($this->next_page != false);
	}


	public function messages()
	{
		$list = [];

		foreach($this->messages as $msg)
		{
			//load the log accompanying the msg
			$log = ORM::factory('Log')
				->where('user_id', '=', $msg->sender_id)
				->where('alias_id', '=', $msg->id)
				->where('alias', '=', 'message.reply')
				->where('type', '=', 'social')
				->find();

			$log_exists = ($log->loaded());

			$list[] = [
				'sender' => ($msg->sender_id == $this->player_id),
				'content' => $msg->content,
				'date' => Fusion::date($msg->created_at),
				'read' => ($msg->read_at != 0),
				'has_log' => $log_exists,
				'log' => ($log_exists) ? $log->as_array() : false
			];
		}

		return $list;
	}
}
