<?php


namespace App\Components;

class ImageProcessor
{
    public $image;
    public $imageType;

    public function load($filename)
    {
        $imageInfo = getimagesize($filename);
        $this->imageType = $imageInfo[2];
        if ($this->imageType === IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->imageType === IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->imageType === IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
        return $this;
    }

    public function save($filename, $imageType = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
    {
        if ($imageType === IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($imageType === IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($imageType === IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions !== null) {
            chmod($filename, $permissions);
        }
    }

    public function output($imageType = IMAGETYPE_JPEG)
    {
        if ($imageType === IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($imageType === IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($imageType === IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }

    public function resizeTo($value) {
        if ($this->getWidth() > $this->getheight()) {
            $this->resizeToWidth($value);
        } else {
            $this->resizeToHeight($value);
        }
        return $this;
    }

    public function resizeToHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
        return $this;
    }

    public function resizeToWidth($width)
    {
        $ratio  = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
        return $this;
    }

    public function scale($scale)
    {
        $width  = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
        return $this;
    }

    public function resize($width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        imagecolortransparent($newImage, imagecolorallocate($newImage, 0, 0, 0)); // делаем фон прозрачным, иначе будет черным
        //imagefill($newImage, 0, 0, imagecolorallocate($newImage, 255, 255, 255)); // заливаем фон созданной картинки белым, иначе будет черным
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $newImage;
        return $this;
    }
}
