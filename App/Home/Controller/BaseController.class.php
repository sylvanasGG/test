<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function __construct()
    {
        parent::__construct();

        $plates = M('Plate');
        $res = $plates->order('id')->select();
        $this->assign('headers',$res);

        //友情链接
        $link = M('Link');
        $res_link = $link->order('id')->select();
        $this->assign('links',$res_link);

        //logo
        $logo = M('Logo');
        $res_logo = $logo->order('id')->find();
        $this->assign('logo',$res_logo);
    }

}