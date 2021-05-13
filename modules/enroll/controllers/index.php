<?php
/**
 * @filesource modules/enroll/controllers/index.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Enroll\Index;

use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=enroll-index
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * ตารางรายการ ลงทะเบียน
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        $params = array(
            'level' => $request->request('level')->toInt(),
            'levels' => \Enroll\Level\Model::toSelect(),
        );
        $this->level = \Enroll\Level\Model::toSelect();
        if (!isset($params['levels'][$params['level']])) {
            $params['level'] = \Kotchasan\ArrayTool::getFirstKey($params['levels']);
        }
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_List of} {LNG_Enroll}');
        // ข้อความ title bar
        $title = $params['levels'][$params['level']];
        $this->title .= ' '.$title;
        // เลือกเมนู
        $this->menu = 'enroll';
        // แสดงผล
        $section = Html::create('section', array(
            'class' => 'content_bg',
        ));
        // breadcrumbs
        $breadcrumbs = $section->add('div', array(
            'class' => 'breadcrumbs',
        ));
        $ul = $breadcrumbs->add('ul');
        $ul->appendChild('<li><span class="icon-register">{LNG_Home}</span></li>');
        $ul->appendChild('<li><span>{LNG_Enroll}</span></li>');
        $ul->appendChild('<li><span>'.$title.'</span></li>');
        $ul->appendChild('<li><span>{LNG_List of}</span></li>');
        $section->add('header', array(
            'innerHTML' => '<h2 class="icon-list">'.$this->title.'</h2>',
        ));
        // แสดงตาราง
        $section->appendChild(createClass('Enroll\Index\View')->render($request, $params));
        // คืนค่า HTML
        return $section->render();
    }
}
