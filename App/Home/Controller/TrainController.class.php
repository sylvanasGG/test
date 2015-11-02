<?php
namespace Home\Controller;
use Think\Controller;
class TrainController extends BaseController {

    public function index()
    {
        $picture = M('Pictures');
        $res = $picture->order('id')->select();
        $this->assign('pictures',$res);
        $this->display();
    }

    public function showById($id)
    {
        $solve = M('Solve');
        $map['id'] = $id;
        $res = $solve->where($map)->find();
        $this->assign('data', $res);
        $this->display();
    }

}