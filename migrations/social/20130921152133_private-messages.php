<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * private messages
 */
class Migration_Social_20130921152133 extends Minion_Migration_Base {

	/**
	 * Run queries needed to apply this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function up(Kohana_Database $db)
	{
		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `message_topics` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `subject` varchar(60) NOT NULL,
		  `unread_receiver` int(2) unsigned NOT NULL,
		  `unread_sender` int(2) unsigned NOT NULL,
		  `receiver_id` int(10) unsigned NOT NULL,
		  `sender_id` int(10) unsigned NOT NULL,
		  `updated_at` int(10) unsigned NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `message_receiver` (`receiver_id`),
		  KEY `message_sender` (`sender_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		$db->query(NULL, "ALTER TABLE `message_topics`
	   	  ADD CONSTRAINT `message_topics_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
	   	  ADD CONSTRAINT `message_topics_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;");

		$db->query(NULL, "CREATE TABLE IF NOT EXISTS `message_replies` (
		  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `message_topic_id` int(10) unsigned NOT NULL,
		  `sender_id` int(10) unsigned NOT NULL,
		  `created_at` int(11) NOT NULL,
		  `read_at` int(11) NOT NULL,
		  `content` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `message_topic` (`message_topic_id`),
		  KEY `message_reply_sender` (`sender_id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");

		$db->query(NULL, "ALTER TABLE `message_replies`
	   	  ADD CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
	   	  ADD CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`message_topic_id`) REFERENCES `message_topics` (`id`) ON DELETE CASCADE;");
	}

	/**
	 * Run queries needed to remove this migration
	 *
	 * @param Kohana_Database $db Database connection
	 */
	public function down(Kohana_Database $db)
	{
		$db->query(NULL, 'DROP TABLE `message_topics`;');
		$db->query(NULL, 'DROP TABLE `message_replies`;');
	}

}
