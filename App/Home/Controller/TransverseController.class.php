<?php
namespace Home\Controller;
use Think\Controller;
class TransverseController extends BaseController {

    public function index( )
    {
        $picture = M('Transverse');
        $res = $picture->order('id')->select();
        $this->assign('transverses',$res);
        $this->display();
    }

    public function showById($id)
    {
        $picture = M('Transverse');
        $map['id'] = $id;
        $res = $picture->where($map)->find();
        $this->assign('transverse',$res);
        $this->display();
    }

}