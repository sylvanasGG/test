<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
class IndexController extends BaseController {
    public function index(){
        //轮换图片
//        $picture = M('Pictures');
//        $res = $picture->order('id')->select();
//        $this->assign('pictures',$res);

        //横向文章
//        $transverse = M('Transverse');
//        $res_transverses = $transverse->order('id')->select();
//        $this->assign('transverses',$res_transverses);
//
//        //纵向文章
//        $portrait = M('Portrait');
//        $res_portraits = $portrait->order('id')->select();
//        $this->assign('portraits',$res_portraits);
//        $this->display();

        $this->display();

    }
    
}