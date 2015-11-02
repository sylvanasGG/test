<?php
namespace Admin\Controller;
use Think\Controller;
class LinkController extends BaseController
{

    public function index()
    {
        $link = M('Link');
        $res = $link->order('id desc')->select();
        $this->assign('links', $res);
        $this->display();
    }

    public function add()
    {
        $this->display();
    }

    public function store()
    {

        $link = M('Link');
        $data = $_POST;
        $item['name'] = $data['name'];
        $item['url'] = $data['url'];
        if($link->add($data))
        {
            session('admin.success_msg','添加成功');
            $this->redirect('Link/index','', 0, '');
            //$this->success('发表成功');
            // $this->ajaxReturn(array('ret'=>0));
        }
        $this->ajaxReturn(array('ret'=>1));
    }

    public function deleteLink()
    {
        $link = M('Link');
        $id = $_GET['id'];
        //
        $link->where('id='.$id)->delete();

        $data = array(
            'ret'=>0,
            'msg'=>'删除成功'
        );
        $this->ajaxReturn($data);
    }
}