<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Renders Quill posts
 *
 * @package fusionFramework/social
 * @category Plugin
 */
class Rendr extends Plugin
{
	/**
	 * @var array Plugin meta
	 */
	public $info = array(
		'name'        => 'Social.rendr',
		'description' => 'Takes care of rendering forum topics, replies, private chat messages, news posts and replies.',
		'author'      => 'Maxim Kerstens',
		'author_URL'  => 'http://happydemon.org'
	);

	/**
	 * Run when initialising the plugin when it's active.
	 */
	protected function _init() {
		return true;
	}

	protected $_events = array('forum.render', 'msg.render', 'news.render');

	/**
	 * We'll do a simple nl2br on the topic or reply's content
	 */
	public function on_forum_render($record) {
		$record->content = nl2br($record->content);
	}

	/**
	 * We'll do a simple nl2br on the message's reply content
	 */
	public function on_msg_render(Model_Message_Post $record) {
		$record->content = nl2br($record->content);
	}

	/**
	 * We'll do a simple nl2br on the news's content
	 */
	public function on_news_render($record) {
		$record->content = nl2br($record->content);
	}
}
