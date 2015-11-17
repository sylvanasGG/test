<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {

    public function index()
    {
        $map = array();
        $title = $author = $article_type = $updated_at_start = $updated_at_end = '';
        $title = $_GET['title'];
        if ($title != null) {
            $map['title'] = array('like', "%$title%");
        }

        $author = $_GET['author'];
        if ($author != null) {
            $map['author'] = array('like', "%$author%");
        }

        $article_type = $_GET['article_type'];
        if ($article_type != null) {
            $map['article_type'] = array('eq', $article_type);
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
        $this->assign('article_type', $article_type);
        $this->assign('updated_at_start', $updated_at_start);
        $this->assign('updated_at_end', $updated_at_end);

        $article = M('Articles');
        $count      = $article->where($map)->order('updated_at desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('header','页');
        $Page->setConfig('prev','');
        $Page->setConfig('next','');
        $Page->setConfig('first','>>');
        $Page->setConfig('end','<<');
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $article->where($map)->order('updated_at desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('articles',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('article_types',getArticleType());
        $this->display('index'); // 输出模板
    }

    /**
     *视图： 新增文章
     **/
    public function create()
    {
        $this->assign('article_types',getArticleType());
        $this->display('create');
    }

    /**
     *动作： 新增文章
     **/
    public function store()
    {
        if($_FILES['article_photo']['name'])
        {
            $res = $this->upload($_FILES['article_photo']);
        }
        $article = M('Articles');
        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['article_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($article->add($data))
        {
            $this->responseSuccess('添加成功','Article/index');
        }
        $this->responseError('添加失败，请重试','Article/index');
    }
    /**
     *视图： 编辑文章
     **/
    public function edit($id)
    {
        $mod = M('Articles');
        $article = $mod->where('article_id='.$id)->find();
        $this->assign('article_types',getArticleType());
        $this->assign('article',$article);
        $this->display('edit');
    }
    /**
     *动作： 编辑文章
     **/
    public function update($id)
    {
        //$id = $_POST['id'];
        if($_FILES['article_photo']['name'])
        {
            $res = $this->upload($_FILES['article_photo']);
        }
        $article = M('Articles');
        if($res)$article->article_photo = __ROOT__.'/uploads/'.$res;
        $article->article_type = $_POST['article_type'];
        $article->title = $_POST['title'];
        $article->content = $_POST['content'];
        $article->updated_at = date('Y-m-d H:i:s',time());
        if($article->where('article_id='.$id)->save())
        {
            $this->responseSuccess('编辑成功','Article/index');
        }
        $this->responseError('编辑失败，请重试','Article/index');
    }
    /**
     *动作： 删除文章
     **/
    public function delete()
    {
        $article = M('Articles');
        $comments = M('Comments');
        $id = $_GET['id'];
        $comments->where('article_id='.$id)->delete();
        $article->where('article_id='.$id)->delete();
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
        $articles = M('Articles');
        $article = $articles->where('id='.$id)->find();
        $comments = $articles->relation(true)->find($id);

        $this->assign('article',$article);
        $this->assign('comments',$comments['comment']);
        $this->display('show');
    }

    
}