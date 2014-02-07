<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Forum controller
 *
 * @package    fusionFramework/social
 * @category   Controller
 * @author     Maxim Kerstens
 * @copyright  (c) Maxim Kerstens
 */
class Fusion_Controller_Forums extends Controller_Fusion_Site
{
	/**
	 * @var Element
	 */
	protected $_breadcrumb = null;

	/**
	 * @var Quill
	 */
	protected $_category = null;

	// Add the guest_view from the config and initialise the breadcrumb element
	public function before()
	{
		$this->_login_required = Kohana::$config->load('forums.guest_view');
		// No need to set this up during POST requests
		if($this->request->method() != Request::POST)
		{
			$this->_breadcrumb = Element::factory('forums');
		}

		//automatically load the category, if required
		$category = $this->request->param('id', false);

		if($category != false)
		{
			try {
				$this->_category = Quill::factory($category);

				if($this->_breadcrumb != null)
				{
					$this->_breadcrumb->get_item('forum.category')
						->param('id', $this->_category->id);
				}
			}
			catch(Quill_Exception_Category_Load $e)
			{
				RD::set(RD::ERROR, 'No category could be loaded.');
				$this->redirect(Route::url('forum'));
			}
		}
		parent::before();
		Fusion::$assets->add('js', 'confirm.js');
	}


	public function after()
	{
		// No need to set this up during POST requests
		if($this->request->method() != Request::POST)
		{
			//Set the category's title in the breadcrumb
			if($this->_tpl->category != null)
			{
				$this->_breadcrumb->get_item('forum')->set_title(ucfirst(str_replace('forum-', '', $this->_category->location->name)));
				$this->_breadcrumb->get_item('forum.category')->set_title($this->_category->title);
			}

			//Set the topic's name in the breadcrumb
			if($this->_tpl->topic != null)
			{
				$this->_breadcrumb->get_item('forum.topic')
					->param('id', $this->_category->id)
					->param('topic', $this->_tpl->topic->id);

				$this->_breadcrumb->get_item('forum.topic')->set_title($this->_tpl->topic->title);
			}

			//render the breadcrumb
			$this->_tpl->breadcrumb = $this->_breadcrumb->render('Breadcrumb', 'bootstrap');
		}

		parent::after();
	}

	// Show categories
	public function action_index()
	{
		$this->_tpl = new View_Forum_Index;

		// Retrieve all locations that are prefixed with forum-
		// so we can display them separately
		$locations = ORM::factory('Quill_Location')
			->where('name', 'LIKE', 'forum-%')
			->find_all();

		$categories = array();

		foreach($locations as $loc)
		{
			$categories[] = array(
				'location' => $loc->name,
				'list' => Quill::categories($loc->name)
			);
		}

		$this->_tpl->categories = $categories;
	}

	// Show topic list for a category
	public function action_category()
	{
		$this->_tpl = new View_Forum_Category;

		$this->_tpl->category = $this->_category;

		$this->_breadcrumb->last_item($this->_tpl->category->title);

		$topics = $this->_tpl->category->topics(false, false);

		$paginate = Paginate::factory($topics, Kohana::$config->load('forums.topics'))->execute();

		$this->_tpl->pagination = $paginate->render();
		$this->_tpl->topics = $paginate->result();
		$this->_tpl->create_topic = Route::url('forum.topic.create', array('id' => $this->request->param('id')), true);
	}

