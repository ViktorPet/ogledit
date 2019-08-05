<?php

namespace Application\Model;

/**
 * Description of Page
 *
 */
class Page {

    public $id;
    public $title;
    public $description;
    public $slug;
    public $dateCreated;
    public $dateUpdated;
    public $metaTitle;
    public $metaDescription;
    public $metaKeywords;
    public $languageId;
    public $url;
    public $rawData;

    public function exchangeArray($data) {
        $this->rawData = $data;
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->dateCreated = (!empty($data['date_created'])) ? $data['date_created'] : null;
        $this->dateUpdated = (!empty($data['date_updated'])) ? $data['date_updated'] : null;
        $this->metaTitle = (!empty($data['meta_title'])) ? $data['meta_title'] : null;
        $this->metaDescription = (!empty($data['meta_description'])) ? $data['meta_description'] : null;
        $this->metaKeywords = (!empty($data['meta_keywords'])) ? $data['meta_keywords'] : null;
        $this->languageId = (!empty($data['language_id'])) ? $data['language_id'] : null;
        $this->url = (!empty($data['url'])) ? $data['url'] : null;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getDateCreated() {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated) {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return mixed
     */
    public function getDateUpdated() {
        return $this->dateUpdated;
    }

    /**
     * @param mixed $dateUpdated
     */
    public function setDateUpdated($dateUpdated) {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return mixed
     */
    public function getMetaTitle() {
        return $this->metaTitle;
    }

    /**
     * @param mixed $metaTitle
     */
    public function setMetaTitle($metaTitle) {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return mixed
     */
    public function getMetaDescription() {
        return $this->metaDescription;
    }

    /**
     * @param mixed $metaDescription
     */
    public function setMetaDescription($metaDescription) {
        $this->metaDescription = $metaDescription;
    }

    /**
     * @return mixed
     */
    public function getMetaKeywords() {
        return $this->metaKeywords;
    }

    /**
     * @param mixed $metaKeywords
     */
    public function setMetaKeywords($metaKeywords) {
        $this->metaKeywords = $metaKeywords;
    }

    /**
     * @return mixed
     */
    public function getLanguageId() {
        return $this->languageId;
    }

    /**
     * @param mixed $languageId
     */
    public function setLanguageId($languageId) {
        $this->languageId = $languageId;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getRawData() {
        return $this->rawData;
    }

    /**
     * @param mixed $rawData
     */
    public function setRawData($rawData) {
        $this->rawData = $rawData;
    }
}
