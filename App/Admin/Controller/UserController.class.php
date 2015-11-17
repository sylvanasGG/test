<?php
namespace Admin\Controller;

use Think\Controller;
class UserController extends BaseController {

    /**
	* 视图：用户列表
    */
    public function index(){
        $map = array();
        $username = $cp_group_id =  '';
        $username = $_GET['username'];
        if ($username != null) {
            $map['username'] = array('like', "%$username%");
        }

        $cp_group_id = $_GET['cp_group_id'];
        if ($cp_group_id != null) {
            $map['cp_group_id'] = array('eq', $cp_group_id);
        }

        $this->assign('username', $username);
        $this->assign('cp_group_id', $cp_group_id);

    	$users = M('Users');
    	$count      = $users->where($map)->order('created_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $users->where($map)->order('created_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->assign('users',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index'); // 输出模板
    }

    public function showAdd()
    {
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->display('add');
    }

    /**
     *动作： 新增用户
     **/
    public function postAdd()
    {
        $user = M('Users');
        $data['username'] =$_POST['username'];
        $data['password'] =md5($_POST['password']);
        $data['email'] = $_POST['email'];
        $data['cp_group_id'] = $_POST['cp_group_id'];
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($user->add($data))
        {
            $this->responseSuccess('添加成功','User/index');
        }
        $this->responseError('添加失败','User/showAdd');
    }

    /**
     * 视图：编辑用户
     *
     * @param $id
     */
    public function showEdit($id)
    {
        $mod = M('Users'); // 实例化User对象
        $user = $mod->where('id = '.$id)->find();
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->assign('user',$user);
        $this->display('edit');
    }

    /**
     * 动作：编辑用户
     *
     * @param $id
     */
    public function postEdit($id)
    {
        $user = M('Users'); // 实例化User对象
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        if($_POST['password'])
        {
            $user->password = md5($_POST['password']);
        }
        $user->cp_group_id = $_POST['cp_group_id'];
        if($user->where('id='.$id)->save())
        {
            $this->responseSuccess('编辑成功','User/index');
        }
        $this->responseError('编辑失败','User/index');
    }

    /**
     * 动作：删除用户
     */
    public function deleteUser()
    {
        $id = $_GET['id'];
        $user = M('Users');
        if($user->where('id='.$id)->delete())
        {
            $data = array(
                'ret'=>0,
                'msg'=>'删除成功',
            );
            $this->ajaxReturn($data);
        }
    }

    /**
     * 视图：修改个人资料
     */
    public function getPersonalInfo()
    {
        $user = M('Users');
        $id = $_GET['id'];
        $user = $user->where('id='.$id)->find();
        $this->assign('user',$user);
        $this->display('personalInfo');
    }

    /**
     * 动作：修改个人资料
     */
    public function postPersonalInfo($id)
    {
        $user = M('Users'); // 实例化User对象
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        if($_POST['password'])
        {
            $user->password = md5($_POST['password']);
        }
        if($user->where('id='.$id)->save())
        {
            $_arr = is_object($user) ? get_object_vars($user) :$user;
            session('admin.admin',$_arr);
            $this->responseSuccess('编辑成功','User/getPersonalInfo');
        }
        $this->responseError('编辑失败','User/getPersonalInfo');
    }

    /**
     * 视图：个人用户权限分配
     *
     */
    public function showPersonalPerm()
    {
        $id = $_GET['id'];
        $users = M('Users');
        $user = $users->where('id='.$id)->find();
        $cp_group_id = $_GET['cp_group_id'] ? $_GET['cp_group_id']  : $user['cp_group_id'];
        //获取菜单权限列表
        $menuList = $this->getMenuList();
        //查询所有职务
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        //反序列化权限
        $user['custom_access'] = ! empty($user['custom_access']) ? unserialize($user['custom_access']) : array();
        //获取职务权限
        $adminAccess = M('Admin_accesses');
        $adminAccessList = $adminAccess->where('cp_group_id='.$cp_group_id)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        $this->assign('groupAccess', $groupAccess);
        $this->assign('menuList', $menuList);
        $this->assign('groupAll', $groupAll);
        $this->assign('user', $user);
        $this->assign('cp_group_id', $cp_group_id);
        $this->display('personalPerm');
    }

    /**
     * 动作：个人用户权限分配
     */
    public function postPersonalPerm($id)
    {
        $accessNew = $_POST['access_new'] ? $_POST['access_new'] : array();
        $cpgroupidNew = $_POST['cp_group_id_new'];

        //获取职务权限
        $adminAccess = M('Admin_accesses');
        $adminAccessList = $adminAccess->where('cp_group_id='.$cpgroupidNew)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        // 序列化自定义权限
        $customaccess = serialize(array_diff($groupAccess, $accessNew));

        $user = M('Users');
        $data['cp_group_id'] = $cpgroupidNew;
        $data['custom_access'] = $customaccess;
        $user->where('id='.$id)->save($data);

        $this->responseSuccess('保存成功','User/showPersonalPerm');
    }

    public function showGroupsList()
    {
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        $this->assign('groupAll',$groupAll);
        $this->display('groupsList');
    }

    public function postGroupsList()
    {
        //新增职务
        if($_POST['new_cp_group_name'])
        {
            $new_cp_group_name = $_POST['new_cp_group_name'];
            $adminGroup = M('Admin_groups');
            if (in_array($new_cp_group_name, array('系统管理员')) || $adminGroup->where('cp_group_name="'.$new_cp_group_name.'"')->find())
            {
                $this->responseError('该团队职务已经存在','User/showGroupsList');
            }
            $data['cp_group_name'] = strip_tags($new_cp_group_name);
            $adminGroup->add($data);
        }
        //更新职务
        if($_POST['name'])
        {
            foreach($_POST['name'] as $cp_group_id => $cp_group_name)
            {
                $adminGroup = M('Admin_groups');
                $adminGroup->cp_group_name = $cp_group_name;
                $adminGroup->where('cp_group_id='.$cp_group_id)->save();
            }
        }
        //删除职务
        if($_POST['delete'])
        {
            $adminAccess = M('Admin_accesses');
            $ids = $_POST['delete'];
            //$adminAccess->where('id='.$id)->delete();
            $adminAccess->where(array('cp_group_id'=>array('in',$ids)))->delete();
            $user = M('Users');
            $user->where(array('cp_group_id'=>array('in',$ids)))->delete();
            //User::whereIn('cp_group_id', $request->input('delete'))->delete();
            $adminGroup = M('Admin_groups');
            $adminGroup->where(array('cp_group_id'=>array('in',$ids)))->delete();
        }
        $this->responseSuccess('保存成功','User/showGroupsList');
    }


    public function showGroupPerm()
    {
        $cp_group_id = $_GET['id'];
        $menuList = $this->getMenuList();
        $group = M('Admin_groups');
        $groupAll  = $group->order('cp_group_id asc')->select();
        //获取职务权限
        $adminAccess = M('Admin_accesses');
        $adminAccessList = $adminAccess->where('cp_group_id='.$cp_group_id)->select();
        $groupAccess = array();
        foreach ($adminAccessList as $adminAccess)
        {
            $groupAccess[] = $adminAccess['access'];
        }
        $this->assign('groupAll',$groupAll);
        $this->assign('menuList',$menuList);
        $this->assign('id',$cp_group_id);
        $this->assign('groupAccess', $groupAccess);
        $this->display('groupPerm');
    }

    public function postGroupPerm($id)
    {
        $adminAccess = M('Admin_accesses');
        $adminAccess->where('cp_group_id='.$id)->delete();

        if(!empty($_POST['perm_allow']))
        {
            $perm_allow = $_POST['perm_allow'];
            foreach($perm_allow as $access)
            {
                $adminAccess = M('Admin_accesses');
                $data['cp_group_id'] = $id;
                $data['access'] = $access;
                $data['created_at'] = date('Y-m-d H:i:s',time());
                $data['updated_at'] = date('Y-m-d H:i:s',time());
                $adminAccess->add($data);
            }
        }
        $this->responseSuccess('保存成功','User/showGroupsList');
    }

   
}