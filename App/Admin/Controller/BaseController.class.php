<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Model\AdminAccessModel as AdminAccess;
class BaseController extends Controller {

    protected $_menus = '';
    protected $_allowAccess = '';
    public function __construct()
    {
        parent::__construct();

        //验证是否admin登录
        if(!session('admin.admin'))
        {
            $this->redirect('Auth/login','', 0, '');
            //$this->error('请先登录...','Auth/login',2);
        }
        //获取配置文件中的menu目录
        $this->_menus = C("LAYOUT_MENU");
        if(!$this->allowList()){
            echo '您无此权限，请联系管理员';exit;
        }

        $this->assign('_actionName',$this->getActionName());
        $this->assign('_controllerFun',$this->getControllerFun());
        $this->assign('menus',$this->getPermMenuList());
    }

    public function getMenuList()
    {
        // 加载Menu
        $menu = $this->_menus;

        return $menu;
    }

    /**
     * 获取允许访问的菜单列表
     */
    public function getPermMenuList()
    {
        $menuList = $this->_menus;
            $user = session('admin.admin');
        if(AdminAccess::checkIsSystemAdmin($user)) {
            return $menuList;
        }
            if ($user['cp_group_id']) {
                foreach ($menuList as $key => $topMenu) {
                    $itemExists = 0;
                    foreach ($topMenu['treeViewMenu'] as $menuKey => $menu) {
                        if (array_key_exists($menu['actionName'], $this->_allowAccess)) {
                            $itemExists = 1;
                        } else {
                            unset($menuList[$key]['treeViewMenu'][$menuKey]);
                        }
                    }
                    if (!$itemExists) unset($menuList[$key]);
                }
            }

        return $menuList;

    }

    public function allowList()
    {
        $user = session('admin.admin');
        //$user['custom_access'] = ! empty($user['custom_access']) ? unserialize($user['custom_access']) : array();
        if ($user['cp_group_id'] > 0)
        {
            //获取用户所在管理组的权限
            $adminAccess = new AdminAccess();
            $accessList = $adminAccess->where('cp_group_id='.$user['cp_group_id'])->select();
            //var_dump($accessList);exit;
            //权限菜单
            $menuList = $this->_menus;
            foreach ($menuList as $topMenu)
            {
                foreach ($topMenu['treeViewMenu'] as $menu)
                {
                    $adminAccess = new AdminAccess();
                    $adminAccess = $adminAccess->where('cp_group_id='.$user['cp_group_id'].' AND access="'.$menu['actionName'].'"')->find();
                    if ($adminAccess && $menu['auth'])
                    {
                        $menu['auth'] = is_array($menu['auth']) ? $menu['auth'] : (array)$menu['auth'];
                        foreach ($menu['auth'] as $auth)
                        {
                            $accessList[] = array('cp_group_id' => $adminAccess['cp_group_id'], 'access' => $auth);
                        }
                    }
                }
            }

            $actionName = $this->getControllerFun();
            //保存访问权限
            foreach ($accessList as $access)
            {
                $this->_allowAccess[$access['access']] = true;
            }
            $this->_allowAccess['Admin/index'] = true;
            //var_dump($this->_allowAccess);exit;
            if(!array_key_exists($actionName, $this->_allowAccess))
            {
               return false;
            }
            return true;
        }

        return true;
    }

    /**
     * 获取控制器/方法名称
     *
     * @return string
     */
    protected function getControllerFun()
    {
        return CONTROLLER_NAME.'/'.ACTION_NAME;
    }

    /**
     * 返回成功信息
     *
     * @param string $msg
     * @param string $route
     */
    protected function responseSuccess($msg='',$route='')
    {
        $route = $route?$route:$_SERVER['HTTP_REFERER'];
        session('admin.success_msg',$msg);
        $this->redirect($route,'', 0, '');
    }

    /**
     * 返回错误信息
     *
     * @param string $msg
     * @param string $route
     */
    protected function responseError($msg='',$route='')
    {
        $route = $route?$route:$_SERVER['HTTP_REFERER'];
        session('admin.error_msg',$msg);
        $this->redirect($route,'', 0, '');
    }

    /**
     * 文件上传
     *
     * @param $fileInfo
     * @return string
     */
    public function upload($fileInfo)
    {

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     10485760 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传单个文件
        $info   =   $upload->uploadOne($fileInfo);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            return  $info['savepath'].$info['savename'];
        }
    }




}