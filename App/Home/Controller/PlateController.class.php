<?php
namespace Home\Controller;
use Think\Controller;
class PlateController extends BaseController {

    public function showPlateByName($name)
    {
        $plate = M('Plate');
        $res = $plate->where('name = "'.$name.'"')->find();
        $this->assign('plate',$res);
        if ($name='解决方案') {
            $res_solve = M('Solve')->order('id')->select();
            $this->assign('solves',$res_solve);
        }
        $this->display();
    }

    public function showPlateByMark($mark)
    {
        $plate = M('Plate');
        $res = $plate->where('mark = "'.$mark.'"')->find();
        $this->assign('plate',$res);
        $this->display('showPlateByMark');
    }

}