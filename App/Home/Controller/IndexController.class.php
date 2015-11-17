<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\ArticleModel as Article;
class IndexController extends BaseController {
    public function index(){
        //轮换图片
        $picture = M('Slide');
        $res = $picture->order('id')->select();
        $this->assign('slides',$res);


        $this->display();

    }
    
}