<?php
namespace Admin\Controller;
use Think\Controller;
class TransverseController extends BaseController {

    public function index()
    {
        $transverse = M('Transverse');
        $res = $transverse->order('created_at desc')->select();
        $this->assign('transverses',$res);
        $this->display();
    }

    /**
     *视图：创建
     **/
    public function create()
    {
        $this->display('create');
    }

    /**
     *动作：创建
     **/
    public function store()
    {
        header("Content-type:text/html;charset=utf-8");
        $transverse = M('Transverse');
        $map['title'] = $_POST['title'];
        if ($transverse->where($map)->find()) {

            $this->responseError('标题重复','Transverse/Create');
        }
        if($_FILES['transverse_photo']['name'])
        {
            $res = $this->upload($_FILES['transverse_photo']);
        }

        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['transverse_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($transverse->add($data))
        {
            $this->responseSuccess('创建成功','Transverse/index');
        }
        $this->responseError('创建失败','Transverse/create');
    }
    /**
     *视图：编辑
     **/
    public function edit($id)
    {
        $mod = M('Transverse');
        $transverse = $mod->where('id='.$id)->find();
        $this->assign('transverse',$transverse);
        $this->display();
    }
    /**
     *编辑
     **/
    public function update($id)
    {
        if($_FILES['transverse_photo']['name'])
        {
            $res = $this->upload($_FILES['transverse_photo']);
        }
        $transverse = M('Transverse');
        if($res)$transverse->transverse_photo = __ROOT__.'/uploads/'.$res;
        $transverse->title = $_POST['title'];
        $transverse->content = $_POST['content'];
        $transverse->updated_at = date('Y-m-d H:i:s',time());
        if($transverse->where('id='.$id)->save())
        {
            $this->responseSuccess('更新成功','Transverse/index');
        }
        $this->responseError('更新失败','Transverse/index');
    }
    /**
     *删除
     **/
    public function delete()
    {
        $transverse = M('Transverse');
        $id = $_GET['id'];
        $transverse->where('id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }

}