<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Quill table filler
 */
class Migration_Social_20131121152133 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "INSERT INTO `quill_locations` (`id`, `name`, `description`, `count_topics`, `record_last_topic`, `stickies`, `count_replies`, `count_views`, `record_last_post`) VALUES
			(2, 'forum-general', 'General site forum', 1, 1, 1, 1, 0, 1),
			(3, 'forum-marketplace', 'Sell services ''n stuff', 1, 1, 1, 1, 0, 1),
			(1, 'site', 'Site-related discussions', 1, 1, 1, 1, 0, 1);");

		$db->query(NULL, "INSERT INTO `quill_categories` (`id`, `location_id`, `title`, `description`, `status`, `topic_count`) VALUES
			(1, 1, 'news', 'Site news', 'open', 0),
			(2, 2, 'Chat', 'General site chat', 'open', 1),
			(3, 3, 'Deals', 'Promote deals you have in your shop or trades', 'open', 1);");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DELETE FROM `quill_locations` WHERE id IN(1,2,3);');
		$db->query(NULL, 'DELETE FROM `quill_categories` WHERE id IN(1,2,3);');
	}

}
