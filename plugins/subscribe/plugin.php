<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Subscribe plugin, handles quill subscriptions
 *
 * @package fusionFramework/social
 * @category Plugin
 */
class Subscribe extends Plugin
{
	/**
	 * @var array Plugin meta
	 */
	public $info = array(
		'name'        => 'Social.subscribe',
		'description' => 'Handles Quill subscriptions to topics (new replies) and categories (new topics).',
		'author'      => 'Maxim Kerstens',
		'author_URL'  => 'http://happydemon.org'
	);

	/**
	 * Run when initialising the plugin when it's active.
	 */
	protected function _init() {
		return true;
	}

	protected $_events = array('quill.topic', 'quill.reply');

	/**
	 * Send out notifications to users that are subscribed to the topic's category
	 */
	public function on_quill_topic(Model_Quill_Category $category, Model_Quill_Topic $topic, Model_User $user) {
		if($category->location->subscribe_category == 1)
		{
			//check if users are subscribed to this category
			$sub = ORM::factory('Quill_Subscription')
				->where('type', '=', 'category')
				->where('type_id', '=', $category->id)
				->find_all();

			if(count($sub) > 0)
			{
				//check if the location has its own notification
				$notify = ORM::factory('Notification')
					->where('alias', '=', 'quill.'.$category->location->name.'.topic')
					->find();

				if(!$notify->loaded())
				{
					$notify = ORM::factory('Notification')
						->where('alias', '=', 'quill.new_topic')
						->find();
				}

				// send out notifications
				foreach($sub as $s)
				{
					$values = [
						'user_id' => $s->user_id,
						'param' => [
							':other_user' => $user->username,
							':other_user_id' => $user->id,
							':topic_id' => $topic->id,
							':category_id' => $category->id,
							':topic_title' => $topic->title,
							':category' => $category->name
						],
						'log_id' => 0,
						'notification_id' => $notify->id,
						'read' => 0
					];

					$n = ORM::factory('User_Notification')
						->values($values)
						->save();


					Plug::fire('log.notify', $values);
				}
			}
		}
	}

	/**
	 * Send out notifications when replies are made to topics
	 */
	public function on_quill_reply(Model_Quill_Topic $topic, Model_Quill_Reply $reply, Model_User $user)
	{
		if($topic->category->location->subscribe_topic == 1)
		{
			//check if users are subscribed to this topic
			$sub = ORM::factory('Quill_Subscription')
				->where('type', '=', 'topic')
				->where('type_id', '=', $topic->id)
				->find_all();

			if(count($sub) > 0)
			{
				//check if the location has its own notification
				$notify = ORM::factory('Notification')
					->where('alias', '=', 'quill.'.$topic->category->location->name.'.reply')
					->find();

				if(!$notify->loaded())
				{
					$notify = ORM::factory('Notification')
						->where('alias', '=', 'quill.new_reply')
						->find();
				}

				// send out notifications
				foreach($sub as $s)
				{
					$values = [
						'user_id' => $s->user_id,
						'param' => [
							':other_user' => $user->username,
							':other_user_id' => $user->id,
							':reply_id' => $reply->id,
							':topic_id' => $topic->id,
							':category_id' => $topic->category->id,
							':topic_title' => $topic->title,
							':category' => $topic->category->name
						],
						'log_id' => 0,
						'notification_id' => $notify->id,
						'read' => 0
					];

					$n = ORM::factory('User_Notification')
						->values($values)
						->save();
					Plug::fire('log.notify', $values);
				}
			}
		}
	}
}
