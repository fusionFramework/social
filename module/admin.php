<?php defined('SYSPATH') OR die('No direct script access.');

Plug::listen('admin.nav_list', function() {
	return [
		'title' => 'Discussions',
		'link'  => '#',
		'icon'  => 'fa fa-comment',
		'items' => array(
			array(
				'title' => 'Locations',
				'route' => 'admin.discussions.locations.index',
				'icon'  => 'fa fa-folder',
			),
			array(
				'title' => 'Categories',
				'route' => 'admin.discussions.categories.index',
				'icon'  => 'fa fa-folder-open',
			)
		)
	];
});
Plug::listen('admin.nav_list', function() {
	return [
		'title' => 'Game',
		'link'  => '#',
		'icon'  => 'fa fa-globe',
		'items' => array(
			array(
				'title' => 'News',
				'route' => 'admin.news.index',
				'icon'  => 'fa fa-bullhorn',
			),
			array(
				'title' => 'NPCs',
				'route' => 'admin.npc.index',
				'icon'  => 'fa fa-male',
			)
		)
	];
});

Plug::listen('admin.user.tabs', function($user) {
	return [
		[
			'title' => 'Private chats',
			'id' => 'tab-pc',
			'permissions' => ['modal', 'message'],
			'render' => function() use($user) {
					$tpl = new View_Admin_User_Tab_Private_Chat;
					$tpl->user = $user;

					$tpl->routes = [
						'modal' => Route::url('admin.user.tab.pc.modal', array('id' => 0), true),
						'message' => Route::url('admin.user.tab.pc.message', array('id' => $user->id), true)
					];

					$table = new Table();
					Plug::fire('admin.user.tab.chats', [$table]);

					$tpl->table = $table->template_table($user->id);

					return $tpl;
				},
			'assets' => [
				'set' => ['slimScroll', 'jScroll', 'timepicker'],
				'js' => [Route::url('admin.user.tab.pc.js', null, true), 'admin/users/tab/chat.js']
			]
		]
	];
});

Plug::listen('admin.user.tab.chats', function(Kohana_Table $table){
	$table->name('chats');
	$table->show_buttons(true);

	$table->setup['fnServerParams'] = preg_replace('/\s+/', ' ', "function (aoData) {
			aoData.push( { 'name': 'date_start', 'value': $('#chat-date-start').val() } );
			aoData.push( { 'name': 'time_start', 'value': $('#chat-time-start').val() } );
			aoData.push( { 'name': 'date_end', 'value': $('#chat-date-end').val()+' '+$('#chat-time-end').val() } );
			aoData.push( { 'name': 'time_end', 'value': $('#chat-time-end').val() } );
		}");

	$table->add_column('user', ['head' => 'User', 'retrieve' => function($record){
			return ($record->sender_id == Request::$initial->param('id')) ? $record->receiver->username : $record->sender-username;
		}], false, false);

	$table->add_column('messages', ['head' => 'Messages', 'retrieve' => function($model){
			return $model->posts->count_all();
		}], false, false);

	$table->remove_button('edit')
		->remove_button('remove')
		->add_button('show', 'fa-search-plus', 'primary');

	return $table;
});
