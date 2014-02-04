<?php defined('SYSPATH' OR die('No direct access allowed.'));
/**
 * Minimalistic menu config example.
 * Renders a simple list (<li>) of links.
 *
 * @see https://github.com/anroots/kohana-menu/wiki/Configuration-files
 * @author Ando Roots <ando@sqroot.eu>
 */
return array(
	'items'             => array(
		array(
			'route'   => 'forum',
			'title'   => 'Forum',
			'icon'    => 'fa fa-comments',
			'items' => array(
				array(
					'route'     => 'forum.category',
					'title' => null,
					'route_param' => array('id'),
					'items' => array(
						array(
							'route'     => 'forum.topic',
							'title' => null,
							'route_param' => array('id', 'topic'),
							'items' => array(
								array(
									'route'     => 'forum.topic.reply',
									'title' => null,
									'route_param' => array('id', 'topic'),
								),
								array(
									'route'     => 'forum.topic.edit',
									'title' => null,
									'route_param' => array('id', 'topic'),
								),
								array(
									'route'     => 'forum.topic.reply.edit',
									'title' => null,
									'route_param' => array('id', 'topic'),
								)
							)
						),
						array(
							'route'     => 'forum.topic.create',
							'title' => null,
							'route_param' => array('id'),
						)
					)
				)
			)
		)
	)
);