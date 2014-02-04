<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Reply model with Formo support
 *
 * @package    fusionFramework/social
 * @category   Model
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Model_Quill_Reply extends Kohana_Model_Quill_Reply {

	public function filters()
	{
		return array(
			'content' => array(
				array('Security::xss_clean'),
			),
		);
	}

	use Formo_ORM;

	/**
	 * Define form fields based on model properties.
	 *
	 * @param Formo $form
	 */
	public function formo(Formo $form)
	{
		if($form->find('content') != null)
		{
			$form->content->set('label', 'Content')
				->set('driver', 'textarea')
				->set('attr.class', 'form-control');
		}

		if($form->find('status') != null)
		{
			$form->status->set('label', 'Status')
				->set('driver', 'radio')
				->set('attr.opts', array(
				'active' => 'Active',
				'deleted' => 'Deleted'
			))
				->set('attr.class', 'form-control');
		}
	}

} // End Quill reply model
