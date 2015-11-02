<?php
namespace Admin\Controller;
use Think\Controller;
class PictureController extends BaseController {

    public function index(){
        $pic = M('Pictures');
        $pictures = $pic->order('id desc')->select();
        $this->assign('pictures',$pictures);
        $this->display();
    }

    public function postPicture()
    {
        $picture = M('Pictures');

        $item = array(
            'url'=>$_POST['url']
        );
        //
        $res = $picture->add($item);

        $data = array(
            'ret'=>0,
            'id'=>$res
        );
        //exit(json_encode($data));
        $this->ajaxReturn($data);
    }

    public function updatePicture()
    {
        $picture = M('Pictures');

        $map['id'] = $_POST['id'];
        $item = array(
            'url'=>$_POST['url']
        );
        //
        if ($picture->where($map)->save($item)) {
            $data = array(
                'ret'=>0,
                'msg'=>'更换成功'
            );
            $this->ajaxReturn($data);
        }
        $this->ajaxReturn(array('ret'=>1,'msg'=>'更换失败'));
    }

    public function deletePicture()
    {
         $picture = M('Pictures');
            $id = $_GET['id'];
            //
            $picture->where('id='.$id)->delete();

            $data = array(
                'ret'=>0,
                'msg'=>'ɾ���ɹ�'
            );
            $this->ajaxReturn($data);
    }


}