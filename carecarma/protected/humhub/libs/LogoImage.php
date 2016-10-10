<?php

namespace humhub\libs;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;

class LogoImage
{

    /**
     * @var Integer height of the image
     */
    protected $height = 80;

    /**
     * @var String folder name inside the uploads directory
     */
    protected $folder_images = "logo_image";

    public function __construct()
    {
        
    }

    /**
     * Returns the URl of Logo Image
     *
     * @return String Url of the profile image
     */
    public function getUrl()
    {
        $cacheId = 0;
        $path = "";

        // Workaround for absolute urls in console applications (Cron)
        if (Yii::$app->request->isConsoleRequest) {
            $path = Url::base(true);
        } else {
            $path = Url::base();
        }

        if (file_exists($this->getPath())) {
            $path .= '/uploads/' . $this->folder_images . '/logo.png';
        }
        $path .= '?cacheId=' . $cacheId;
        return $path;
    }


    public function getImage($prefix = "", $scheme = false)
    {
        $cacheId = 0;
        $path = "@web/";
        $path .= 'uploads/' . $this->folder_images . '/logo.png';

        $path .= '?cacheId=' . $cacheId;

        return Url::to(Yii::getAlias($path), $scheme);
    }
    /**
     * Indicates there is a logo image
     *
     * @return Boolean is there a logo image
     */
    public function hasImage()
    {
        return file_exists($this->getPath());
    }

    /**
     * Returns the Path of the logo image
     *
     * @return String Path to the logo image
     */
    public function getPath()
    {
        $path = \Yii::getAlias("@webroot") . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $this->folder_images . DIRECTORY_SEPARATOR;

        if (!is_dir($path))
            mkdir($path);

        $path .= 'logo.png';

        return $path;
    }

    /**
     * Sets a new logo image by given temp file
     *
     * @param CUploadedFile $file
     */
    public function setNew(UploadedFile $file)
    {
        $this->delete();
        move_uploaded_file($file->tempName, $this->getPath());

        ImageConverter::Resize($this->getPath(), $this->getPath(), array('height' => $this->height, 'width' => 0, 'mode' => 'max', 'transparent' => ($file->getExtension() == 'png' && ImageConverter::checkTransparent($this->getPath()))));
    }

    /**
     * Deletes current logo
     */
    public function delete()
    {
        @unlink($this->getPath());
    }

}

?>
