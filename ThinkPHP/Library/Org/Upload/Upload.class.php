<?php
/**
 * 文件上传处理工具
 *
 * @category   ORG
 * @package    ORG
 * @subpackage Request
 * @author     hzhi <huzhi@baoxiane.com>
 */
class Upload
{
    /**
     * $file = $_FILES['userfile']
     * @var null
     */
    private $file = null;

    /**
     * 文件上传的绝对路径
     * $basePath = '/home/guosheng/webroot/safety/Public/uploads/img/';
     * @var string
     */
    private $basePath = 'Public/uploads/img';

    /**
     * 文件上传子路径
     * $path = date('Y'). '/'. date('m'). '/'. date('d'). '/';
     * @var string
     */
    private $path = '';

    /**
     * 上传文件的原始名
     * $originFileName = $this->file['name'];
     * $originFileName = $_FILES['userfile']['name']
     * @var string
     */
    private $originFileName = '';

    /**
     * 上传文件的临时文件名
     * $tmpFilename = $this->file['tmp_name'];
     * $tmpFilename = $_FILES['userfile']['tmp_name']
     * @var string
     */
    private $tmpFilename;

    /**
     * 上传文件经过处理后的绝对路径+文件名+风格+扩展名
     * @var null
     */
    private $destFile = null;

    /**
     * 上传文件经过处理后的文件名(不包括文件扩展名)
     * $filename = md5(time().$this->tmpFilename);
     */
    private $filename = '';

    /**
     * 文件的扩展名(jpg, png, gif)
     * @var string
     */
    private $fileExtName = '';

    /**
     * 文件的mime类型
     */
    private $fileMime = null;

    /**
     * 上传文件的大小
     * $fileSize = intval($this->file['size']);
     * $fileSize = intval($_FILES['userfile']['size']);
     * @var string
     */
    private $fileSize = 0;

    /**
     * 不同大小，不同风格的图片，例如(small, medium, large)
     * @var string
     */
    private $suffix = '';


    //图像相关变量
    private $maxWidth = 0;
    private $maxHeight = 0;

    /**
     * 上传成功后进行图像处理的原始图片信息
     * @var null
     */
    private $originImage = null;

    /**
     * 上传图像的宽度
     * @var int
     */
    private $originImageWidth = 0;

    /**
     * 上传图像的高度
     * @var int
     */
    private $originImageHeight = 0;

    private $workImage = null;
    private $workImageWidth = 0;
    private $workImageHeight = 0;


    private $imgMime = array(
        'image/gif',
        'image/jpg',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/x-png',
    );

    /**
     * @param $file
     * @throws \Exception "not file selected" $file参数不是一个文件
     */
    public function __construct($file = null)
    {
        if (isset($file['tmp_name']) && $file['name'] && empty($file['tmp_name'])) {
            throw new \Exception("file size too large");
        }

        if ($file !== null) {
            if(isset($file['tmp_name']) && !empty($file['tmp_name'])) {
                $this->file = $file;
                $this->originFileName = $this->file['name'];
                $this->fileSize = intval($this->file['size']);
                $this->tmpFilename = $this->file['tmp_name'];

                $this->getMime($this->tmpFilename);

                if(in_array($this->fileMime, $this->imgMime)) {
                    $imageInfo = getimagesize($this->tmpFilename);//取得图像大小
                    $this->originImageWidth = $imageInfo[0];//图像的宽度
                    $this->originImageHeight = $imageInfo[1];//图像的高度
                }

            } else {
                throw new \Exception("not file selected");
            }
        }
    }

    public function __destruct()
    {
        if (is_resource($this->originImage)) {
            imagedestroy($this->originImage);
        }

        if (is_resource($this->workImage)) {
            imagedestroy($this->workImage);
        }

    }

    /**
     * @return int 返回文件的大小，单位为KB
     */
    public function getSize()
    {
        return round($this->fileSize / 1024);
    }

    /**
     * 设置上传文件绝对基路径 比如 /www/deyi/projectname/
     * @param $path
     * @return $this
     */
    public function setBasePath($path)
    {
        $this->basePath = $path;
        return $this;
    }

