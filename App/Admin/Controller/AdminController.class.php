<?php
namespace Admin\Controller;


class AdminController extends BaseController {



    public function index(){

        $this->display('index');
    }

    public function unsetSession()
    {
        session('admin.success_msg',null);
        session('admin.error_msg',null);
        $data = array(
            'ret'=>0,
        );
        $this->ajaxReturn($data);
    }
}