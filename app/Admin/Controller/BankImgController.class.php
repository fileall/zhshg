<?php
namespace Admin\Controller;
class BankImgController extends AdminCoreController {
    public function _initialize()
    {
        parent::_initialize();
        $this->_mod = D('BankImg');
        $this->set_mod('BankImg');
    }

    public function _before_update($data)
    {
        if(empty($data['img'])) unset($data['img']);
          return $data;
    }

    public function _after_update($id)
    {
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'],'bank_img', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $result['info'][0]['savename'];
                $this->_mod->where(['id'=>$id])->setField(['img'=>$data['img']]);
            }
        }
    }

    public  function _before_insert($data)
    {
        if (!empty($_FILES['img']['name'])) {
            $result = $this->_upload($_FILES['img'],'bank_img', array('width'=>C('pin_article_cate_img.width'), 'height'=>C('pin_article_cate_img.height')));
            if ($result['error']) {
                $this->ajax_return(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['img'] = $result['info'][0]['savename'];
               return $data;
            }
        }
    }
}