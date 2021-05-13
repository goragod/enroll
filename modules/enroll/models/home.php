<?php
/**
 * @filesource modules/enroll/models/home.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Enroll\Home;

use Kotchasan\Database\Sql;

/**
 * โมเดลสำหรับอ่านข้อมูลแสดงในหน้า  Home.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านจำนวนการสมัครตามระดับต่างๆ
     *
     * @return array
     */
    public static function datas()
    {
        $query = static::createQuery()
            ->select('level', Sql::COUNT('id', 'count'))
            ->from('enroll')
            ->groupBy('level')
            ->cacheOn();
        $result = array();
        foreach ($query->execute() as $item) {
            $result[$item->level] = $item->count;
        }
        return $result;
    }
}
