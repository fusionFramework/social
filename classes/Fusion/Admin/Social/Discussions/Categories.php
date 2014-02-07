<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Discussion categories admin
 *
 * @package    fusionFramework/social
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Fusion_Admin_Social_Discussions_Categories extends Admin
{
	public  $resource = "discussions.categories";
	public $icon = 'fa fa-folder-open-o';
	public $track_changes = TRUE;
	public $filter = 'location.id';

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
		$table->add_column('description', array('head' => 'Description'));
		$table->add_column('status', array('head' => 'Status'));
		return $table;
	}

	protected function _setup()
	{
		$this->model = ORM::factory('Quill_Category');
	}

	public function modal(Array $data)
	{
		return View::factory('admin/modal/discussion_categories', $data);
	}

	/**
	 * @see Admin::save
	 *
	 * Set some defaults before saving to the database
	 */
	public function save(ORM $model, Array $data, $namespace)
	{
		$data[$namespace]['updated_at'] = 0;

		return $data;
	}
}