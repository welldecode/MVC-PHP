<?php

namespace app\library;

use \Imagick;

class Images
{

    public static function load_image(string $imagePath, int $width = 250, int  $height = 250)
    {

        if (self::hasCache($imagePath)) {
            return file_get_contents(self::pathCache($imagePath));
        }

        $path = dirname(__FILE__, 3);

        $img = new Imagick($path . "/public/assets/img/uploads/" . $imagePath);

        $img->setImageFormat('webp');
        $img->thumbnailImage($width, $height);

        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality(80);

        $base = base64_encode($img);

        file_put_contents(self::pathCache($imagePath), $base);

        return $base;
    }

    private static function hasCache(string $image_name)
    { 
        return file_exists(self::pathCache($image_name));
    }

    private static function pathCache(string $image_name)
    {
        $path = dirname(__FILE__, 3);
        return "$path/public/assets/cache/$image_name";
    }
}
