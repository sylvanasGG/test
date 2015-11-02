<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {

    public function show($id)
    {
        $articles = M('articles');
        $article = $articles->where('article_id='.$id)->find();
        //$comments = $articles->relation(true)->find($id);

        $this->assign('article',$article);
        $this->display('showArticle');
    }

    public function showArticleByType($name)
    {
        $article = M('articles');
        $res = $article->where('article_type = "'.$name.'"')->find();
        $this->assign('article',$res);
        $this->display();
    }

}