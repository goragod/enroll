<?php
/**
 * @filesource modules/index/models/autocomplete.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Autocomplete;

use Kotchasan\Http\Request;

/**
 * คลาสสำหรับการโหลด ตำบล อำเภอ จังหวัด.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * ประมวลผลค่าที่ส่งมา และส่งค่ากลับเป็น JSON
     *
     * @param array $where
     *
     * @return JSON
     */
    public function execute($where)
    {
        // Query ข้อมูล
        $result = static::createQuery()
            ->select('P.province', 'P.id provinceID', 'A.amphur', 'A.id amphurID', 'D.district', 'D.id districtID')
            ->from('province P')
            ->join('amphur A', 'INNER', array('A.province_id', 'P.id'))
            ->join('district D', 'INNER', array('D.amphur_id', 'A.id'))
            ->where($where)
            ->limit(50)
            ->cacheOn()
            ->toArray()
            ->execute();
        // คืนค่า JSON
        if (!empty($result)) {
            echo json_encode($result);
        }
    }
    /**
     * คืนค่า ตำบล อำเภอ จังหวัด จาก อำเภอ
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function amphur(Request $request)
    {
        // session, referer
        if ($request->initSession() && $request->isReferer()) {
            try {
                // ข้อความค้นหาที่ส่งมา
                $value = $request->post('register_amphur')->topic();
                $country = $request->get('country')->filter('A-Z');
                if ($value != '') {
                    $this->execute(array(
                        array('A.country', $country),
                        array('A.amphur', 'LIKE', $value.'%'),
                    ));
                }
            } catch (\Kotchasan\InputItemException $e) {
            }
        }
    }

    /**
     * คืนค่า ตำบล อำเภอ จังหวัด จาก ตำบล.
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function district(Request $request)
    {
        // session, referer
        if ($request->initSession() && $request->isReferer()) {
            try {
                // ข้อความค้นหาที่ส่งมา
                $value = $request->post('register_district')->topic();
                $country = $request->get('country')->filter('A-Z');
                if ($value != '') {
                    $this->execute(array(
                        array('D.country', $country),
                        array('D.district', 'LIKE', $value.'%'),
                    ));
                }
            } catch (\Kotchasan\InputItemException $e) {
            }
        }
    }

    /**
     * คืนค่า ตำบล อำเภอ จังหวัด จาก จังหวัด.
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function province(Request $request)
    {
        // session, referer
        if ($request->initSession() && $request->isReferer()) {
            try {
                // ข้อความค้นหาที่ส่งมา
                $value = $request->post('register_province')->topic();
                $country = $request->get('country')->filter('A-Z');
                if ($value != '') {
                    $this->execute(array(
                        array('P.country', $country),
                        array('P.province', 'LIKE', $value.'%'),
                    ));
                }
            } catch (\Kotchasan\InputItemException $e) {
            }
        }
    }
}
