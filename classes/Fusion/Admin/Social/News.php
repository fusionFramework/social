<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * News admin
 *
 * @package    fusionFramework/social
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Fusion_Admin_Social_News extends Admin
{
	public  $resource = "news";
	public $icon = 'fa fa-bullhorn';
	public $primary_key = 'quill_topic.id';
	public $track_changes = TRUE;

	/**
	 * Set up the dataTable definition for this controller.
	 *
	 * @see Table
	 *
	 * @param Table $table
	 *
	 * @return Table A fully configured dataTable definition
	 */
	public function setup_table($table)
	{
		$table->add_column('title', array('head' => 'Title'));
		$table->add_column('user_id', array('head' => 'Poster', 'retrieve' => 'user.username'));
		$table->add_column('created_at', array('head' => 'Created'), true, false);
		$table->add_column('status', array('head' => 'Status'));

		return $table;
	}

	protected function _setup()
	{
		// We only need to see posts from the news category
		$this->model = ORM::factory('Quill_Topic')
			->where('quill_topic.category_id', '=', 1)
			->order_by('quill_topic.created_at', 'DESC');

		// a wider modal is needed for the wysiwyg
		$this->modal['width'] = 750;
	}

	public function modal(Array $data)
	{
		$form = $data['model']->get_form(['title', 'content', 'status']);
		$form->content->add_class('wysiwyg');
		$form->status->set('opts', ['active' => 'Published', 'archived' => 'Draft']);

		return $form;
	}

	/**
	 * @see Admin::save
	 *
	 * Set some defaults before saving to the database
	 */
	public function save(ORM $model, Array $data, $namespace)
	{
		$data[$namespace]['category_id'] = 1;
		$data[$namespace]['updated_at'] = 0;
		$data[$namespace]['user_id'] = Fusion::$user->id;

		return $data;
	}
}