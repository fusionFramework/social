<?php 

/**
 *	Discussions categories admin routes
 */
//set the js file route
Route::set('admin.discussions.categories.js', 'admin/discussions/categories/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_Social_Discussions_Categories'
	)
);

//set the actions js file route
Route::set('admin.discussions.categories.actions.js', 'admin/discussions/categories/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_Social_Discussions_Categories',
	)
);

//set the fill table route
Route::set('admin.discussions.categories.fill', 'admin/discussions/categories/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_Social_Discussions_Categories'
	)
);

//set the delete record route
Route::set('admin.discussions.categories.remove', 'admin/discussions/categories/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_Social_Discussions_Categories'
	)
);


//set the record history route
Route::set('admin.discussions.categories.history', 'admin/discussions/categories/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_Social_Discussions_Categories'
	)
);


//set the load record route
Route::set('admin.discussions.categories.modal', 'admin/discussions/categories/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_Social_Discussions_Categories'
	)
);

//set the save record route
Route::set('admin.discussions.categories.save', 'admin/discussions/categories/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_Social_Discussions_Categories'
	)
);


//set the index route
Route::set('admin.discussions.categories.index', 'admin/discussions/categories(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_Social_Discussions_Categories'
	)
);

/**
 *	Discussions locations admin routes
 */
//set the js file route
Route::set('admin.discussions.locations.js', 'admin/discussions/locations/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_Social_Discussions_Locations'
	)
);

//set the actions js file route
Route::set('admin.discussions.locations.actions.js', 'admin/discussions/locations/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_Social_Discussions_Locations',
	)
);

//set the fill table route
Route::set('admin.discussions.locations.fill', 'admin/discussions/locations/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_Social_Discussions_Locations'
	)
);

//set the delete record route
Route::set('admin.discussions.locations.remove', 'admin/discussions/locations/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_Social_Discussions_Locations'
	)
);


//set the record history route
Route::set('admin.discussions.locations.history', 'admin/discussions/locations/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_Social_Discussions_Locations'
	)
);


//set the load record route
Route::set('admin.discussions.locations.modal', 'admin/discussions/locations/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_Social_Discussions_Locations'
	)
);

//set the save record route
Route::set('admin.discussions.locations.save', 'admin/discussions/locations/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_Social_Discussions_Locations'
	)
);


//set the index route
Route::set('admin.discussions.locations.index', 'admin/discussions/locations(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_Social_Discussions_Locations'
	)
);

/**
 *	News admin routes
 */
//set the js file route
Route::set('admin.news.js', 'admin/news/table.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js',
		'master'     => 'Admin_Social_News'
	)
);

//set the actions js file route
Route::set('admin.news.actions.js', 'admin/news/actions.js')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action'     => 'js_actions',
		'master'     => 'Admin_Social_News',
	)
);

//set the fill table route
Route::set('admin.news.fill', 'admin/news/fill(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'fill_table',
		'id' => 0,
		'master' => 'Admin_Social_News'
	)
);

//set the delete record route
Route::set('admin.news.remove', 'admin/news/<id>/remove', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'remove',
		'master' => 'Admin_Social_News'
	)
);


//set the record history route
Route::set('admin.news.history', 'admin/news/<id>/history', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'history',
		'master' => 'Admin_Social_News'
	)
);


//set the load record route
Route::set('admin.news.modal', 'admin/news/<id>/load', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'modal',
		'master' => 'Admin_Social_News'
	)
);

//set the save record route
Route::set('admin.news.save', 'admin/news/save')
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'save',
		'master' => 'Admin_Social_News'
	)
);


//set the index route
Route::set('admin.news.index', 'admin/news(/<id>)', array('id' => '([0-9]*)'))
	->defaults(array(
		'controller' => 'Fusion_CRUD',
		'action' => 'table',
		'id' => null,
		'master' => 'Admin_Social_News'
	)
);