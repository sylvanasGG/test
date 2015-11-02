<?php
namespace Admin\Controller;
use Think\Controller;
class PortraitController extends BaseController {

    public function index()
    {
        $portrait = M('Portrait');
        $res = $portrait->order('created_at desc')->select();
        $this->assign('portraits',$res);
        $this->display();
    }

    /**
     *��ͼ�� ��������
     **/
    public function create()
    {
        $this->display('create');
    }
    public function upload($fileInfo)
    {

        $upload = new \Think\Upload();// ʵ�����ϴ���
        $upload->maxSize   =     3145728 ;// ���ø����ϴ���С
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// ���ø����ϴ�����
        $upload->rootPath  =     './uploads/'; // ���ø����ϴ���Ŀ¼
        $upload->savePath  =     ''; // ���ø����ϴ����ӣ�Ŀ¼
        // �ϴ������ļ� 
        $info   =   $upload->uploadOne($fileInfo);
        if(!$info) {// �ϴ�������ʾ������Ϣ
            $this->error($upload->getError());
        }else{// �ϴ��ɹ� ��ȡ�ϴ��ļ���Ϣ
            return  $info['savepath'].$info['savename'];
        }
    }
    /**
     *������ ��������
     **/
    public function store()
    {
        header("Content-type:text/html;charset=utf-8");
        $portrait = M('Portrait');
        $map['title'] = $_POST['title'];
        if ($portrait->where($map)->find()) {

            $this->error('标题重复');
        }
        if($_FILES['portrait_photo']['name'])
        {
            $res = $this->upload($_FILES['portrait_photo']);
        }

        $data = $_POST;
        $user = session('admin.admin');
        $data['author'] = $user['username'];
        $data['portrait_photo'] =$res? __ROOT__.'/uploads/'.$res:__ROOT__.'/Public/img/111.jpg';
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        if($portrait->add($data))
        {
            session('admin.success_msg','创建成功');
            $this->redirect('Portrait/index','', 0, '');
            //$this->success('����ɹ�');
            // $this->ajaxReturn(array('ret'=>0));
        }
        //$this->ajaxReturn(array('ret'=>1));
    }
    /**
     *��ͼ�� �༭����
     **/
    public function edit($id)
    {
        $mod = M('Portrait');
        $portrait = $mod->where('id='.$id)->find();
        $this->assign('portrait',$portrait);
        $this->display();
    }
    /**
     *������ �༭����
     **/
    public function update($id)
    {
        if($_FILES['portrait_photo']['name'])
        {
            $res = $this->upload($_FILES['portrait_photo']);
        }
        $portrait = M('Portrait');
        if($res)$portrait->portrait_photo = __ROOT__.'/uploads/'.$res;
        $portrait->title = $_POST['title'];
        $portrait->content = $_POST['content'];
        $portrait->updated_at = date('Y-m-d H:i:s',time());
        if($portrait->where('id='.$id)->save())
        {
            session('admin.success_msg','编辑成功');
            $this->redirect('Portrait/index','', 0, '');
        }
        $this->ajaxReturn(array('ret'=>1));
    }
    /**
     *������ ɾ������
     **/
    public function delete()
    {
        $portrait = M('Portrait');
        $id = $_GET['id'];
        $portrait->where('id='.$id)->delete();
        $data = array(
            'ret'=>0,
            'msg'=>'删除成功',
        );
        $this->ajaxReturn($data);
    }

}