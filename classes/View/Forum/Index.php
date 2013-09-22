<?php defined('SYSPATH') OR die('No direct script access.');

class View_Forum_Index extends View_Forum {
	/**
	 * @var array Contains loaded news posts
	 */
	public $categories = array();

	public function locations()
	{
		$locations = array();

		foreach($this->categories as $cat)
		{
			$categories = array();

			if(count($cat['list']) > 0)
			{
				foreach($cat['list'] as $category)
				{
					$categories[] = array(
						'id' => $category->id,
						'title' => $category->title,
						'description' => $category->description,
						'link' => Route::url('forum.category', array('id' => $category->id), true),
						'topics' => $category->topic_count,
						'last_topic' => ($category->last_topic->loaded()) ? HTML::anchor(Route::url('forum.topic', array('id' => $category->id, 'topic' => $category->last_topic->id), true), $category->last_topic->title) : '<i>none</i>'
					);
				}
			}

			$locations[] = array(
				'title' => ucfirst(str_replace('forum-', '', $cat['location'])),
				'categories' => $categories
			);
		}

		return $locations;
	}
	/**
	 * Standardise categories
	 *
	 * @return array
	 */
	public function categories()
	{
		$categories = array();

		if(count($this->categories) > 0)
		{
			foreach($this->categories as $category)
			{
				$categories[] = array(
					'id' => $category->id,
					'title' => $category->title,
					'description' => $category->description,
					'link' => Route::url('forum.category', array('id' => $category->id), true),
					'topics' => $category->topic_count,
					'last_topic' => ($category->last_topic->loaded()) ? HTML::anchor(Route::url('forum.topic', array('id' => $category->id, 'topic' => $category->last_topic->id), true), $category->last_topic->title) : '<i>none</i>'
				);
			}
		}
		return $categories;
	}
}
