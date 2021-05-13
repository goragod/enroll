<?php
/**
 * @filesource modules/enroll/controllers/export.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Enroll\Export;

use Kotchasan\Date;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Template;

/**
 * export.php?module=enroll-export&typ=csv|print
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * export
     *
     * @param Request $request
     */
    public function export(Request $request)
    {
        $typ = $request->get('typ')->toString();
        if ($typ === 'csv') {
            // CSV
            return \Enroll\Csv\View::execute($request);
        } elseif ($typ === 'print') {
            // ตรวจสอบรายการที่เลือก
            $enroll = \Enroll\Export\Model::get($request->get('id')->password());
            if ($enroll) {
                // academic_results
                $onet_list = Language::get('ACADEMIC_RESULTS');
                $academic_results = '<tr>';
                if (!empty($onet_list)) {
                    $datas = json_decode($enroll->academic_results, true);
                    $i = 0;
                    foreach ($onet_list as $key => $label) {
                        if ($i > 0 && $i % 4 == 0) {
                            $academic_results .= '</tr><tr>';
                        }
                        $i++;
                        $academic_results .= '<td>'.$label.' : '.(empty($datas[$key]) ? '' : $datas[$key]).'</td>';
                    }
                }
                $academic_results .= '</tr>';
                // parent
                $parent_list = Language::get('PARENT_LIST', array());
                $parent = '<tr>';
                if (!empty($parent_list)) {
                    $datas = json_decode($enroll->parent, true);
                    $i = 0;
                    foreach ($parent_list as $key => $label) {
                        if ($i > 0 && $i % 2 == 0) {
                            $parent .= '</tr><tr>';
                        }
                        $i++;
                        $parent .= '<td>'.$label.' : '.(empty($datas[$key]['name']) ? '' : $datas[$key]['name']).'</td>';
                        $parent .= '<td>โทรศัพท์ : '.(empty($datas[$key]['phone']) ? '' : $datas[$key]['phone']).'</td>';
                    }
                }
                $parent .= '</tr>';
                // พิมพ์
                $content = array(
                    '/{LANGUAGE}/' => Language::name(),
                    '/{CONTENT}/' => file_get_contents(ROOT_PATH.'modules/enroll/template/register.html'),
                    '/{WEBURL}/' => WEB_URL,
                    '/{TITLE}/' => self::$cfg->web_title,
                    '/%PICTURE%/' => WEB_URL.DATA_FOLDER.'enroll/'.$enroll->id.'.jpg',
                    '/%DATE%/' => Date::format($enroll->create_date),
                    '/%LEVEL%/' => $enroll->level,
                    '/%SCHOOL_NAME%/' => self::$cfg->school_name,
                    '/%PLAN%/' => $enroll->plan,
                    '/%TITLE%/' => Language::find('TITLES', '', $enroll->title),
                    '/%NAME%/' => $enroll->name,
                    '/%ID_CARD%/' => $enroll->id_card,
                    '/%BIRTHDAY%/' => Date::format($enroll->birthday, 'd M Y'),
                    '/%PHONE%/' => $enroll->phone,
                    '/%EMAIL%/' => $enroll->email,
                    '/%NATIONALITY%/' => $enroll->nationality,
                    '/%RELIGION%/' => $enroll->religion,
                    '/%ADDRESS%/' => $enroll->address,
                    '/%DISTRICT%/' => $enroll->district,
                    '/%AMPHUR%/' => $enroll->amphur,
                    '/%PROVINCE%/' => $enroll->province,
                    '/%ZIPCODE%/' => $enroll->zipcode,
                    '/%ORIGINAL_SCHOOL%/' => $enroll->original_school,
                    '/%PARENT%/' => $parent,
                    '/%ACADEMIC_RESULTS%/' => $academic_results,
                );
                return self::toPrint($content);
            }
        }
        return false;
    }

    /*
     * ส่งออกข้อมูลเป็น HTML หรือ หน้าสำหรับพิมพ์
     *
     * @param array $content
     */
    /**
     * @param $content
     * @return mixed
     */
    public static function toPrint($content)
    {
        $template = Template::createFromFile(ROOT_PATH.'modules/enroll/template/print.html');
        $template->add(Language::trans($content));
        return $template->render();
    }
}
