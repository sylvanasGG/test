<?php
namespace Admin\Controller;
use Think\Controller;

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

        $case = M('Cases');
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
        $this->assign('case_types',getCaseType());
        $this->display('index'); // 输出模板
    }

    /**
     *视图： 新增案例
     **/
    public function create()
    {
        $this->assign('case_types',getCaseType());
        $this->display('create');
    }

    /**
     *动作： 新增案例
     **/
    public function store()
    {
        if($_FILES['case_photo']['name'])
        {
            $res = $this->upload($_FILES['case_photo']);
        }
        $case = M('Cases');
        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['case_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($case->add($data))
        {
            $this->responseSuccess('添加成功','Case/index');
        }
        $this->responseError('添加失败，请重试','Case/index');
    }
    /**
     *视图： 编辑案例
     **/
    public function edit($id)
    {
        $mod = M('Cases');
        $case = $mod->where('case_id='.$id)->find();
        $this->assign('case_types',getCaseType());
        $this->assign('case',$case);
        $this->display('edit');
    }
    /**
     *动作： 编辑案例
     **/
    public function update($id)
    {
        //$id = $_POST['id'];
        if($_FILES['case_photo']['name'])
        {
            $res = $this->upload($_FILES['case_photo']);
        }
        $case = M('Cases');
        if($res)$case->case_photo = __ROOT__.'/uploads/'.$res;
        $case->case_type = $_POST['case_type'];
        $case->title = $_POST['title'];
        $case->content = $_POST['content'];
        $case->updated_at = date('Y-m-d H:i:s',time());
        if($case->where('case_id='.$id)->save())
        {
            $this->responseSuccess('编辑成功','Case/index');
        }
        $this->responseError('添加失败，请重试','Case/index');
    }
    /**
     *动作： 删除案例
     **/
    public function delete()
    {
        $case = M('Cases');
        $comments = M('Comments');
        $id = $_GET['id'];
        $comments->where('case_id='.$id)->delete();
        $case->where('case_id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }
    /**
     *动作： 后台展示一篇案例
     **/
    public function show($id)
    {
        $cases = M('Cases');
        $case = $cases->where('id='.$id)->find();
        $comments = $cases->relation(true)->find($id);

        $this->assign('case',$case);
        $this->assign('comments',$comments['comment']);
        $this->display('show');
    }


}