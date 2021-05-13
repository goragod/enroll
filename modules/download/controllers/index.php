<?php
/**
 * @filesource modules/download/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Download\Index;

use Kotchasan\Mime;

/**
 * แสดงผลไฟล์ดาวน์โหลด
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ลิสต์รายการไฟล์ คืนค่าเป็น HTML สำหรับแสดงผล
     * ที่เก็บไฟล์ ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/'.
     *
     * @param int $id ID ของไฟล์
     * @param string $module ไดเร็คทอรี่เก็บไฟล์ปกติจะเป็นชื่อโมดูล
     * @param array $typies ประเภทของไฟล์ที่ต้องการ
     * @param int $owner_id มากกว่า 0 ตรวจสอบกับคน Login ถ้าตรงกันถึงจะลบได้, 0 ไม่สามารถลบได้
     *
     * @return string
     */
    public static function init($id, $module, $typies, $owner_id = 0)
    {
        $files = array();
        \Kotchasan\File::listFiles(ROOT_PATH.DATA_FOLDER.$module.'/'.$id.'/', $files);
        $elem = uniqid();
        $content = '<div class="file_list clear" id='.$elem.'>';
        foreach ($files as $item) {
            if (preg_match('/.*\/('.$id.')\/([a-z0-9]+)\.('.implode('|', $typies).')$/', $item, $match)) {
                // id ของไฟล์
                $uid = uniqid();
                // รูปภาพ
                $isImage = in_array($match[3], array('bmp', 'png', 'gif', 'jpg', 'jpeg', 'jfif'));
                // ข้อมูลไฟล์ดาวน์โหลด
                $_SESSION[$uid] = array(
                    'file' => $item,
                    'mime' => $match[3] == 'pdf' || $isImage ? Mime::get($match[3]) : 'application/octet-stream',
                    'owner_id' => $owner_id,
                );
                $content .= '<div id="item_'.$uid.'">';
                if ($isImage) {
                    $content .= '<a class="preview" href="'.WEB_URL.'modules/download/download.php?id='.$uid.'" title="{LNG_Download}" target="preview" style="background-image:url('.str_replace(ROOT_PATH, WEB_URL, $item).')"></a>';
                } else {
                    $content .= '<a class="preview file-thumb" href="'.WEB_URL.'modules/download/download.php?id='.$uid.'" title="{LNG_Download}" target="preview"><span>'.strtoupper($match[3]).'</span></a>';
                }
                if ($owner_id > 0) {
                    $content .= '<a class="icon-delete" id=delete_'.$uid.' title="{LNG_Delete}"></a>';
                }
                $content .= '</div>';
            }
        }
        $content .= '</div><script>initDownload("'.$elem.'")</script>';

        return $content;
    }
}