    /**
     * 返回 mime 类型
     * @param $file
     * $file = $_FILES['userfile']['tmp_name']
     * @return mixed|null
     */
    public function getMime($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);//创建一个 fileinfo 资源
        $this->fileMime = finfo_file($finfo, $file);//返回一个文件的信息
        return $this->fileMime;
    }

    /**
     * 设置上传文件子路径  比如 uploads/
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * 获取上传文件原始文件名
     * @return mixed
     */
    public function getOriginFilename()
    {
        return $this->originFileName;
    }

    /**
     * 设置或者获取文件的扩展名
     * @return string
     */
    public function getFileType()
    {
        $fileExt = '';
        if ($this->fileExtName === '') {
            switch($this->fileMime) {
                case 'image/jpg':
                case 'image/jpeg':
                case 'image/pjpeg':
                    $fileExt = 'jpg';
                    break;
                case 'image/png':
                case 'image/x-png':
                    $fileExt = 'png';
                    break;
                case 'image/gif':
                    $fileExt = 'gif';
                    break;
            }
            return $this->fileExtName = $fileExt;
        }

        return $this->fileExtName;
    }

    /**
     * 设置新文件名(不包括扩展名)
     * @param $filename
     * @return $this
     */
    public function setFileName($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * 设置文件的扩展名
     * @param $fileExtName
     * @return $this
     */
    public function setExtName($fileExtName)
    {
        $this->fileExtName = $fileExtName;
        return $this;
    }

    /**
     * 设置不同大小，不同风格的图片，例如(small, medium, large)
     * @param $str
     */
    public function setSuffix($str)
    {
        $this->suffix = $str;
    }

    /**
     * 保存上传文件
     * @return string
     */
    public function save()
    {
        if(empty($this->path)) {
            $this->path = date('Y'). '/'. date('m'). '/'. date('d'). '/';//设置文件相对路径
        }
        if(empty($this->filename)) {
            $this->filename = md5(time().$this->tmpFilename);//设置文件新文件名(不包含文件扩展名)
        }
        $savePath = $this->basePath. $this->path;//文件最后保存的路径

        if(!is_dir($savePath)) {
            @mkdir($savePath, 0777, true);
            if(!is_dir($savePath)) exit('no write permission');
        }
        $this->destFile = $savePath. $this->filename. $this->suffix. '.'. $this->getFileType();
        move_uploaded_file($this->tmpFilename, $savePath. $this->filename. $this->suffix. '.'. $this->getFileType());
        return  $this->path. $this->filename. '.'. $this->getFileType();
    }

    /**
     * 设置最终的绝对路径+文件名+风格+扩展名
     * @param $file
     * @return $this
     * @throws Exception
     */
    public function setDestFile($file)
    {
        if (!is_file($file)) {
            throw new \Exception("not file selected");
        }
        $this->destFile = $file;
        $this->getMime($file);
        $imageInfo = getimagesize($this->destFile);
        $this->originImageWidth = $imageInfo[0];
        $this->originImageHeight = $imageInfo[1];
        return $this;
    }

    /**
     * 获取最终的绝对路径+文件名+风格+扩展名
     * @return null
     */
    public function getDestFile()
    {
        return $this->destFile;
    }

    //图像处理相关的方法

    private function initImage() {
        switch($this->getFileType()) {
            case 'jpg':
                $this->originImage = imagecreatefromjpeg($this->destFile);
                break;
            case 'png':
                $this->originImage = imagecreatefrompng($this->destFile);
                break;
            case 'gif':
                $this->originImage = imagecreatefromgif($this->destFile);
                break;
        }
        return $this;
    }

    public function getWidth()
    {
        return $this->originImageWidth;
    }

    public function getHeight()
    {
        return $this->originImageHeight;
    }

    /*
     * 获取图片宽高百分比
     */
    public function getPercent()
    {
        return round($this->originImageWidth / $this->originImageHeight, 2);
    }


    /**
     * 检测是不是图片格式文件
     * return  Bool 返回布尔型
     */
    public function isImage($type = array('jpg','png','gif'))
    {
        return is_array($type) && in_array($this->getFileType(), $type);
    }


    /**
     * 图像缩略保存
     * @param array $thumbOption  array('name' => array('width' => number, 'height' => number))
     * @return $this
     */
    public function thumb($thumbOption = array())
    {
        $this->initImage();
        if (is_array($thumbOption)) {
            foreach($thumbOption as $name => $option) {
                $this->resize($option['width'], $option['height']);
                imageinterlace($this->workImage, 1);
                $this->suffix = !empty($name) ? '_'.$name : '';
                $thumbFile = $this->basePath. $this->path. $this->filename. $this->suffix. '.'. $this->getFileType();
                switch ($this->getFileType()) {
                    case 'jpg':
                        imagejpeg($this->workImage, $thumbFile, 95);
                        break;
                    case 'png':
                        imagegif($this->workImage, $thumbFile);
                        break;
                    case 'gif':
                        imagegif($this->workImage, $thumbFile);
                        break;
                }
            }
        }
        return $this;
    }

    /**
     * 图片缩小处理
     * @param int $maxWidth
     * @param int $maxHeight
     * @param bool $zoom
     * @return $this
     */
    public function resize($maxWidth = 0, $maxHeight = 0, $zoom = false)
    {
        if (!$zoom) {
            $this->maxWidth = (intval($maxWidth) > $this->originImageWidth) ? $this->originImageWidth : $maxWidth;
            $this->maxHeight = (intval($maxHeight) > $this->originImageHeight) ? $this->originImageHeight : $maxHeight;
        } else {
            $maxWidth = $maxWidth == 0 ? $maxHeight : $maxWidth;
            $maxHeight = $maxHeight == 0 ? $maxWidth : $maxHeight;
            $this->maxWidth = $maxWidth;
            $this->maxHeight = $maxHeight;
        }

        $newSize = $this->calcImageSize($this->originImageWidth, $this->originImageHeight, $zoom);

        $this->workImageWidth = $newSize['width'];
        $this->workImageHeight = $newSize['height'];
        $this->workImage = imagecreatetruecolor($this->workImageWidth, $this->workImageHeight);

        imagecopyresampled(
            $this->workImage,
            $this->originImage,
            0,
            0,
            0,
            0,
            $this->workImageWidth,
            $this->workImageHeight,
            $this->originImageWidth,
            $this->originImageHeight
        );

        return $this;
    }


    private function calcImageSize($width, $height, $zoom = false)
    {
        $newWidth = $width;
        $newHeight = $height;

        if (!$zoom) {
            if ($this->maxWidth > 0 && $this->maxWidth < $newWidth) {
                $newWidthPercent = (100 * $this->maxWidth) / $width;
                $newHeight = intval(($height * $newWidthPercent) / 100);
                $newWidth = $this->maxWidth;
            }

            if ($this->maxHeight > 0 && $this->maxHeight < $newHeight) {
                $newHeightPercent = (100 * $this->maxHeight) / $height;
                $newWidth = intval(($width * $newHeightPercent) / 100);
                $newHeight = $this->maxHeight;
            }
        } else {
            if ($this->maxWidth > 0 && $this->maxWidth > $newWidth) {
                $newWidthPercent = (100 * $this->maxWidth) / $width;
                $newHeight = intval(($height * $newWidthPercent) / 100);
                $newWidth = $this->maxWidth;
            }

            if ($this->maxHeight > 0 && $this->maxHeight > $newHeight) {
                $newHeightPercent = (100 * $this->maxHeight) / $height;
                $newWidth = intval(($width * $newHeightPercent) / 100);
                $newHeight = $this->maxHeight;
            }
        }


        return array('width' => $newWidth, 'height' => $newHeight);
    }

    /**
     * 裁切图像中间部份功能
     * @param $cropWidth
     * @param null $cropHeight  为空时，默认是裁切成正方形
     * @return $this
     */
    public function cropFromCenter($cropWidth, $cropHeight = null)
    {
        $this->initImage();
        $cropHeight = $cropHeight === null ? $cropWidth : $cropHeight;

        $needReduce = $cropWidth < $this->originImageWidth && $cropHeight < $this->originImageHeight;
        if ($needReduce) {
            //缩小图片到workingImage
            $originPercent = $this->originImageWidth / $this->originImageHeight;
            $corpPercent = $cropWidth / $cropHeight;

            if ($originPercent > $corpPercent) {
                $this->resize(0, $cropHeight);
            } else {
                $this->resize($cropWidth, 0);
            }
        } else {
            //放大图片到workingImage
            $this->resize($cropWidth, 0, true);
        }

        $cropX = $needReduce || ($this->workImageWidth > $cropWidth) ? intval(($this->workImageWidth - $cropWidth) / 2) : 0;
        $cropY = $needReduce || ($this->workImageHeight > $cropHeight) ? intval(($this->workImageHeight - $cropHeight) / 2) : 0;

        if ($cropWidth == $this->workImageWidth) {
            $cropX = 0;
        }

        if ($cropHeight == $this->workImageHeight) {
            $cropY = 0;
        }

        $cropImage = $this->crop($cropX, $cropY, $cropWidth, $cropHeight, "_{$cropWidth}x{$cropHeight}");

        return $this;
    }

    /**
     * 按缩放比例，定点裁切图片
     * @param $leftX   左上x坐标
     * @param $leftY   左上y坐标
     * @param $zoom   　缩放值
     * @param $w       宽度
     * @param null $h   高度，缺省null时，以宽度为高度，也就是正方形
     * @return $this
     */
    public function cropFromZoom($leftX, $leftY, $zoom, $w, $h = null)
    {
        $this->initImage();
        $h = $h === null ? $w : $h;

        if ($zoom < 1) {
            //缩小workingImage到$zoom比例
            $resizeW = ceil($this->originImageWidth * $zoom);
            $resizeH = ceil($this->originImageHeight * $zoom);
            $this->resize($resizeW, $resizeH);
        }

        $cropImage = $this->crop($leftX, $leftY, $w, $h);

        return $this;
    }

    /**
     * 图片先裁切再缩放到指定尺寸
     * @param $x1   源图左上x坐标
     * @param $y1   源图左上y坐标
     * @param $x2   源图右下x坐标
     * @param $y2   源图左下y坐标
     * @param $w    缩放后图的宽度
     * @param null $h   缩放后图的高度,不填默认等宽方形
     * @return $this
     */
    public function cropFromPoint($x1, $y1, $x2, $y2, $w, $h = null)
    {

        $this->initImage();

        $h = $h === null ? $w : $h;

        $this->workImageWidth = floor($x2 - $x1);
        $this->workImageHeight = floor($y2 - $y1);
        $this->workImageWidth = $this->workImageHeight == $h ? $w : $this->workImageWidth;
        $this->workImageHeight = $this->workImageWidth == $h ? $w : $this->workImageHeight;

        $this->workImage = imagecreatetruecolor($this->workImageWidth, $this->workImageHeight);
        imagecopyresampled(
            $this->workImage,
            $this->originImage,
            0,
            0,
            $x1,
            $y1,
            $this->workImageWidth,
            $this->workImageHeight,
            $this->workImageWidth,
            $this->workImageHeight
        );

        //缩小workingImage到$zoom比例
        $reizeImage = imagecreatetruecolor($w, $h);
        imagecopyresampled(
            $reizeImage,
            $this->workImage,
            0,
            0,
            0,
            0,
            $w,
            $h,
            $this->workImageWidth,
            $this->workImageHeight
        );

        imageinterlace($reizeImage, 1);

        $thumbFile = $this->basePath. $this->path. $this->filename.'.'. $this->getFileType();

        switch ($this->getFileType()) {
            case 'jpg':
                imagejpeg($reizeImage, $thumbFile, 95);
                break;
            case 'png':
                imagegif($reizeImage, $thumbFile);
                break;
            case 'gif':
                imagegif($reizeImage, $thumbFile);
                break;
        }
        if (is_resource($reizeImage)) {
            imagedestroy($reizeImage);
        }
        return $this;
    }

    /**
     * @param $x
     * @param $y
     * @param $w
     * @param $h
     * @param string $suffix
     * @param int $interlace
     * @return $this
     */
    public function crop($x, $y, $w, $h, $suffix = '', $interlace = 1)
    {
        $cropImage = imagecreatetruecolor($w, $h);

        imagecopyresampled(
            $cropImage,
            $this->workImage,
            0,
            0,
            $x,
            $y,
            $w,
            $h,
            $w,
            $h
        );

        imageinterlace($cropImage, $interlace);

        $thumbFile = $this->basePath. $this->path. $this->filename. "{$suffix}.". $this->getFileType();

        switch ($this->getFileType()) {
            case 'jpg':
                imagejpeg($cropImage, $thumbFile, 95);
                break;
            case 'png':
                imagegif($cropImage, $thumbFile);
                break;
            case 'gif':
                imagegif($cropImage, $thumbFile);
                break;
        }

        if (is_resource($cropImage)) {
            imagedestroy($cropImage);
        }

        return $this;
    }

    //TODO 水印功能
    public function waterMark()
    {

    }
}
