<?php
namespace Home\Controller;
use Think\Controller;
use Common\Common\ArticleLib;
class CaseController extends BaseController {

    public function index()
    {
        $case = M('Case');
        $res = $case->order('id')->select();

        foreach ($res as $k=>$v) {
            $item[$v['case_type']][] = $v;
        }
        //var_dump($item);exit;

        $this->assign('cases',$item);
        $this->assign('case_types',ArticleLib::$CASE_TYPE);
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