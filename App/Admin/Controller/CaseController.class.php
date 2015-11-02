<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Common\ArticleLib;
class CaseController extends BaseController {

    public function index()
    {
        $map = array();
        $title = $author = $case_type = $updated_at_start = $updated_at_end = '';
        $title = $_GET['title'];
        if ($title != null) {
            $map['title'] = array('like', "%$title%");
        }

        $author = $_GET['author'];
        if ($author != null) {
            $map['author'] = array('like', "%$author%");
        }

        $case_type = $_GET['case_type'];
        if ($case_type != null) {
            $map['case_type'] = array('eq', $case_type);
        }

        $updated_at_start = $_GET['updated_at_start'];
        if ($updated_at_start != null) {
            $map['updated_at'] = array('egt', $updated_at_start);
        }

        $updated_at_end = $_GET['updated_at_end'];
        if ($updated_at_end != null) {
            $map['updated_at'] = array('elt', $updated_at_end);
        }

        if ($updated_at_start != null && $updated_at_end != null) {
            $map['updated_at'] = array('between', array($updated_at_start, $updated_at_end));
        }
        $this->assign('title', $title);
        $this->assign('author', $author);
        $this->assign('case_type', $case_type);
        $this->assign('updated_at_start', $updated_at_start);
        $this->assign('updated_at_end', $updated_at_end);

        $case = M('Case');
        $count      = $case->where($map)->order('updated_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $case->where($map)->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('cases',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('case_types',ArticleLib::$CASE_TYPE);
        $this->display('index'); // 输出模板
    }

    /**
     *视图： 新增案例
     **/
    public function create()
    {
        $this->assign('case_types',ArticleLib::$CASE_TYPE);
        $this->display('create');
    }
    /**
     *动作： 新增案例
     **/
    public function store()
    {

        $case = M('Case');
        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($case->add($data))
        {
            session('admin.success_msg','添加成功');
            $this->redirect('Case/index','', 0, '');
            //$this->success('发表成功');
            // $this->ajaxReturn(array('ret'=>0));
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *视图： 编辑案例
     **/
    public function edit($id)
    {
        $case = M('Case');
        $case = $case->where('id='.$id)->find();
        $this->assign('case_types',ArticleLib::$CASE_TYPE);
        $this->assign('case',$case);
        $this->display('edit');
    }
    /**
     *动作： 编辑案例
     **/
    public function update($id)
    {
        $case = M('Case');
        $case->case_type = $_POST['case_type'];
        $case->title = $_POST['title'];
        $case->storage_info = $_POST['storage_info'];
        $case->fault_desc = $_POST['fault_desc'];
        $case->recovery_process = $_POST['recovery_process'];
        $case->recovery_result = $_POST['recovery_result'];
        $case->tips = $_POST['tips'];
        $case->remark = $_POST['remark'];
        $case->updated_at = date('Y-m-d H:i:s',time());
        if($case->where('id='.$id)->save())
        {
            session('admin.success_msg','编辑成功');
            $this->redirect('Case/index','', 0, '');
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *动作： 删除案例
     **/
    public function delete()
    {
        $case = M('Case');
        $id = $_GET['id'];
        $case->where('id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }

}