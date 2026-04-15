<?php

return array(
    /** Trang chủ */
	'dashboard' => array(
		'name'       => 'admin/messages.sidebar.dashboard',
		'icon'       => 'fa-solid fa-house',
		'permission' => false,
		'children'   => false,
	),
    /** Quản lý danh mục */
    'menu-listtype' => array(
        'name'     => 'Danh mục',
        'type'     => 'menu-group',
        'show'     => true,
        'children' => array(
            //'users' => array(
            //    'name'     => 'Quản lý người dùng',
            //    'icon'     => 'fa-solid fa-user',
            //    'permission' => false,
            //    'children' => false,
            //),
            //'listtype' => array(
            //    'name'     => 'Quản lý danh mục',
            //    'icon'     => 'fa-solid fa-list-ul',
            //    'permission' => false,
            //    'children' => array(
            //        'listtype' => array(
            //            'name'       => 'Nhóm danh mục',
            //            'icon'       => 'fa-solid fa-angles-right',
            //            'permission' => false,
            //            'children'   => false,
            //        ),
            //        'list' => array(
            //            'name'       => 'Danh mục chi tiết',
            //            'icon'       => 'fa-solid fa-angles-right',
            //            'permission' => false,
            //            'children'   => false,
            //        ),
            //    ),
            //),
            //'units' => array(
            //    'name'       => 'Quản lý đơn vị',
            //    'icon'       => 'fa-solid fa-sitemap',
            //    'permission' => false,
            //    'children'   => false,
            //),
            'facilities' => array(
                'name'       => 'admin/messages.sidebar.facility',
                'icon'       => 'fa-solid fa-sitemap',
                'permission' => false,
                'children'   => false,
            ),
            'doctors' => array(
                'name'       => 'admin/messages.sidebar.doctor',
                'icon'       => 'fa-solid fa-sitemap',
                'permission' => false,
                'children'   => false,
            ),
        ),
    ),
    /** Quản lý sản phẩm */
    //'menu-product' => array(
    //    'name'     => 'Sản phẩm',
    //    'type'     => 'menu-group',
    //    'show'     => true,
    //    'children' => array(
    //        'categories' => array(
    //            'name'       => 'Quản lý danh mục',
    //            'icon'       => 'fa-solid fa-list-ul',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //        'brand' => array(
    //            'name'       => 'Quản lý thương hiệu',
    //            'icon'       => 'fa-solid fa-store',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //        'product' => array(
    //            'name'       => 'Quản lý sản phẩm',
    //            'icon'       => 'fa-solid fa-box',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //    ),
    //),
    /** Quản lý người dùng */
    //'menu-client' => array(
    //    'name'     => 'Giao diện khách hàng',
    //    'type'     => 'menu-group',
    //    'show'     => true,
    //    'children' => array(
    //        'module' => array(
    //            'name'       => 'Quản lý chức năng',
    //            'icon'       => 'fa-solid fa-sidebar',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //        'slider' => array(
    //            'name'       => 'Quản lý slider',
    //            'icon'       => 'fa-solid fa-images',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //    ),
    //),
    /** Quản lý chung */
    //'menu-general' => array(
    //    'name'     => 'Quản lý chung',
    //    'type'     => 'menu-group',
    //    'show'     => true,
    //    'children' => array(
    //        'role' => array(
    //            'name'       => 'System/Sidebar.role',
    //            'icon'       => 'fa-solid fa-user-plus',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //        'position' => array(
    //            'name'       => 'System/Sidebar.position',
    //            'icon'       => 'fa-solid fa-user-voice',
    //            'permission' => false,
    //            'children'   => false,
    //        ),
    //    ),
    //),
);
