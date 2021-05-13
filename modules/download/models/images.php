<?php
/**
 * @filesource modules/download/models/images.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Download\Images;

/**
 * ลิสต์รายการไฟล์
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ลิสต์รายการไฟล์ คืนค่าเป็น array สำหรับนำไปใช้งานต่อ
     * ที่เก็บไฟล์ ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/'.
     *
     * @param int $id ID ของไฟล์
     * @param string $module ไดเร็คทอรี่เก็บไฟล์ปกติจะเป็นชื่อโมดูล
     * @param array $typies ประเภทของไฟล์ที่ต้องการ
     *
     * @return array
     */
    public static function get($id, $module, $typies)
    {
        $files = array();
        $result = array();
        \Kotchasan\File::listFiles(ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/', $files);
        foreach ($files as $item) {
            if (preg_match('/.*\/('.$id.')\/([a-z0-9]+)\.('.implode('|', $typies).')$/', $item, $match)) {
                $result[] = str_replace(ROOT_PATH, WEB_URL, $item);
            }
        }

        return $result;
    }
}
