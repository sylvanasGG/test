<?php
namespace Admin\Controller;
use Think\Controller;
class SolveController extends BaseController {

    public function index()
    {
        $solve = M('Solve');
        $res = $solve->order('id desc')->select();
        $this->assign('solves', $res);
        $this->display();
    }

    /**
     *视图： 新增文章
     **/
    public function create()
    {
        $this->assign('solve_types',SolveLib::$ARTICLE_TYPE);
        $this->display('create');
    }
    public function upload($fileInfo)
    {

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传单个文件
        $info   =   $upload->uploadOne($fileInfo);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            return  $info['savepath'].$info['savename'];
        }
    }
    /**
     *动作： 新增文章
     **/
    public function store()
    {
        if($_FILES['solve_photo']['name'])
        {
            $res = $this->upload($_FILES['solve_photo']);
        }
        $solve = new Solve;
        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['solve_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($solve->add($data))
        {
            session('admin.success_msg','添加成功');
            $this->redirect('Solve/index','', 0, '');
            //$this->success('发表成功');
            // $this->ajaxReturn(array('ret'=>0));
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *视图： 编辑文章
     **/
    public function edit($id)
    {
        $mod = M('Solve');
        $solve = $mod->where('id='.$id)->find();
        $this->assign('solve',$solve);
        $this->display('edit');
    }
    /**
     *动作： 编辑
     **/
    public function update($id)
    {
        if($_FILES['solve_photo']['name'])
        {
            $res = $this->upload($_FILES['solve_photo']);
        }
        $solve = M('Solve');
        if($res)$solve->solve_photo = __ROOT__.'/uploads/'.$res;

        $solve->title = $_POST['title'];
        $solve->desc = $_POST['desc'];
        $solve->content = $_POST['content'];
        if($solve->where('id='.$id)->save())
        {
            session('admin.success_msg','编辑成功');
            // //$this->success('评论成功');
            $this->redirect('Solve/index','', 0, '');
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *动作： 删除文章
     **/
    public function delete()
    {
        $solve = new Solve;
        $comments = new Comment;
        $id = $_GET['id'];
        $comments->where('solve_id='.$id)->delete();
        $solve->where('solve_id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }
    /**
     *动作： 后台展示一篇文章
     **/
    public function show($id)
    {
        $solves = new Solve;
        $solve = $solves->where('id='.$id)->find();
        $comments = $solves->relation(true)->find($id);

        $this->assign('solve',$solve);
        $this->assign('comments',$comments['comment']);
        $this->display('show');
    }


}