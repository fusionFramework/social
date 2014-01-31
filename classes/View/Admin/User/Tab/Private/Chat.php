<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Tab_Private_Chat extends View_Admin_User_Tab {
	public $button = [
		'modal_id' => 'modal-message',
		'class' => 'btn-primary',
		'icon' => 'fa fa-envelope',
		'text' => 'Send message'
	];

	public function routes()
	{
		return json_encode($this->routes, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
	}
}
