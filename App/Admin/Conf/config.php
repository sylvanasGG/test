<?php
return array(
    //'配置项'=>'配置值'
    'URL_ROUTE_RULES'=>array(
        //'admin/index' => 'Admin/Index/index',
        //'index/show' => 'Admin/Index/show'
    ),
    'TMPL_PARSE_STRING'=>array(
        '__PUBLIC__'=>__ROOT__."/Public",
    ),
    //开启布局
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layouts/admin',
    // 设置默认的模板主题
    //'DEFAULT_THEME'    =>    'admin'

    //上传图片的绝对地址
    //'UPLOAD_PATH'            =>realpath(__ROOT__).'/Public/uploads/img/',

    'LAYOUT_MENU'=>array(
        ''=>array(
            'treeView' => array('name' => '仪表盘', 'icon' => 'fa-dashboard', 'url' => '#', 'actionName' => 'Admin\Controller\AdminController'),
            'treeViewMenu' => array(
                array('name' => '首页', 'icon' => 'fa-circle-o', 'url' => "Admin/index", 'actionName' => "Admin/index",'auth' =>array()
                ),

            )
        ),
        'logo' => array(
            'treeView' => array('name' => 'logo', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\LogoController'),
            'treeViewMenu' => array(
                array('name' => '更换', 'icon' => 'fa-circle-o', 'url' => "Logo/index", 'actionName' => "Logo/index",'auth' =>array(

                ))
            )
        ),
        'plate' => array(
            'treeView' => array('name' => '板块', 'icon' => 'fa-folder', 'url' => '#', 'actionName' => 'Admin\Controller\PlateController'),
            'treeViewMenu' => array(
                array('name' => '板块列表', 'icon' => 'fa-circle-o', 'url' => "Plate/index", 'actionName' => "Plate/index",'auth' =>array(

                )),
                array('name' => '新增板块', 'icon' => 'fa-circle-o', 'url' => "Plate/create", 'actionName' => 'Plate/create','auth' =>array(
                    ''
                ))
            )
        ),
         'slide' => array(
             'treeView' => array('name' => '幻灯片', 'icon' => 'fa-pencil', 'url' => '#', 'actionName' => 'Admin\Controller\SlideController'),
             'treeViewMenu' => array(
                 array('name' => '更换图片', 'icon' => 'fa-circle-o', 'url' => "Slide/index", 'actionName' => "Slide/index",'auth' =>array(

                 ))
             )
         ),
         'transverse' => array(
            'treeView' => array('name' => '横向', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\TransverseController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Transverse/index", 'actionName' => "Transverse/index",'auth' =>array(

                )),
                array('name' => '新增', 'icon' => 'fa-circle-o', 'url' => "Transverse/create", 'actionName' => 'Transverse/create','auth' =>array(
                    ''
                ))
            )
        ),
        'portrait' => array(
            'treeView' => array('name' => '纵向', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\PortraitController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Portrait/index", 'actionName' => "Portrait/index",'auth' =>array(

                )),
                array('name' => '新增', 'icon' => 'fa-circle-o', 'url' => "Portrait/create", 'actionName' => 'Portrait/create','auth' =>array(
                    ''
                ))
            )
        ),
        'img' => array(
            'treeView' => array('name' => '图片集合', 'icon' => 'fa-pencil', 'url' => '#', 'actionName' => 'Admin\Controller\PictureController'),
            'treeViewMenu' => array(
                array('name' => '更换图片', 'icon' => 'fa-circle-o', 'url' => "Picture/index", 'actionName' => "Picture/index",'auth' =>array(

                ))
            )
        ),

        'solve' => array(
            'treeView' => array('name' => '解决方案', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\SolveController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Solve/index", 'actionName' => "Solve/index",'auth' =>array(

                )),
//                array('name' => '新增', 'icon' => 'fa-circle-o', 'url' => "Solve/create", 'actionName' => 'Solve/create','auth' =>array(
//                    ''
//                ))
            )
        ),
        'case' => array(
            'treeView' => array('name' => '成功案例', 'icon' => 'fa-comments', 'url' => '#', 'actionName' => 'Admin\Controller\CaseController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Case/index", 'actionName' => "Case/index",'auth' =>array(

                )),
                array('name' => '新增', 'icon' => 'fa-circle-o', 'url' => "Case/create", 'actionName' => 'Case/create','auth' =>array(
                    ''
                ))
            )
        ),



        'link' => array(
            'treeView' => array('name' => '友情链接', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\LinkController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Link/index", 'actionName' => "Link/index",'auth' =>array(

                )),
            )
        ),

        'article' => array(
            'treeView' => array('name' => '文章', 'icon' => 'fa-pagelines', 'url' => '#', 'actionName' => 'Admin\Controller\ArticleController'),
            'treeViewMenu' => array(
                array('name' => '列表', 'icon' => 'fa-circle-o', 'url' => "Article/index", 'actionName' => "Article/index",'auth' =>array(

                )),
                array('name' => '新增', 'icon' => 'fa-circle-o', 'url' => "Article/create", 'actionName' => 'Article/create','auth' =>array(
                    ''
                ))
            )
        ),
        'user' => array(
            'treeView' => array('name' => '管理员', 'icon' => 'fa-user', 'url' => '#', 'actionName' => 'Admin\Controller\UserController'),
            'treeViewMenu' => array(
                array('name' => '管理员列表', 'icon' => 'fa-circle-o', 'url' => "User/index", 'actionName' => 'User/index','auth' =>array(
                    'User/showAdd'
                )),
                array('name' => '增加管理员', 'icon' => 'fa-circle-o', 'url' => "User/showAdd", 'actionName' => 'User/showAdd','auth' =>array(
                    ''
                )),
                array('name' => '管理组权限', 'icon' => 'fa-circle-o', 'url' => "User/showGroupsList", 'actionName' => 'User/showGroupsList','auth' =>array(
                    ''
                )),

            )
        ),
    ),



);