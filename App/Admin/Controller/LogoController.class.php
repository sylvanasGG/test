<?php
namespace Admin\Controller;
use Think\Controller;
class LogoController extends BaseController
{

    public function index()
    {
        $logo = M('Logo');
        $res = $logo->order('id desc')->find();
        $this->assign('logo', $res);
        $this->display();
    }

    public function updatePicture()
    {
        $logo = M('Logo');

        $map['id'] = $_POST['id'];
        $item = array(
            'url'=>$_POST['url']
        );
        //
        if ($logo->where($map)->save($item)) {
            $data = array(
                'ret'=>0,
                'msg'=>'更换成功'
            );
            $this->ajaxReturn($data);
        }
        $this->ajaxReturn(array('ret'=>1,'msg'=>'更换失败'));
    }
}