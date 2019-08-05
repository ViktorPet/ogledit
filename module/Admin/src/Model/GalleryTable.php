<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Gallery;

/**
 * Description of GalleryTable
 *
 */
class GalleryTable extends BaseGridTable
{

    protected $offerId = null;

    function setofferId($offerId)
    {
        $this->offerId = $offerId;
    }

    /**
     * Create main image
     *
     * @param $image
     * @param $offerId
     */
    public function addMainImageForOffer($image, $offerId)
    {
        $this->tableGateway->delete(array(
            'offer_id' => $offerId,
            'is_front' => 1
        ));
        $this->tableGateway->insert(array(
            'image' => $image,
            'offer_id' => $offerId,
            'is_front' => '1',
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()')
        ));
    }

    /**
     * Update main image
     *
     * @param $image
     * @param $offerId
     */
    public function updateMainImageForOffer($image, $offerId)
    {
        $this->tableGateway->update(array(
            'is_front' => 2
        ), array(
            'offer_id' => $offerId
        ));
        $this->tableGateway->update(array(
            'is_front' => 1
        ), array(
            'offer_id' => $offerId,
            'image' => $image
        ));
    }

    /**
     * Gets main image
     *
     * @param $offerId
     * @return array|\ArrayObject|null
     */
    public function getMainImage($offerId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'is_front'
            ));
            $select->where(array(
                'offer_id' => $offerId,
                'is_front' => 1
            ));
        });
        return $rowset->current();
    }

    /**
     * Gets image by id
     *
     * @param $imageId
     * @return array|\ArrayObject|null
     */
    public function getImage($imageId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($imageId) {
            $select->columns(array(
                'image'
            ));
            $select->where(array(
                'id' => $imageId,
            ));
        });
        return $rowset->current();
    }


    /**
     * Gets offer id by image
     *
     * @param $imageId
     * @return array|\ArrayObject|null
     */
    public function getOfferIdByImage($imageId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($imageId) {
            $select->columns(array(
                'offer_id'
            ));
            $select->where(array('id' => $imageId));
        });
        return $rowset->current();
    }

    /**
     * @param BaseGridSettings $pGridSettings
     * @param $userId
     * @param $paging
     * @return \Zend\Db\ResultSet\ResultSet
     */
    function getData(BaseGridSettings $pGridSettings, $userId, $paging)
    {
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
            $select->columns(array(
                '*'
            ));

            $select->where->equalTo('offer_id', $this->offerId);

            // Filter
            $thisClass->filterHelper($pGridSettings, $select);

            // Sort
            $thisClass->sortHelper($pGridSettings, $select, 'image_order', 'ACS');

            // Pagination
            if ($paging == true) {
                $thisClass->pagingnHelper($pGridSettings, $select);
            }
        });
    }

    /**
     * @param BaseGridSettings|null $pGridSettings
     * @param null $userId
     * @return mixed
     */
    function getCount(BaseGridSettings $pGridSettings = null, $userId = null)
    {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            $select->where->equalTo('offer_id', $this->offerId);

            if (!is_null($pGridSettings)) {
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Creates new image
     *
     * @param $imageName
     * @param $offerId
     * @param $lastOrder
     * @return int
     */
    public function create(Gallery $gallery, $offerId, $lastOrder, $isFront = 2) {
        $file = $gallery->getField('image');
        $this->tableGateway->insert(array(
            'is_front' => $isFront,
            'offer_id' => $offerId,
            'image' => $file['tmp_name'],
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'image_order' => new Expression("$lastOrder + 1")
        ));
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * Deletes the image.
     *
     * @param $imageId
     */
    public function delete($imageId)
    {
        $result = $this->tableGateway->select(function (Select $select) use ($imageId) {
            $select->columns(array(
                'image_order',
                'offer_id'
            ));
            $select->where(array(
                'id' => $imageId,
            ));
        })->current();

        if (!is_null($result)) {
            $currentOrder = $result->getField('image_order');
            $offerId = $result->getField('offer_id');

            $where = new \Zend\Db\Sql\Where();
            $where
                ->equalTo('offer_id', $offerId)
                ->greaterThan('image_order', $currentOrder);


            $this->tableGateway->update(array('image_order' => new Expression("image_order - 1")), $where);

            $this->tableGateway->delete(
                array(
                    'id' => $imageId
                )
            );
        }
    }

    /**
     * Update the image.
     *
     * @param $imageId
     */
    public function updateImage($imageId, $image)
    {
        $where = new \Zend\Db\Sql\Where();
        $where->equalTo('id', $imageId);

        $this->tableGateway->update(array('image' => $image), $where);
    }

    /**
     * Deletes images by offer id
     *
     * @param type $offerId
     */
    public function deleteImages($offerId)
    {
        $this->tableGateway->delete(
            array(
                'offer_id' => $offerId
            )
        );
    }


    /**
     * Update image order
     *
     * @param $imageId
     * @param $newImageOrder
     * @param $oldImageOrder
     * @param $offerId
     */
    public function updateImageOrder($imageId, $newImageOrder, $oldImageOrder, $offerId)
    {
        $this->tableGateway->update(array(
            'image_order' => $oldImageOrder,
        ), array(
            'image_order' => $newImageOrder,
            'offer_id' => $offerId
        ));

        $this->tableGateway->update(array(
            'image_order' => $newImageOrder,
        ), array(
            'id' => $imageId
        ));

    }

    /**
     * Gets last image order
     *
     * @return mixed
     */
    public function getLastImageOrder($offerId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'image_order'
            ));

            $select->where(array('offer_id' => $offerId));
            $select->order('image_order DESC');
        });
        return $rowset->current();
    }

    /**
     *  Gets the number images for given offer
     *
     * @param $offerId
     * @return mixed
     */
    public function getImageCount($offerId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'num_results' => new Expression('COUNT(*)')
            ));

            $select->where->EqualTo('offer_id', $offerId);

        });
        return $rowset->current()->getField('num_results');
    }

    /**
     * Gets unique offer_id
     *
     * @param $imageId
     * @return array|\ArrayObject|null
     */
    public function getOfferIds()
    {
        $rowset = $this->tableGateway->select(function (Select $select){
            $select->columns(array(new Expression('DISTINCT(offer_id) as offer_id')));

        });
        return $rowset;
    }

    /**
     * Gets images by offer id
     *
     * @param $offerId
     * @return array|\ArrayObject|null
     */
    public function getImages($offerId)
    {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                '*'
            ));
            $select->where(array(
                'offer_id' => $offerId,
            ));
        });
        return $rowset;
    }

    /**
     * Insert image order to old offers in db
     *
     * @param $imageId
     * @param $offerId
     * @param $order
     */
    public function insertImageOrder($imageId, $offerId, $order)
    {
        $this->tableGateway->update(array(
            'image_order' => $order
        ), array(
            'offer_id' => $offerId,
            'id' => $imageId
        ));
    }

}