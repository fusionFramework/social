<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Message model
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Fusion_Model_Message extends ORM {

	// Table specification
	protected $_table_name = 'message_topics';

	protected $_table_columns = array(
		'id' => null,
		'receiver_id' => null,
		'sender_id' => null,
		'updated_at' => null,
		'unread_receiver' => null,
		'unread_sender' => null,
	);

	// Relationships
	protected $_has_many = array(
		'posts' => array('model' => 'Message_Post', 'foreign_key' => 'message_topic_id'),
	);

	protected $_belongs_to = array(
		'sender' => array('model' => 'User', 'foreign_key' => 'sender_id'),
		'receiver' => array('model' => 'User', 'foreign_key' => 'receiver_id'),
	);

	protected $_updated_column = array('column' => 'updated_at', 'format' => true);

	public function rules()
	{
		return array(
			'sender_id' => array(
				array('not_empty')
			),
			'receiver_id' => array(
				array('not_empty')
			),
		);
	}

	/**
	 * Create a new message.
	 *
	 * @param            $content
	 * @param Model_User $receiver
	 * @param Model_User $sender
	 *
	 * @return Model_Message
	 */
	public function post($content, Model_User $receiver, Model_User $sender=null)
	{
		if($sender == null)
		{
			$sender = Fusion::$user;
		}

		$values = array(
			'sender_id' => $sender->id,
			'receiver_id' => $receiver->id,
			'unread_receiver' => 1
		);

		$this->reset();
		$this->values($values);
		$this->save();

		$this->add('posts', ORM::factory('Message_Post')
				->values(array(
					'sender_id' => $sender->id,
					'content' => $content
				)
			)
		);

		// Log the fact that a user created a new message and notify the receiver
		Fusion::$log->create('message.create', 'social', '<b>:username</b> created a new discussion to <i>:other_username</i>', array(
			':user' => $sender->id,
			':username' => $sender->username,
			':other_username' => $receiver->username,
			':other_user_id' => $receiver->id,
		))
		->notify($receiver, 'mail.message', ['message_id' => $this->id]);

		return $this;
	}

	/**
	 * Read a message.
	 *
	 * @param string $role
	 * @return Model_Message
	 */
	public function read($role='sender')
	{
		if($this->get('unread_'.$role) > 0)
		{
			// update the unread replies
			DB::update(array('message_replies', 'message_post'))
				->set(array('read_at' => time()))
				->where('read_at', '=', 0)
				->where('sender_id', '!=', Fusion::$user->id)
				->where('message_topic_id', '=', $this->id)
				->execute();

			// set unread to 0
			$this->set('unread_'.$role, 0);

			$this->save();
		}

		return $this;
	}

	/**
	 * Reply to a message.
	 *
	 * @param      $content
	 * @param null $sender
	 */
	public function reply($content, $sender=null)
	{
		if($sender == null)
		{
			$sender = Fusion::$user;
		}

		$post = ORM::factory('Message_Post')
			->values(array(
			'sender_id' => $sender->id,
			'content' => $content,
			'message_topic_id' => $this->id
		))
		->save();

		$role = ($this->sender_id == $sender->id) ? 'sender' : 'receiver';
		$other_user_role = ($this->sender_id != $sender->id) ? 'sender' : 'receiver';

		$this->set('unread_'.$role, $this->get('unread_'.$role)+1);
		$this->save();

		// Log the fact that a user created a new reply and notify the receiver
		Fusion::$log->create('message.reply', 'social', '<b>:username</b> replied in a discussion to <i>:other_username</i>', array(
			':message_id' => $post->id,
			':user' => $sender->id,
			':username' => $sender->username,
			':other_username' => $this->{$other_user_role}->username,
			':other_user_id' => $this->{$other_user_role}->id,
		))
		->notify($this->{$other_user_role}, 'mail.reply');
	}

} // End Message model
