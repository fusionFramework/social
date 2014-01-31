<?php defined('SYSPATH') OR die('No direct script access.');

class View_News_Index extends Views {
	public $title = 'News';

	/**
	 * @var array Contains loaded news posts
	 */
	public $posts = array();

	/**
	 * @var string Contains parsed pagination template
	 */
	public $pagination = '';

	/**
	 * Standardise posts
	 *
	 * @return array
	 */
	public function posts()
	{
		$posts = array();

		if(count($this->posts) > 0)
		{
			foreach($this->posts as $post)
			{
				Plug::listen('news.render', [$post]);
				$posts[] = array(
					'date' => $post->created_at,
					'title' => $post->title,
					'content' => $post->content,
					'comment_route' => Route::url('news.comments', array('id' => $post->id), true),
					'replies' => $post->reply_count
				);
			}
		}
		return $posts;
	}
}
