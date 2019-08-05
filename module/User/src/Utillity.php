<?php
namespace User;
/**
 * Description of Utillity
 *
 */
class Utillity {

    public static function recurse_copy($src, $dst) {
        $dir = opendir($src);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    if(!is_dir($dst . '/' . $file)) {
                        mkdir($dst . '/' . $file);
                    }
                    self::recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

}
