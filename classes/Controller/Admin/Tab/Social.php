<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * User tab request controller
 *
 * @package    fusionFramework
 * @category   Admin/Social
 * @author     Maxim Kerstens
 * @copyright  (c) 2013-2014 Maxim Kerstens
 * @license    BSD
 */
class Controller_Admin_Tab_Social extends Controller_Admin_Tab
{
	public function action_pc_js()
	{
		$table = new Table();
		Plug::fire('admin.user.tab.chats', [$table]);

		$this->response->headers('Content-Type','application/x-javascript');
		$this->response->body($table->js(Route::url('admin.user.tab.pc.fill', null, true)));
	}

	public function action_pc_fill() {
		$this->access('admin.user.pc.view');

		$this->_handle_ajax = false;

		if (DataTables::is_request())
		{
			$table = new Table();
			Plug::fire('admin.user.tab.chats', [$table]);

			//set a model and render
			$model = ORM::factory('Message')
				->join(['message_replies', 'posts'])
				->on('message.id', '=', 'posts.message_topic_id')
				->or_where_open()
				->where('message.sender_id', '=', $this->request->param('id'))
				->or_where('message.receiver_id', '=', $this->request->param('id'))
				->or_where_close();

			$start['string'] = $_GET['date_start'];

			if(!empty($_GET['time_start']))
			{
				$start['string'] .= ' '.$_GET['time_start'];
				$start['format'] = 'm/d/Y g:i A';
			}
			else
			{
				$start['format'] = 'm/d/Y';
			}

			$end['string'] = $_GET['date_end'];
			if(!empty($_GET['time_end']))
			{
				$end['string'] .= ' '.$_GET['time_end'];
				$end['format'] = 'm/d/Y g:i A';
			}
			else
			{
				$end['format'] = 'm/d/Y';
			}

			//between start and end
			if(strlen($start['string']) > 4 && strlen($end['string']) > 4)
			{
				$model->where('posts.created_at', '>=', date_create_from_format($start['format'], $start['string'])->getTimestamp());
				$model->and_where('posts.created_at', '<=', date_create_from_format($end['format'], $end['string'])->getTimestamp());
			}
			// only on start day
			else if(strlen($start['string']) > 4 && strlen($end['string']) <= 4)
			{
				$begin = date_create_from_format('m/d/Y', $_GET['date_start'])->getTimestamp();
				$end = $begin + 86400;
				$model->where('posts.created_at', '>=', $begin);
				$model->where('posts.created_at', '<=', $end);
			}
			//before end
			else if(strlen($end['string']) > 4)
			{
				$model->and_where('posts.created_at', '<=', date_create_from_format($end['format'], $end['string'])->getTimestamp());
			}

			$data = $table->model($model)->request();

			$this->response
				->headers('content-type', 'application/json')
				->body($data->render());
		}
		else
			throw new HTTP_Exception_500();
	}

	public function action_pc_modal()
	{
		$this->access('admin.user.tab-pc.modal');

		$topic = $this->request->param('id');
		$page = $this->request->param('page');
		$user_id = $this->request->param('user_id');

		if(!$this->request->is_ajax())
			throw new HTTP_Exception_404;


		$chat = ORM::factory('Message', $topic);

		$messages = $chat->posts
			->order_by('id', 'DESC')
			->limit('5')
			->offset($page)
			->find_all();

		$posts = $chat->posts->count_all();

		$tpl = new View_Admin_User_Tab_Private_Modal;

		$tpl->messages = $messages;
		$tpl->player_id = $user_id;
		$tpl->pages = ceil($posts / 5);

		$tpl->next_page = ($tpl->pages > $page+1) ?
			Route::url('admin.user.tab.pc.modal', ['id' => $topic, 'user_id' => $user_id, 'page' => ++$page], true) :
			false;

		$html = Kostache_Layout::factory('empty')
			->render($tpl);

		if($page == 0)
		{
			$data = [];
			$data['user'] = ($chat->sender_id == $user_id) ? $chat->receiver->username : $chat->sender->username;
			$data['other_user'] = ($chat->sender_id == $user_id) ? $chat->sender->username : $chat->receiver->username;
			$data['total_posts'] = $posts;
			$data['html'] = $html;
			RD::set(RD::SUCCESS, 'loaded modal', null, $data);
		}
		else
		{
			$this->_handle_ajax = false;
			$this->response->body($html);
		}
	}

	public function action_pc_message()
	{
		$this->access('admin.user.tab-pc.message');

		if(!$this->request->is_ajax())
			throw new HTTP_Exception_404();

		try {
			$msg = ORM::factory('Message')
				->post($this->request->post('message'), ORM::factory('User', $this->request->param('id')), Fusion::$user);

			RD::set(RD::SUCCESS, 'Message sent successfully');
		}
		catch(Kohana_Exception $e)
		{
			RD::set(RD::ERROR, $e->getMessage());
		}
	}
} // End Admin Social Tab
