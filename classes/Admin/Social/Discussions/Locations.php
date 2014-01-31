<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * Discussions locations admin
 *
 * @package    fusionFramework/social
 * @category   Admin
 * @author     Maxim Kerstens
 * @copyright  (c) happydemon.org
 */
class Admin_Social_Discussions_Locations extends Admin
{
	public  $resource = "discussions.locations";
	public $icon = 'fa fa-folder-o';
	public $primary_key = 'id';
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
		$table->add_column('name', array('head' => 'Shortcut'));
		$table->add_column('description', array('head' => 'Description'));

		$table->add_button('categories', 'fa-bars', array('class' => 'primary', 'title' => 'Categories'), 'after', 'edit');

		return $table;
	}

	protected function _setup()
	{
		$this->model = ORM::factory('Quill_Location');
		$this->_assets['js'][] = 'admin/discussion_locations.js';
	}

	public function modal(Array $data)
	{
		return View::factory('admin/modal/discussion_locations', $data);
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