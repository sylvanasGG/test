<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Common\PlateLib;
class PlateController extends BaseController {

    public function index()
    {
        $plate = M('Plate');
        $res = $plate->order('created_at desc')->select();
        $this->assign('plates',$res);
        $this->display();
    }

    /**
     *视图：创建
     **/
    public function create()
    {
        $this->display('create');
    }
    public function upload($fileInfo)
    {

        $upload = new \Think\Upload();
        $upload->maxSize   =     3145728 ;
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath  =     './uploads/';
        $upload->savePath  =     ''; 

        $info   =   $upload->uploadOne($fileInfo);
        if(!$info) {
            $this->error($upload->getError());
        }else{
            return  $info['savepath'].$info['savename'];
        }
    }
    /**
     *动作：创建
     **/
    public function store()
    {
        header("Content-type:text/html;charset=utf-8");
        $plate = M('Plate');
        $map['name']    = $_POST['name'];
        $map['mark']    = $_POST['mark'];
        $map['_logic']  = 'OR';
        if ($plate->where($map)->find()) {

            $this->error('名称重复或者标示重复');
        }
//        if($_FILES['plate_photo']['name'])
//        {
//            $res = $this->upload($_FILES['plate_photo']);
//        }

        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        //$data['plate_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($plate->add($data))
        {
            session('admin.success_msg','创建成功');
            $this->redirect('Plate/index','', 0, '');
        }
    }
    /**
     *视图：修改
     **/
    public function edit($id)
    {
        $mod = M('Plate');
        $plate = $mod->where('id='.$id)->find();
        $this->assign('plate',$plate);
        $this->display();
    }
    /**
     *动作：修改
     **/
    public function update($id)
    {
        $plate = M('Plate');
        $plate->name = $_POST['name'];
        $plate->mark = $_POST['mark'];
        $plate->content = $_POST['content'];
        $plate->updated_at = date('Y-m-d H:i:s',time());
        if($plate->where('id='.$id)->save())
        {
            session('admin.success_msg','编辑成功');
            $this->redirect('Plate/index','', 0, '');
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *删除
     **/
    public function delete()
    {
        $plate = M('Plate');
        $id = $_GET['id'];
        $plate->where('id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }

}