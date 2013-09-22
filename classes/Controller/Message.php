<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Message extends Controller_Fusion_Site {
	protected $_login_required = true;

	protected function _load_messages()
	{
		$this->_tpl->create_link = Route::url('message.create', null, true);
		$this->_tpl->messages = ORM::factory('Message')
			->where('sender_id', '=', Fusion::$user->id)
			->or_where('receiver_id', '=', Fusion::$user->id)
			->order_by('updated_at', 'DESC')
			->find_all();
	}

	public function action_index()
	{
		$this->_tpl = new View_Message_Index;
		Fusion::$assets->add_set('message');
		Fusion::$assets->add_set('slimScroll');
		Fusion::$assets->add_js('messages.js');

		$this->_tpl->active_message = $id = $this->request->param('id', false);

		if($id != false)
		{
			$message = ORM::factory('Message', $id);

			if(!$message->loaded())
			{
				RD::set(RD::ERROR, 'No message found.');
				$this->redirect(Route::url('message', null, true));
			}
			else if(!in_array(Fusion::$user->id, array($message->sender_id, $message->receiver_id)))
			{
				RD::set(RD::ERROR, 'You\'re not part of this discussion.');
				$this->redirect(Route::url('message', null, true));
			}

			$this->_tpl->reply_link = Route::url('message.reply', array('id' => $id), true);

			$this->_tpl->role = (Fusion::$user->id == $message->sender_id) ? 'sender' : 'receiver';

			$message->read($this->_tpl->role);

			$this->_tpl->replies = $message->posts->find_all();
		}

		$this->_load_messages();
	}

	public function action_reply()
	{
		if($this->request->method() == Request::POST)
		{
			$message = ORM::factory('Message', $this->request->param('id'));

			if(!$message->loaded())
			{
				RD::set(RD::ERROR, 'No message found.');
				$this->_tpl->active_message = false;
			}
			else if(!in_array(Fusion::$user->id, array($message->sender_id, $message->receiver_id)))
			{
				RD::set(RD::ERROR, 'You\'re not part of this discussion.');
				$this->_tpl->active_message = false;
			}

			try {
				$message->reply($_POST['content']);
				RD::set(RD::SUCCESS, 'You posted a reply successfully.');
			}
			catch(ORM_Validation_Exception $e)
			{
				$errors = $e->errors('model');

				foreach($errors as $error)
				{
					RD::set(RD::ERROR, $error);
				}
			}
		}

		$this->redirect(Route::url('message', array('id' => $this->request->param('id')), true));
	}

	public function action_create()
	{
		$this->_tpl = new View_Message_Create;

		$this->_load_messages();

		$this->_tpl->post_link = Route::url('message.post', null, true);
		$this->_tpl->username = $this->request->param('user', '');
	}

	public function action_post()
	{
		if( $_POST['username'] == Fusion::$user->username)
		{
			RD::set(RD::ERROR, 'No need to feel lonely.');
			$this->redirect(Route::url('message.create', null, true));
		}

		$receiver = ORM::factory('User')
			->where('username', '=', $_POST['username'])
			->find();

		if( !$receiver->loaded())
		{
			RD::set(RD::ERROR, 'I don\'t know who you\'re sending this mail to.');
			$this->redirect(Route::url('message.create', null, true));
		}

		try {
			$message = ORM::factory('Message')
				->post($_POST['content'], $receiver);

			RD::set(RD::SUCCESS, 'Your mail was sent.');
			$this->redirect(Route::url('message', array('id' => $message->id), true));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('model');

			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
	}
} // End Message
