<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Add topic/category subscriptions
 */
class Migration_Social_20140105152133 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "ALTER TABLE  `quill_locations` ADD  `subscribe_topic` BOOLEAN NOT NULL ,
			ADD  `subscribe_category` BOOLEAN NOT NULL");

		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `quill_subscriptions` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `type` enum('topic','category') NOT NULL,
			  `type_id` int(10) unsigned NOT NULL,
			  `user_id` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'ALTER TABLE  `quill_locations` REMOVE  `subscribe_topic` BOOLEAN NOT NULL ,
			REMOVE  `subscribe_category` BOOLEAN NOT NULL');
		$db->query(NULL, 'DROP TABLE `quill_subscriptions`;');
	}

}