	// Show topic list for a category
	public function action_topic()
	{
		$this->_tpl = new View_Forum_Topic;

		$this->_tpl->category = $this->_category;

		try {
			$topic = $this->_tpl->category->topic($this->request->param('topic'), 'id', null);

			if(!$topic->loaded())
			{
				RD::set(RD::WARNING, 'No topic found.');
				$this->redirect(Route::url('forum', null, true));
			}

			// load the replies
			$replies = $topic->replies(false);

			// paginate them
			$paginate = Paginate::factory($replies, Kohana::$config->load('forums.replies'))->execute();

			$this->_tpl->topic = $topic;

			$this->_tpl->pagination = $paginate->render();
			$this->_tpl->replies = $paginate->result();

			$this->_tpl->create_reply = Route::url('forum.topic.reply', array(
				'id' => $this->request->param('id'),
				'topic' => $topic->id
			), true);
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'No topic found.');
			$this->redirect(Route::url('forum', null, true));
		}
	}

	// Show create topic form
	public function action_create_topic()
	{
		$this->_login_required();

		$this->_tpl = new View_Forum_Topic_Create;

		$this->_tpl->submit_link = Route::url('forum.topic.post', array('id' => $this->request->param('id')), true);

		$this->_tpl->category = $this->_category;

		if(Fusion::$user->hasAccess('forum.sticky'))
		{
			$this->_tpl->can_sticky = true;
		}
		if(Fusion::$user->hasAccess('forum.status'))
		{
			$this->_tpl->change_status = true;
			$this->_tpl->options = array(
				array('value' => 'active', 'title' => 'Active', 'selected' => false),
				array('value' => 'archived', 'title' => 'Archived', 'selected' => false),
				array('value' => 'deleted', 'title' => 'Deleted', 'selected' => false)
			);
		}

		$this->_breadcrumb->last_item('Create topic');
	}

	// Attempt to create a topic
	public function action_post_topic()
	{
		$this->_login_required();

		$topic = null;

		try {
			$keys = array('title', 'content');

			if(Fusion::$user->hasAccess('forum.sticky'))
			{
				$keys[] = 'stickied';
			}
			if(Fusion::$user->hasAccess('forum.status'))
			{
				$keys[] = 'status';
			}

			$values = Arr::extract($_POST, $keys);
			$topic = $this->_category->create_topic($values);

			Plug::fire('forum.parse', [$topic]);

			RD::set(RD::SUCCESS, 'Thanks for creating a topic.');

			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $topic->id
			), true));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('model');

			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
		catch(Quill_Exception_Topic_Create $e)
		{
			RD::set(RD::ERROR, $e->getMessage());
		}

		$this->redirect(Route::url('forum.category', array('id' => $this->request->param('id')), true));
	}

	// Show create topic form
	public function action_edit_topic()
	{
		$this->_login_required();

		$this->_tpl = new View_Forum_Topic_Edit;

		$this->_tpl->submit_link = Route::url('forum.topic.change', array('id' => $this->request->param('id'), 'topic' => $this->request->param('topic')), true);

		try {
			$this->_tpl->category = $this->_category;
			$topic = $this->_category->topic($this->request->param('topic'), 'id', null);

			if($topic->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.topic.edit'))
			{
				RD::set(RD::WARNING, 'You can\'t edit other people\'s topics');

				$this->redirect(Route::url('forum.topic', array(
					'id' => $this->request->param('id'),
					'topic' => $this->request->param('topic')
				), true));
			}

			if(Fusion::$user->hasAccess('forum.sticky'))
			{
				$this->_tpl->can_sticky = true;
			}

			if(Fusion::$user->hasAccess('forum.status'))
			{
				$this->_tpl->change_status = true;
				$this->_tpl->options = array(
					array('value' => 'active', 'title' => 'Active', 'selected' => ($topic->status == 'active')),
					array('value' => 'archived', 'title' => 'Archived', 'selected' => ($topic->status == 'archived')),
					array('value' => 'deleted', 'title' => 'Deleted', 'selected' => ($topic->status == 'deleted'))
				);
			}

			$this->_tpl->topic = $topic;

			$this->_breadcrumb->last_item('Edit topic');
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'No topic found.');
			$this->redirect(Route::url('forum', null, true));
		}
	}

	// Attempt to create a topic
	public function action_change_topic()
	{
		$this->_login_required();

		$topic = null;

		try {
			$keys = array('title', 'content');

			if(Fusion::$user->hasAccess('forum.sticky'))
			{
				$keys[] = 'stickied';
			}
			if(Fusion::$user->hasAccess('forum.status'))
			{
				$keys[] = 'status';
			}

			$values = Arr::extract($_POST, $keys);

			$topic = $this->_category->topic($this->request->param('topic'), 'id', null);

			if($topic->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.topic.edit'))
			{
				RD::set(RD::WARNING, 'You can\'t edit other people\'s topics');

				$this->redirect(Route::url('forum.topic', array(
					'id' => $this->request->param('id'),
					'topic' => $this->request->param('topic')
				), true));
			}

			$topic->values($values)
				->save();

			RD::set(RD::SUCCESS, 'you\'ve successfully edited this topic.');

			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $topic->id
			), true));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('model');

			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
		catch(Quill_Exception_Topic_Create $e)
		{
			RD::set(RD::ERROR, $e->getMessage());
		}

		$this->redirect(Route::url('forum.category', array('id' => $this->request->param('id')), true));
	}

	// Show create topic form
	public function action_delete_topic()
	{
		$this->_login_required();

		try {
			$topic = $this->_category->topic($this->request->param('topic'), 'id', null);

			if($topic->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.topic.delete'))
			{
				RD::set(RD::WARNING, 'You can\'t delete other people\'s topics');
			}
			else
			{
				$topic->delete();
				RD::set(RD::SUCCESS, 'You\'ve successfully deleted your topic!');
			}

			$this->redirect(Route::url('forum.category', array(
				'id' => $this->request->param('id')
			), true));

		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'No topic found.');
			$this->redirect(Route::url('forum', null, true));
		}
	}

	// Show create reply form
	public function action_create_reply()
	{
		$this->_login_required();

		$this->_tpl = new View_Forum_Topic_Reply;

		$this->_tpl->submit_link = Route::url('forum.topic.reply.post', array(
			'id' => $this->request->param('id'),
			'topic' => $this->request->param('topic')
		), true);

		$this->_tpl->category = $this->_category;

		$this->_tpl->topic = $this->_category->topic($this->request->param('topic'));

		if($this->_tpl->topic->status != 'active')
		{
			RD::set(RD::ERROR, 'This topic is archived, you can\'t make a reply.');

			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic')
			), true));
		}

		$this->_breadcrumb->last_item('Reply');
	}

	// Attempt to create a reply
	public function action_post_reply()
	{
		$this->_login_required();

		$topic = null;

		$values = Arr::extract($_POST, array('content'));

		try {
			$reply = $this->_category
				->topic($this->request->param('topic'))
				->create_reply($values);

			Plug::fire('forum.parse', [$reply]);

			RD::set(RD::SUCCESS, 'Thanks for posting your reply.');

			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic')
			), true));
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('model');

			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'There\s no topic to reply to.');
		}
		catch(Quill_Exception_Reply_Status $e)
		{
			$error = $e->getMessage();
			RD::set(RD::ERROR, $error);
		}

		$this->redirect(Route::url('forum.topic', array(
			'id' => $this->request->param('id'),
			'topic' => $this->request->param('topic')
		), true));
	}

	// Show edit reply form
	public function action_edit_reply()
	{
		$this->_login_required();

		$this->_tpl = new View_Forum_Reply_Edit;

		$this->_tpl->category = $this->_category;
		$this->_tpl->topic = $this->_tpl->category->topic($this->request->param('topic'));

		try {
			$reply = $this->_tpl->topic
				->reply($this->request->param('reply'));

			if($reply->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.reply.edit'))
			{
				RD::set(RD::WARNING, 'You can\'t edit other people\'s replies');

				$this->redirect(Route::url('forum.topic', array(
					'id' => $this->request->param('id'),
					'topic' => $this->request->param('topic')
				), true));
			}

			$this->_tpl->reply = $reply;
			$this->_tpl->submit_link = Route::url('forum.topic.reply.change', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic'),
				'reply' => $this->request->param('reply')
			), true);

			$this->_breadcrumb->last_item('Edit Reply');
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'No reply found.');

			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic')
			), true));
		}
	}

	// Attempt to edit a reply
	public function action_change_reply()
	{
		$this->_login_required();

		$topic = null;

		$values = Arr::extract($_POST, array('content'));

		try {
			$reply = $this->_category
				->topic($this->request->param('topic'))
				->reply($this->request->param('reply'));

			if($reply->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.reply.edit'))
			{
				RD::set(RD::WARNING, 'You can\'t edit other people\'s replies');

				$this->redirect(Route::url('forum.topic', array(
					'id' => $this->request->param('id'),
					'topic' => $this->request->param('topic')
				), true));
			}

			$reply->values($values)
				->save();

			RD::set(RD::SUCCESS, 'Thanks for editing your reply.');
		}
		catch(ORM_Validation_Exception $e)
		{
			$errors = $e->errors('model');

			foreach($errors as $error)
			{
				RD::set(RD::ERROR, $error);
			}
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'There\s no topic to reply to.');
		}
		catch(Quill_Exception_Reply_Create $e)
		{
			$error = $e->getMessage();
			RD::set(RD::ERROR, $error);
		}

		$this->redirect(Route::url('forum.topic', array(
			'id' => $this->request->param('id'),
			'topic' => $this->request->param('topic')
		), true));
	}

	// Attempt to delete a reply
	public function action_delete_reply()
	{
		$this->_login_required();

		$this->_tpl = new View_Forum_Reply_Edit;

		$topic = $this->_category->topic($this->request->param('topic'));

		try {
			$reply = $topic
				->reply($this->request->param('reply'));

			if($reply->user->id != Fusion::$user->id && !Fusion::$user->hasAccess('forum.reply.delete'))
			{
				RD::set(RD::WARNING, 'You can\'t delete other people\'s replies');

				$this->redirect(Route::url('forum.topic', array(
					'id' => $this->request->param('id'),
					'topic' => $this->request->param('topic')
				), true));
			}

			$reply->delete();

			RD::set(RD::SUCCESS, 'You\'ve successfully deleted your reply!');
			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic')
			), true));
		}
		catch(Quill_Exception_Topic_Load $e)
		{
			RD::set(RD::ERROR, 'No reply found.');
			$this->redirect(Route::url('forum.topic', array(
				'id' => $this->request->param('id'),
				'topic' => $this->request->param('topic')
			), true));
		}

	}

} // End Forums controller
