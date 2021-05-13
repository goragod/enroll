<?php
/**
 * @filesource modules/download/models/action.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Download\Action;

use Gcms\Login;
use Kotchasan\Http\Request;

/**
 * ลบไฟล์.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ลบไฟล์.
     *
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $ret = array();
        // session, referer
        if ($request->initSession() && $request->isReferer()) {
            if (preg_match('/delete_([a-z0-9]+)$/', $request->post('id')->toString(), $match)) {
                if (isset($_SESSION[$match[1]])) {
                    $file = $_SESSION[$match[1]];
                    // สมาชิก
                    $login = Login::isMember();
                    if (!$login) {
                        // ไม่ใช่สมาชิกตรวจสอบว่าเป็นคนลงทะเบียนหรือเปล่า
                        $login = isset($_SESSION['enroll']) ? $_SESSION['enroll'] : null;
                    }
                    if (!empty($file['owner_id']) && $file['owner_id'] == $login['id'] && is_file($file['file'])) {
                        @unlink($file['file']);
                        // คืนค่ารายการที่ลบ
                        $ret['remove'] = 'item_'.$match[1];
                    }
                }
            }
        }
        // คืนค่า JSON
        if (!empty($ret)) {
            echo json_encode($ret);
        }
    }
}
