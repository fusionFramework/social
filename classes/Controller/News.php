<?php defined('SYSPATH') or die('No direct script access.');

class Controller_News extends Controller_Fusion_Site {
	protected $_login_required = false;

	public function action_index()
	{
		$this->_tpl = new View_News_Index;

		$posts = Quill::factory('news')->topics(false, null, false)
			->order_by('quill_topic.id', 'DESC');

		$paginate = Paginate::factory($posts, Kohana::$config->load('news.posts'))->execute();

		$this->_tpl->pagination = $paginate->render();
		$this->_tpl->posts = $paginate->result();
	}

	public function action_post()
	{
		$this->_tpl = new View_News_Post;

		$this->_tpl->post = ORM::factory('Quill_Topic', $this->request->param('id'));

		$replies = $this->_tpl->post->replies(false, 'active', false)
			->order_by('quill_reply.created_at', 'DESC');

		$paginate = Paginate::factory($replies, Kohana::$config->load('news.replies'))->execute();

		$this->_tpl->pagination = $paginate->render();
		$this->_tpl->replies = $paginate->result();
		$this->_tpl->make_comment = (Fusion::$user != null);
		$this->_tpl->comment_link = Route::url('news.comment', array('id' => $this->_tpl->post->id), true);
	}

	public function action_comment()
	{
		$this->_login_required();

		if($this->request->method() != Request::POST)
		{
			RD::set(RD::ERROR, 'You can\'t post a comment like this.');
		}
		else if(empty($_POST['content']))
		{
			RD::set(RD::ERROR, 'You can\'t post an empty comment to this news post.');
		}
		else
		{
			$values = array(
				'content' => $_POST['content'],
				'user_id' => Fusion::$user,
				'topic_id' => $this->request->param('id')
			);

			ORM::factory('Quill_Topic')
				->where('quill_topic.id', '=', $this->request->param('id'))
				->find()
				->create_reply($values);

			RD::set(RD::SUCCESS, 'Thanks for creating a comment');
		}

		$this->redirect(Route::url('news.comments', array('id' => $this->request->param('id')), true));
	}
} // End Welcome
