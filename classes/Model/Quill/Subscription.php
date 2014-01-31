<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Category model
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Quill_Subscription extends ORM {

	protected $_belongs_to = array(
		'user' => array('model' => 'User', 'foreign_key' => 'user_id'),
	);


	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->type;
	}

	public function where($key, $op, $value)
	{
		if($key == 'id')
			$key = 'quill_category.id';

		return parent::where($key, $op, $value);
	}
} // End Quill subscription model
