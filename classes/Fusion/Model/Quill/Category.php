<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Category model
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Fusion_Model_Quill_Category extends Kohana_Model_Quill_Category {
	use Formo_ORM;

	protected $_primary_val = 'title';

	// Include the location relation in formo
	protected $_belongs_to = array(
		'location' => array('model' => 'Quill_Location', 'foreign_key' => 'location_id', 'formo' => TRUE),
	);

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		if($form->find('title') != null)
		{
			$form->title->set('label', 'Title')
				->set('driver', 'input')
				->set('attr.class', 'form-control');
		}

		if($form->find('description') != null)
		{
			$form->description->set('label', 'Description')
				->set('driver', 'textarea')
				->set('attr.class', 'form-control');
		}

		if($form->find('status') != null)
		{
			$form->status->set('label', 'Status')
				->set('driver', 'radios')
				->set('opts', array (
					'open' => 'Open',
					'closed' => 'Closed'
				))
				->set('attr.class', 'form-control');
		}
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->title;
	}

	public function where($key, $op, $value)
	{
		if($key == 'id')
			$key = 'quill_category.id';

		return parent::where($key, $op, $value);
	}

	/**
	 * Subscribe a user to the loaded category.
	 *
	 * @param null $user
	 * @return ORM
	 * @throws Kohana_Exception
	 */
	public function subscribe($user=null)
	{
		if(!$this->loaded())
			Throw new Kohana_Exception('No category loaded to subscribe to.');

		if($this->location->subscribe_category != 1)
			Throw new Kohana_Exception('No one can subscribe to this category');

		if($user == null)
		{
			$user = Fusion::$user;
		}
		else if(Valid::digit($user))
		{
			$user = ORM::factory('User', $user);
		}

		if(!is_a($user, 'Model_User') || !$user->loaded())
		{
			throw new Kohana_Exception('No user found to subscribe to this category');
		}

		return ORM::factory('Quill_Subscription')
			->values(['user_id' => $user->id, 'type' => 'category', 'type_id' => $this->id])
			->save();
	}
} // End Quill category model
