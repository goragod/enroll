<?php
/**
 * @filesource modules/enroll/controllers/level.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Enroll\Level;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=enroll-level
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * Level
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_List of} {LNG_Education level}');
        // เลือกเมนู
        $this->menu = 'settings';
        // สมาชิก
        $login = Login::isMember();
        // สามารถจัดการการลงทะเบียนได้
        if (Login::checkPermission($login, 'can_manage_enroll')) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-settings">{LNG_Settings}</span></li>');
            $ul->appendChild('<li><span>{LNG_Enroll}</span></li>');
            $ul->appendChild('<li><span>{LNG_Education level}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-elearning">'.$this->title.'</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'settings', 'enroll'));
            // แสดงฟอร์ม
            $section->appendChild(createClass('Enroll\Level\View')->render($request));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
