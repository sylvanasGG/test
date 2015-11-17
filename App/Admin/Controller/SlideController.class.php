<?php
namespace Admin\Controller;
use Think\Controller;
class SlideController extends BaseController {

    public function index(){
        $pic = M('Slide');
        $slides = $pic->order('id desc')->select();
        $this->assign('slides',$slides);
        $this->display();
    }

    public function postSlide()
    {
        $slide = M('Slide');

        $item = array(
            'url'=>$_POST['url']
        );
        //
        $res = $slide->add($item);

        $data = array(
            'ret'=>0,
            'id'=>$res
        );
        $this->ajaxReturn($data);
    }

    public function updateSlide()
    {
        $slide = M('Slide');

        $map['id'] = $_POST['id'];
        $item = array(
            'url'=>$_POST['url']
        );
        //
        if ($slide->where($map)->save($item)) {
            $data = array(
                'ret'=>0,
                'msg'=>'更换成功'
            );
            $this->ajaxReturn($data);
        }
        $this->ajaxReturn(array('ret'=>1,'msg'=>'更换失败'));
    }

    public function deleteSlide()
    {
        $slide = M('Slide');
        $id = $_GET['id'];
        //
        $slide->where('id='.$id)->delete();

        $data = array(
            'ret'=>0,
            'msg'=>'删除成功'
        );
        $this->ajaxReturn($data);
    }


}