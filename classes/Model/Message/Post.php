<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Message_Post model
 *
 * @package
 * @author     Maxim Kerstens 'happyDemon'
 * @copyright  (c) 2013 Maxim Kerstens
 * @license    MIT
 */
class Model_Message_Post extends ORM {

	// Table specification
	protected $_table_name = 'message_replies';

	protected $_table_columns = array(
		'id' => null,
		'message_topic_id' => null,
		'sender_id' => null,
		'created_at' => null,
		'read_at' => null,
		'content' => null,
	);

	protected $_belongs_to = array(
		'message' => array('model' => 'Message', 'foreign_key' => 'message_topic_id'),
		'sender' => array('model' => 'User', 'foreign_key' => 'sender_id'),
	);

	protected $_created_column = array('column' => 'created_at', 'format' => true);
	protected $_sorting = array('created_at' => 'DESC');
	protected $_load_with = array('sender');

	public function rules()
	{
		return array(
			'sender_id' => array(
				array('not_empty')
			),
			'content' => array(
				array('not_empty')
			),
		);
	}

} // End Message_Post model
