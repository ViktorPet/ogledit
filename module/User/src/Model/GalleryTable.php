<?php
namespace User\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;

/**
 * Class GalleryTable
 * @package User\Model
 */
class GalleryTable extends BaseTableModel {

    /**
     * Gets offer images by offer Id.
     * 
     * @param $offerId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getImagesByOfferId($offerId) {
        return $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->where->equalTo('offer_id', $offerId);
            $select->order('image_order ASC');
        });
    }

    /**
     * Gets main image
     *
     * @return mixed
     */
    public function getMainImage($offerId) {
        $rowset = $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'image'
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
                '*'
            ));
            $select->where(array(
                'id' => $imageId,
            ));
        });
        return $rowset->current();
    }

    /**
     * Gets last image order
     *
     * @return mixed
     */
    public function getLastImageOrder($offerId)
    {
        return $this->tableGateway->select(function (Select $select) use ($offerId) {
            $select->columns(array(
                'image_order'
            ));

            $select->where(array('offer_id' => $offerId));
            $select->order('image_order DESC');
        })->current();
    }

    /**
     * Creates new image
     *
     * @param $imageName
     * @param $offerId
     * @param $lastOrder
     * @param $isFront
     * @return int
     */
    public function create(Gallery $gallery, $offerId, $lastOrder, $isFront = 2) {
        $file = $gallery->getImage();
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
            $currentOrder = $result->getImageOrder();
            $offerId = $result->getOfferId();

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
        return $rowset->current()->getNumResults();
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

}