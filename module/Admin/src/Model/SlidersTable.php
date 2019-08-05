<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Gallery;
use Zend\Db\Sql\Where;

/**
 * Description of GalleryTable
 *
 */
class SlidersTable extends BaseGridTable {

    function getData(BaseGridSettings $pGridSettings, $userId, $paging)
    {
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));

            // Filter
            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
            $thisClass->sortHelper($pGridSettings, $select);

            // Pagination
            if ($paging == true) {
                $thisClass->pagingnHelper($pGridSettings, $select);
            }
        });
    }

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null)
    {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    public function create(Sliders $slidersObj) {
        $this->tableGateway->insert(array(
            'name' => $slidersObj->getField('name'),

            'link' => $slidersObj->getField('link'),
            'desktop_img' => $slidersObj->getField('desktop_img'),
            'mobile_img' => $slidersObj->getField('mobile_img'),

            'link_en' => $slidersObj->getField('link_en'),
            'desktop_img_en' => $slidersObj->getField('desktop_img_en'),
            'mobile_img_en' => $slidersObj->getField('mobile_img_en')
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    public function update(Sliders $slidersObj, $deleteDesktopImg = false, $deleteMobileImg = false, $deleteDesktopEnImg = false, $deleteMobileEnImg = false) {
        $desktopUpdate = $slidersObj->getField('desktop_img');
        $mobileUpdate = $slidersObj->getField('mobile_img');

        $desktopEnUpdate = $slidersObj->getField('desktop_img_en');
        $mobileEnUpdate = $slidersObj->getField('mobile_img_en');

        $updateArray = array(
            'name' => $slidersObj->getField('name'),
            'link' => $slidersObj->getField('link'),
            'link_en' => $slidersObj->getField('link_en')
        );
        if($desktopUpdate) {
            $updateArray['desktop_img'] = $slidersObj->getField('desktop_img');
        }
        if($mobileUpdate) {
            $updateArray['mobile_img'] = $slidersObj->getField('mobile_img');
        }

        if($desktopEnUpdate) {
            $updateArray['desktop_img_en'] = $slidersObj->getField('desktop_img_en');
        }
        if($mobileEnUpdate) {
            $updateArray['mobile_img_en'] = $slidersObj->getField('mobile_img_en');
        }

        if($deleteDesktopImg) {
            $updateArray['desktop_img'] = '';
        }

        if($deleteMobileImg) {
            $updateArray['mobile_img'] = '';
        }

        if($deleteDesktopEnImg) {
            $updateArray['desktop_img_en'] = '';
        }

        if($deleteMobileEnImg) {
            $updateArray['mobile_img_en'] = '';
        }

        $this->tableGateway->update($updateArray, array(
            'id' => $slidersObj->getField('id')
        ));
    }

    public function delete($imageId)
    {
        $this->tableGateway->delete(
            array(
                'id' => $imageId
            )
        );
    }

    function getById($slideId)
    {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($thisClass, $slideId) {
            $select->columns(array(
                '*'
            ));
            $select->where(array(
                'id' => $slideId
            ));
        });
        return $res->current();
    }

    function getByType($type, $lang = 'bg')
    {
        $where = new Where();
        if($type == Sliders::MOBILE) {
            if($lang == 'bg') {
                $where->notEqualTo('mobile_img', '');
            } else {
                $where->notEqualTo('mobile_img_en', '');
            }
        } else if($type == Sliders::DESKTOP) {
            if($lang == 'bg') {
                $where->notEqualTo('desktop_img', '');
            } else {
                $where->notEqualTo('desktop_img_en', '');
            }
        }
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($thisClass, $where) {
            $select->columns(array(
                '*'
            ));

            $select->where($where);
        });
        return $res;
    }

    function getAll()
    {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($thisClass) {
            $select->columns(array(
                '*'
            ));
        });
        return $res;
    }
}