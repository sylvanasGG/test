<?php
namespace Home\Controller;
use Think\Controller;
class SolveController extends BaseController {

    public function index()
    {
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