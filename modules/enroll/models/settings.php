<?php
/**
 * @filesource modules/enroll/models/settings.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Enroll\Settings;

use Gcms\Config;
use Gcms\Login;
use Kotchasan\File;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * บันทึกการตั้งค่าโมดูล.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * รับค่าจาก settings.php
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, can_config, ไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login) && Login::checkPermission($login, 'can_config')) {
                try {
                    // โหลด config
                    $config = Config::load(ROOT_PATH.'settings/config.php');
                    $config->school_name = $request->post('school_name')->topic();
                    $config->enroll_study_plan_count = max(1, $request->post('enroll_study_plan_count')->toInt());
                    $config->enroll_w = max(100, $request->post('enroll_w')->toInt());
                    $config->enroll_csv_language = $request->post('enroll_csv_language')->filter('A-Z0-9\-');
                    $config->enroll_country = $request->post('enroll_country')->filter('A-Z');
                    $config->enroll_editable = $request->post('enroll_editable', array())->toInt();
                    $config->enroll_begin = $request->post('enroll_begin')->date();
                    $config->enroll_end = $request->post('enroll_end')->date();
                    // save config
                    if (Config::save($config, ROOT_PATH.'settings/config.php')) {
                        // คืนค่า
                        $ret['alert'] = Language::get('Saved successfully');
                        $ret['location'] = 'reload';
                        // เคลียร์
                        $request->removeToken();
                    } else {
                        // ไม่สามารถบันทึก config ได้
                        $ret['alert'] = sprintf(Language::get('File %s cannot be created or is read-only.'), 'settings/config.php');
                    }
                } catch (\Kotchasan\InputItemException $e) {
                    $ret['alert'] = $e->getMessage();
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่าเป็น JSON
        echo json_encode($ret);
    }
}
