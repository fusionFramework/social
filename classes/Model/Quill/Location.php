<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Location model with Formo support
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Quill_Location extends Kohana_Model_Quill_Location {
	use Formo_ORM;

	protected $_primary_val = 'name';

	protected $_table_columns = array(
		'id' => null,
		'name' => null,
		'description' => null,
		'count_topics' => null,
		'record_last_topic' => null,
		'stickies' => null,
		'count_replies' => null,
		'record_last_post' => null,
		'count_views' => null,
		'subscribe_topic' => null,
		'subscribe_category' => null
	);

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		$form->add('name')->name->set('label', 'Shortcut')
			->set('driver', 'input')
			->set('attr.class', 'form-control');

		$form->add('description')->description->set('label', 'Description')
			->set('driver', 'textarea')
			->set('attr.class', 'form-control');

		$form->add('count_topics')->count_topics->set('label', 'Count topics?')
			->set('driver', 'checkbox')
			->set('message', 'Should we keep track of the amount of topics posted?')
			->set('value', 1);

		$form->add('record_last_topic')->record_last_topic->set('label', 'Record last topic?')
			->set('driver', 'checkbox')
			->set('message', 'Will you show the last posted topic in categories?')
			->set('value', 1);

		$form->add('stickies')->stickies->set('label', 'Sticky support?')
			->set('driver', 'checkbox')
			->set('message', 'Is staff able to sticky topics?')
			->set('value', 1);

		$form->add('count_replies')->count_replies->set('label', 'Count replies?')
			->set('driver', 'checkbox')
			->set('message', 'Should we keep track of the amount of replies posted per topic?')
			->set('value', 1);

		$form->add('count_views')->count_views->set('label', 'Count topic views?')
			->set('driver', 'checkbox')
			->set('message', 'Should we keep track of the amount of views a topic has?')
			->set('value', 1);

		$form->add('record_last_post')->record_last_post->set('label', 'Record last post?')
			->set('driver', 'checkbox')
			->set('message', 'Should we keep track of the user who posted last to a topic?')
			->set('value', 1);

		$form->add('subscribe_topic')->subscribe_topic->set('label', 'Can users subscribe to topics?')
			->set('driver', 'checkbox')
			->set('value', 0);

		$form->add('subscribe_category')->subscribe_category->set('label', 'Can users subscribe to categories?')
			->set('driver', 'checkbox')
			->set('value', 0);
	}

	/**
	 * Used to represent in belongs_to relations when changes are tracked
	 * @return bool|string
	 */
	public function candidate_key()
	{
		if (!$this->loaded()) return FALSE;
		return $this->name;
	}
} // End Quill Location model
