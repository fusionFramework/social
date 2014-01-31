<?php defined('SYSPATH') OR die('No direct script access.');

// News routes
Route::set('news', 'news(/<page>)', array('page' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'News',
		'action'     => 'index',
		'page'       => 1
	)
);
Route::set('news.comments', 'news/view/<id>(/<page>)', array('page' => '([0-9]*)', 'id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'News',
		'action'     => 'post',
		'page'       => 1
	)
);
Route::set('news.comment', 'news/comment/<id>', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'News',
		'action'     => 'comment'
	)
);

// Forum routes
Route::set('forum', 'forum')
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'index'
	)
);

Route::set('forum.category', 'forum/<id>(/<page>)', array('page' => '([0-9]*)', 'id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'category',
		'page' => 1
	)
);
Route::set('forum.topic', 'forum/<id>/t/<topic>(/<page>)', array('page' => '([0-9]*)', 'id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'topic',
		'page' => 1
	)
);
Route::set('forum.topic.create', 'forum/<id>/create', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'create_topic'
	)
);
Route::set('forum.topic.post', 'forum/<id>/submit', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'post_topic'
	)
);
Route::set('forum.topic.edit', 'forum/<id>/t/<topic>/edit', array('id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'edit_topic'
	)
);
Route::set('forum.topic.change', 'forum/<id>/t/<topic>/change', array('id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'change_topic'
	)
);
Route::set('forum.topic.delete', 'forum/<id>/t/<topic>/delete', array('id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'delete_topic'
	)
);
Route::set('forum.topic.reply', 'forum/<id>/t/<topic>/reply', array('id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'create_reply'
	)
);
Route::set('forum.topic.reply.post', 'forum/<id>/t/<topic>/post', array('id' => '([0-9]*)', 'topic' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'post_reply'
	)
);
Route::set('forum.topic.reply.edit', 'forum/<id>/t/<topic>/edit/<reply>', array('id' => '([0-9]*)', 'topic' => '([0-9]*)', 'reply' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'edit_reply'
	)
);
Route::set('forum.topic.reply.change', 'forum/<id>/t/<topic>/change/<reply>', array('id' => '([0-9]*)', 'topic' => '([0-9]*)', 'reply' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'change_reply'
	)
);
Route::set('forum.topic.reply.delete', 'forum/<id>/t/<topic>/delete/<reply>', array('id' => '([0-9]*)', 'topic' => '([0-9]*)', 'reply' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Forums',
		'action'     => 'delete_reply'
	)
);

// Private message routes
Route::set('message', 'messages(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Message',
		'action'     => 'index',
		'id' => null
	)
);
Route::set('message.create', 'messages/create(/<user>)', array('user' => '([a-z0-9-_]+)'))
	->defaults(array(
		'controller' => 'Message',
		'action'     => 'create',
		'user' => null
	)
);
Route::set('message.post', 'messages/post')
	->defaults(array(
		'controller' => 'Message',
		'action'     => 'post'
	)
);
Route::set('message.reply', 'messages/<id>/reply', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Message',
		'action'     => 'reply'
	)
);

Route::set('admin.user.tab.pc.fill', 'admin/user/tab/pc/fill(/<id>)', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_Social',
			'action'     => 'pc_fill',
		)
	);
Route::set('admin.user.tab.pc.js', 'admin/user/tab/pc.js')
	->defaults(array(
			'controller' => 'Admin_Tab_Social',
			'action'     => 'pc_js',
		)
	);
Route::set('admin.user.tab.pc.modal', 'admin/user/tab/pc/modal/<id>(/<page>(/<user_id>))', array('id' => '([0-9]+)','user_id' => '([0-9]+)', 'page' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_Social',
			'action'     => 'pc_modal',
			'page'       => 0,
			'user_id'    => 0
		)
	);
Route::set('admin.user.tab.pc.message', 'admin/user/tab/pc/message/<id>', array('id' => '([0-9]+)'))
	->defaults(array(
			'controller' => 'Admin_Tab_Social',
			'action'     => 'pc_message'
		)
	);