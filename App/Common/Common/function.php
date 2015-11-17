<?php 


/**
 * 邮件发送函数
 */
/**
* 
*/


	function sendMail($to, $title, $content) {
     
        Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());

    }

    function getArticleType()
    {
        return array(
            'advantage'     => '技术优势',
            'about'         => '关于我们',
            'solve'         => '解决方案',
            'case'          => '成功案例',
            'service'       => '服务项目',
            'customer'      => '客户品牌',
        );
    }

    function getCaseType()
    {
        return array(
            'sql'       =>  '数据库数据恢复案例',
            'raid'      =>  '磁盘阵列数据恢复案例',
            'server'    =>  '服务器数据恢复案例',
            'xxj'       =>  '小型机数据恢复案例',
            'sandick'   =>  '硬盘闪存数据恢复案例',
            'personal'  =>  '个人级数据恢复案例',
        );
    }

    