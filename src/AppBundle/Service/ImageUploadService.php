<?php

namespace AppBundle\Service;

/**
 * Uploads image and creates thumbnail. Image does not get distorted when resized.
 * http://www.inanzzz.com/index.php/post/0m8s/image-upload-create-thumbnail-center-and-add-padding-in-symfony
 * Use:
 * $this->get('image_uploader')->upload($submission->getFile());
 */
class ImageUploadService
{
    private $imageUploadDir;
    private $thumbnailUploadDir;
    private $thumbnailDefaults;
    private $validExtensions;

    /**
     * @param $root
     * @param $imageUpload
     */
    public function __construct($root, $imageUpload)
    {
        $webDir = $root . '/../web';
        $this->imageUploadDir = $webDir . $imageUpload['image_upload_dir'];
        $this->thumbnailUploadDir = $webDir . $imageUpload['thumbnail_upload_dir'];
        $this->thumbnailDefaults = $imageUpload['thumbnail_defaults'];
        $this->validExtensions = $imageUpload['valid_extensions'];
    }

    /**
     * Image gets uploaded.
     *
     * @param $image
     * @return array|string
     */
    public function upload($image)
    {
        
        $extension = $image->guessExtension();

        if (! in_array($extension, $this->validExtensions)) {
            return 'Invalid image format.';
        }

        $originalName = strip_tags($image->getClientOriginalName());
        $newName = sha1(crc32(time()) . microtime() . $originalName) . '.' . $extension;

        $image->move($this->imageUploadDir, $newName);

        if (! file_exists($this->imageUploadDir . $newName)) {
            return 'Image could not be uploaded.';
        }
        
        $this->createThumbnail(
            $this->imageUploadDir . $newName,
            $this->thumbnailUploadDir . $newName
        );

        return array('originalName' => $originalName, 'newName' => $newName);
    }

    /**
     * Resizes image without adding padding to short edge.
     * Transparency of image is preserved.
     *
     * @param $sourceImage
     * @param $targetImage
     * @return bool
     */
    private function createThumbnail($sourceImage, $targetImage)
    {
        list($sourceWidth, $sourceHeight, $sourceType) = getimagesize($sourceImage);

        switch ($sourceType) {
            case IMAGETYPE_GIF:
                $sourceGdImage = imagecreatefromgif($sourceImage);
                break;
            case IMAGETYPE_JPEG:
                $sourceGdImage = imagecreatefromjpeg($sourceImage);
                break;
            case IMAGETYPE_PNG:
                $sourceGdImage = imagecreatefrompng($sourceImage);
                break;
        }

        if ($sourceGdImage === false) {
            return false;
        }

        $sourceAspectRatio = ($sourceWidth / $sourceHeight);
        $thumbnailAspectRatio = ($this->thumbnailDefaults['width'] / $this->thumbnailDefaults['height']);

        if ($sourceWidth <= $this->thumbnailDefaults['width'] && $sourceHeight <= $this->thumbnailDefaults['height']) {
            $thumbnailWidth = $sourceWidth;
            $thumbnailHeight = $sourceHeight;
        } elseif ($thumbnailAspectRatio > $sourceAspectRatio) {
            $thumbnailWidth = (int) ($this->thumbnailDefaults['height'] * $sourceAspectRatio);
            $thumbnailHeight = $this->thumbnailDefaults['height'];
        } else {
            $thumbnailWidth = $this->thumbnailDefaults['width'];
            $thumbnailHeight = (int) ($this->thumbnailDefaults['width'] / $sourceAspectRatio);
        }

        $thumbnailGdImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

        imagecopyresampled(
            $thumbnailGdImage,
            $sourceGdImage,
            0,
            0,
            0,
            0,
            $thumbnailWidth,
            $thumbnailHeight,
            $sourceWidth,
            $sourceHeight
        );

        //clearstatcache();

        switch ($sourceType) {
            case IMAGETYPE_GIF:
                imagegif($thumbnailGdImage, $targetImage, 90);
                break;
            case IMAGETYPE_JPEG:
                imagejpeg($thumbnailGdImage, $targetImage, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumbnailGdImage, $targetImage, 9);
                break;
        }

        imagedestroy($sourceGdImage);
        imagedestroy($thumbnailGdImage);

        return true;
    }
}