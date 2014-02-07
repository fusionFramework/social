<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Forum base View
 *
 * @package    fusionFramework/social
 * @category   View
 * @author     Maxim Kerstens
 * @copyright  (c) Maxim Kerstens
 */
class Fusion_View_Forum extends Views
{
	public $title = 'Forum';

	/**
	 * @var Model_Quill_Topic Contains loaded topic
	 */
	public $topic = null;

	/**
	 * @var Model_Quill_Category Contains loaded topic
	 */
	public $category = null;

	/**
	 * @var string Rendered breadcrumb element
	 */
	public $breadcrumb = null;
}
