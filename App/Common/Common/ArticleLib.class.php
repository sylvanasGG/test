<?php 
namespace Common\Common;

class ArticleLib{
    //技术优势
    const ARTICLE_TYPE_ADVANTAGE = 'advantage';
    //关于我们
    const ARTICLE_TYPE_ABOUT = 'about';
    //解决方案
    const ARTICLE_TYPE_SOLVE = 'solve';
    //成功案例
    const ARTICLE_TYPE_CASE= 'case';
    //服务项目
    const ARTICLE_TYPE_SERVICE = 'service';
    //客户品牌
    const ARTICLE_TYPE_CUSTOMER = 'customer';

    public static $ARTICLE_TYPE = array(
        self::ARTICLE_TYPE_ADVANTAGE        => '技术优势',
        self::ARTICLE_TYPE_ABOUT            => '关于我们',
        self::ARTICLE_TYPE_SOLVE            => '解决方案',
        self::ARTICLE_TYPE_CASE             => '成功案例',
        self::ARTICLE_TYPE_SERVICE          => '服务项目',
        self::ARTICLE_TYPE_CUSTOMER         => '客户品牌',
    );


    //技术优势
    const CASE_TYPE_1   = 'sql';
    //关于我们
    const CASE_TYPE_2   = 'raid';
    //解决方案
    const CASE_TYPE_3   = 'server';
    //成功案例
    const CASE_TYPE_4   = 'xxj';
    //服务项目
    const CASE_TYPE_5   = 'sandick';
    //客户品牌
    const CASE_TYPE_6   = 'personal';

    public static $CASE_TYPE = array(
        self::CASE_TYPE_1        => '数据库数据恢复案例',
        self::CASE_TYPE_2            => '磁盘阵列数据恢复案例',
        self::CASE_TYPE_3            => '服务器数据恢复案例',
        self::CASE_TYPE_4             => '小型机数据恢复案例',
        self::CASE_TYPE_5          => '硬盘闪存数据恢复案例',
        self::CASE_TYPE_6         => '个人级数据恢复案例',
    );
}