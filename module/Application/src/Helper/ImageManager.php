<?php
namespace Application\Helper;

/**
 * Helps to manage images
 *
 * Class ImageManager
 * @package Application\Helper
 */
class ImageManager {

    /**
     * Resizes the image, keeping its aspect ratio.
     *
     * @param $filePath
     * @param int $desiredWidth
     * @return string
     */
    public static function resizeImage($filePath, $newFilePath, $desiredWidth = 350) {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Calculate aspect ratio
        $aspectRatio = $originalWidth/$originalHeight;

        // Calculate the resulting height
        $desiredHeight = $desiredWidth/$aspectRatio;

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Save the resized image.
        imagejpeg($resultingImage, $newFilePath, 80);
    }

    /**
     * Resizes the image, does not keeping its aspect ratio.
     *
     * @param $filePath
     * @param int $desiredWidth
     * @param int $desiredHeight
     * @return string
     */
    public static function resizeImageFull($filePath, $newFilePath, $desiredWidth, $desiredHeight) {
        // Get original image dimensions.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Resize the image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0,
            $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Save the resized image.
        imagejpeg($resultingImage, $newFilePath, 80);
    }
}