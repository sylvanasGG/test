<?php
namespace Admin\Controller;
use Think\Controller;
class PortraitController extends BaseController {

    public function index()
    {
        $portrait = M('Portrait');
        $res = $portrait->order('created_at desc')->select();
        $this->assign('portraits',$res);
        $this->display();
    }

    /**
     *试图：新增
     **/
    public function create()
    {
        $this->display('create');
    }

    /**
     *动作：新增
     **/
    public function store()
    {
        header("Content-type:text/html;charset=utf-8");
        $portrait = M('Portrait');
        $map['title'] = $_POST['title'];
        if ($portrait->where($map)->find()) {

            $this->responseError('标题重复','Portrait/create');
        }
        if($_FILES['portrait_photo']['name'])
        {
            $res = $this->upload($_FILES['portrait_photo']);
        }

        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['portrait_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($portrait->add($data))
        {
            $this->responseSuccess('创建成功','Portrait/index');
        }
        $this->responseError('创建失败','Portrait/index');
    }
    /**
     *试图：编辑
     **/
    public function edit($id)
    {
        $mod = M('Portrait');
        $portrait = $mod->where('id='.$id)->find();
        $this->assign('portrait',$portrait);
        $this->display();
    }
    /**
     *动作：编辑
     **/
    public function update($id)
    {
        if($_FILES['portrait_photo']['name'])
        {
            $res = $this->upload($_FILES['portrait_photo']);
        }
        $portrait = M('Portrait');
        if($res)$portrait->portrait_photo = __ROOT__.'/uploads/'.$res;
        $portrait->title = $_POST['title'];
        $portrait->content = $_POST['content'];
        $portrait->updated_at = date('Y-m-d H:i:s',time());
        if($portrait->where('id='.$id)->save())
        {
            $this->responseSuccess('编辑成功','Portrait/index');
        }
        $this->responseError('编辑失败','Portrait/index');
    }
    /**
     *动作：编辑
     **/
    public function delete()
    {
        $portrait = M('Portrait');
        $id = $_GET['id'];
        $portrait->where('id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }

}