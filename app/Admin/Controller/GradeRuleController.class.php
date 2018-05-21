<?php
namespace Admin\Controller;

/**会员规则类
 * Class GradeRuleController
 * @package Admin\Controller
 */
class GradeRuleController extends AdminCoreController {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('GradeRule');
        $this->set_mod('GradeRule');
    }

    public function _before_index() {
        //默认排序
        $this->sort = 'id';
        $this->order = 'ASC';
    }


}