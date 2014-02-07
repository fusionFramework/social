<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Topic model with Formo support
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Fusion_Model_Quill_Topic extends Kohana_Model_Quill_Topic {

	public function filters()
	{
		return array(
			'content' => array(
				array('Security::xss_clean'),
			),
		);
	}

	use Formo_ORM;

	protected $_primary_val = 'title';

	protected $_belongs_to = array(
		'category' => array('model' => 'Quill_Category', 'foreign_key' => 'category_id', 'formo' => TRUE),
		'user' => array('model' => 'User', 'foreign_key' => 'user_id'),
		'last_user' => array('model' => 'User', 'foreign_key' => 'last_post_user_id')
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

		if($form->find('content') != null)
		{
			$form->content->set('label', 'Content')
				->set('driver', 'textarea')
				->set('attr.class', 'form-control');
		}

		if($form->find('status') != null)
		{
			$form->status->set('label', 'Status')
				->set('driver', 'radios')
				->set('opts', array(
					'active' => 'Active',
					'archived' => 'Archived',
					'deleted' => 'Deleted'
				))
				->set('attr.class', 'form-control');
		}

		if($form->find('stickied') != null)
		{
			$form->stickied->set('label', 'Stickied?')
				->set('driver', 'checkbox')
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

	/**
	 * Subscribe a user to the loaded topic.
	 *
	 * @param null $user
	 * @return ORM
	 * @throws Kohana_Exception
	 */
	public function subscribe($user=null)
	{
		if(!$this->loaded())
			Throw new Kohana_Exception('No topic loaded to subscribe to.');

		if($this->category->location->subscribe_topic != 1)
			Throw new Kohana_Exception('No one can subscribe to this category');

		// Get the logged in user if none is defined
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
			throw new Kohana_Exception('No user found to subscribe to this topic');
		}

		return ORM::factory('Quill_Subscription')
			->values(['user_id' => $user->id, 'type' => 'topic', 'type_id' => $this->id])
			->save();
	}
} // End Quill topic model
