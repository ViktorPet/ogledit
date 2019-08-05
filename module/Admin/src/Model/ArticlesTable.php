<?php

namespace Admin\Model;

use Application\Model\BaseTableModel;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Application\Model\Base\BaseGridSettings;
use Application\Model\Base\BaseGridTable;
use Admin\Model\Articles;

/**
 * Class AgenciesTable
 * @package Admin\Model
 */
class ArticlesTable extends BaseGridTable {

    public function getTable() {
        return $this->tableGateway->select();
    }

    function getData(BaseGridSettings $pGridSettings, $userId, $paging) {
        $thisClass = $this;
        return $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass, $paging, $userId) {
                    $select->columns(array(
                        '*'
                    ));
                    $select->join('languages', 'articles.language_id = languages.id', array('language'), 'left');
                    $select->join('categories', 'articles.category_id = categories.id', array('category' => 'name'), 'left');

                    foreach ($pGridSettings->toArray() as $key => $value) {
                        if (substr($key, 0, 15) === "filterdatafield") {
                            if ($value == 'id') {
                                $pGridSettings->setField($key, 'languages.id');
                            }
                            if ($value == 'language') {
                                $pGridSettings->setField($key, 'languages.language');
                            }
                            if ($value == 'category') {
                                $pGridSettings->setField($key, 'categories.name');
                            }
                        }
                    }

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

    function getCount(BaseGridSettings $pGridSettings = null, $userId = null) {
        $thisClass = $this;
        $res = $this->tableGateway->select(function (Select $select) use ($pGridSettings, $thisClass) {
            $select->columns(array(
                new Expression('COUNT(*) AS num_results')
            ));

            $select->join('languages', 'articles.language_id = languages.id', array('language'), 'left');
            $select->join('categories', 'articles.category_id = categories.id', array('category' => 'name'), 'left');

            if (!is_null($pGridSettings)) {
                foreach ($pGridSettings->toArray() as $key => $value) {
                    if (substr($key, 0, 15) === "filterdatafield") {
                        if ($value == 'id') {
                            $pGridSettings->setField($key, 'languages.id');
                        }
                        if ($value == 'language') {
                            $pGridSettings->setField($key, 'languages.language');
                        }
                        if ($value == 'category') {
                            $pGridSettings->setField($key, 'categories.name');
                        }
                    }
                }
                // Filter
                $thisClass->filterHelper($pGridSettings, $select);
            }
        });
        return $res->current()->getField('num_results');
    }

    /**
     * Creates new Article
     *
     * @param Articles $article
     * @return mixed
     */
    public function create(Articles $article) {
        $file = $article->getField('image');
        $this->tableGateway->insert(array(
            'title' => $article->getField('title'),
            'description' => $article->getField('description'),
            'announcement' => $article->getField('announcement'),
            'url' => $article->getField('url'),
            'image' => $file['tmp_name'],
            'position' => $article->getField('position'),
            'date_published' => $article->getField('date_published'),
            'date_created' => new Expression('NOW()'),
            'date_updated' => new Expression('NOW()'),
            'meta_title' => $article->getField('meta_title'),
            'meta_description' => $article->getField('meta_description'),
            'meta_keywords' => $article->getField('meta_keywords'),
            'language_id' => $article->getField('language_id'),
            'category_id' => $article->getField('category_id')
        ));
    }

    /**
     * Edit Article
     *
     * @param Articles $article
     * @return mixed
     */
    public function edit(Articles $article) {
        $file = $article->getField('image');
        if (strlen($file['tmp_name']) == 0) {
            $this->tableGateway->update(array(
                'title' => $article->getField('title'),
                'description' => $article->getField('description'),
                'announcement' => $article->getField('announcement'),
                'url' => $article->getField('url'),
                'position' => $article->getField('position'),
                'date_published' => $article->getField('date_published'),
                'date_updated' => new Expression('NOW()'),
                'meta_title' => $article->getField('meta_title'),
                'meta_description' => $article->getField('meta_description'),
                'meta_keywords' => $article->getField('meta_keywords'),
                'language_id' => $article->getField('language_id'),
                'category_id' => $article->getField('category_id')
                    ), array('id' => $article->getField('id')));
        } else {
            $this->tableGateway->update(array(
                'title' => $article->getField('title'),
                'description' => $article->getField('description'),
                'announcement' => $article->getField('announcement'),
                'url' => $article->getField('url'),
                'image' => $file['tmp_name'] ? $file['tmp_name'] : NULL,
                'position' => $article->getField('position'),
                'date_published' => $article->getField('date_published'),
                'date_updated' => new Expression('NOW()'),
                'meta_title' => $article->getField('meta_title'),
                'meta_description' => $article->getField('meta_description'),
                'meta_keywords' => $article->getField('meta_keywords'),
                'language_id' => $article->getField('language_id'),
                'category_id' => $article->getField('category_id')
                    ), array('id' => $article->getField('id')));
        }
    }

    /**
     * Get Article By Id
     *
     * @param $articleId
     * @return mixed
     */
    public function getById($articleId) {
        $res = $this->tableGateway->select(function (Select $select) use ($articleId) {
            $select->where(array(
                'id' => $articleId,
            ));
        });
        return $res->current();
    }

    /**
     * Deletes Article
     *
     * @param $articleId
     */
    public function delete($articleId) {
        $this->tableGateway->delete(
                array(
                    'id' => $articleId,
                )
        );
    }

    /**
     * Get all news
     *
     * @return mixed
     */
    public function getNews($i) {
        return $this->tableGateway->select(function (Select $select) use ($i) {
            
            $select->join('news_categories', 'articles.position = news_categories.id', array('categoryName' => 'name'), 'left');
            
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 2);
                    $where->equalTo('position', $i);

                    $select->where($where);
                    $select->order(array('date_published DESC'))
                            ->limit(1);
                });
    }

    /**
     * Get last News
     *
     * @param $count
     * @return mixed
     */
    public function getLastNews($count) {
        return $this->tableGateway->select(function (Select $select) use ($count) {
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 2);

                    $select->where($where);
                    $select->order('date_published DESC')
                            ->limit($count);
                });
    }

    /**
     * Get last Articles
     *
     * @param $count
     * @return mixed
     */
    public function getLastArticles($count) {
        return $this->tableGateway->select(function (Select $select) use ($count) {
            $where = new Where();
            $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));

            $select->where($where);
            $select->order('date_published DESC')
                ->limit($count);
        });
    }

    /**
     * Get last News for given category
     *
     * @param $count
     * @param $newsCategory
     * @return mixed
     */
    public function getLastNewsByCategory($count, $newsCategory) {
        return $this->tableGateway->select(function (Select $select) use ($count, $newsCategory) {
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 2);
                    $where->equalTo('position', $newsCategory);

                    $select->where($where);

                    $select->order('date_published DESC')
                            ->limit($count);
                });
    }

    /**
     * Get News
     *
     * @param $url
     * @return mixed
     */
    public function getNewsById($url) {
        $res = $this->tableGateway->select(function (Select $select) use ($url) {
            
            $select->join('news_categories', 'articles.position = news_categories.id', array('categoryName' => 'name'), 'left');
            
            $where = new Where();
            $where->equalTo('url', $url);
            $where->equalTo('category_id', 2);

            $select->where($where);
        });

        return $res->current();
    }

    /**
     * Get Blogs
     *
     * @return mixed
     */
    public function getBlogs($i) {
        return $this->tableGateway->select(function (Select $select) use ($i) {
            
             $select->join('blog_categories', 'articles.position = blog_categories.id', array('categoryName' => 'name'), 'left');
            
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 1);
                    $where->equalTo('position', $i);

                    $select->where($where);
                    $select->order(array('date_published DESC'))
                            ->limit(1);
                });
    }

    /**
     * Get last Blogs
     *
     * @param $count
     * @return mixed
     */
    public function getLastBlogs($count) {
        return $this->tableGateway->select(function (Select $select) use ($count) {
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 1);

                    $select->where($where);

                    $select->order('date_published DESC')
                            ->limit($count);
                });
    }

    /**
     * Gets last Blogs by category.
     * 
     * @param type $count
     * @param type $blogCategory
     * @return type
     */
    public function getLastBlogsByCategory($count, $blogCategory) {
        return $this->tableGateway->select(function (Select $select) use ($count, $blogCategory) {
                    $where = new Where();
                    $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
                    $where->equalTo('category_id', 1);
                    $where->equalTo('position', $blogCategory);

                    $select->where($where);

                    $select->order('date_published DESC')
                            ->limit($count);
                });
    }

    /**
     * Get News
     *
     * @param $articleId
     * @return mixed
     */
    public function getBlogsById($url) {
        $res = $this->tableGateway->select(function (Select $select) use ($url) {
            
            $select->join('blog_categories', 'articles.position = blog_categories.id', array('categoryName' => 'name'), 'left');
            
            $where = new Where();
            $where->equalTo('url', $url);
            $where->equalTo('category_id', 1);

            $select->where($where);
        });

        return $res->current();
    }

    /**
     * Get Blog for given category
     *
     * @param $id
     * @return mixed
     */
    public function getBlogsByCategoryId($id, $limit = 2000, $offset = 0) {
        $res = $this->tableGateway->select(function (Select $select) use ($id, $limit, $offset) {
            $where = new Where();
            $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $where->equalTo('position', $id);
            $where->equalTo('category_id', 1);

            $select->order('date_published DESC');
            $select->offset($offset);
            $select->limit($limit);

            $select->where($where);
        });

        return $res;
    }

    /**
     * Gets the count of articles by category.
     * 
     * @param type $categoryId
     * @return type
     */
    public function getArticleCount($categoryId = 1) {
        $res = $this->tableGateway->select(function (Select $select) use ($categoryId) {
            $select->columns(array(
                'num_articles' => new Expression('COUNT(id)')
            ));
            $select->where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $select->where->equalTo('category_id', $categoryId);
        });

        return $res->current()->getField('num_articles');
    }
    
    /**
     * Gets the number of blog items.
     * 
     * @return type
     */
    public function getBlogCount() {
        return $this->getArticleCount(1);
    }

    /**
     * Gets the number of news items.
     * 
     * @return type
     */
    public function getNewsCount() {
        return $this->getArticleCount(2);
    }

    /**
     * Get Blog items by category.
     *
     * @param $id
     * @return mixed
     */
    public function getArticleItems($limit = 2000, $offset = 0, $categoryId = 1) {
        $res = $this->tableGateway->select(function (Select $select) use ($limit, $offset, $categoryId) {
            $select->where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $select->where->equalTo('category_id', $categoryId);

            $select->order('date_published DESC');
            $select->offset($offset);
            $select->limit($limit);
        });

        return $res;
    }
    
    /**
     * Gets all Blog items.
     * 
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getBlogItems($limit = 2000, $offset = 0) {
        return $this->getArticleItems($limit, $offset, 1);
    }

    /**
     * Gets all News items.
     * 
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getNewsItems($limit = 2000, $offset = 0) {
        return $this->getArticleItems($limit, $offset, 2);
    }

    /**
     * Get news for given category
     *
     * @param $id
     * @return mixed
     */
    public function getNewsByCategoryId($id, $limit = 2000, $offset = 0) {
        $res = $this->tableGateway->select(function (Select $select) use ($id, $limit, $offset) {
            $where = new Where();
            $where->lessThanOrEqualTo('date_published', new Expression('NOW()'));
            $where->equalTo('position', $id);
            $where->equalTo('category_id', 2);

            $select->order('date_published DESC');
            $select->offset($offset);
            $select->limit($limit);

            $select->where($where);
        });

        return $res;
    }

    /**
     * Gets all blogs for insert of meta tags
     *
     * @param $condition
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getBlogsWithoutMetaTags($condition) {
        return $this->tableGateway->select(function (Select $select) use ($condition) {
                    $select->join('languages', 'articles.language_id = languages.id', array('language'), 'left');
                    $select->join('categories', 'articles.category_id = categories.id', array('category' => 'name'), 'left');

                    $select->where($condition);
                });
    }

    /**
     * Update meta title
     *
     * @param $blogId
     * @param $metaTitle
     * @return int
     */
    public function updateMetaTitle($blogId, $metaTitle) {
        $this->tableGateway->update(array(
            'meta_title' => $metaTitle,
                ), array(
            'id' => $blogId
        ));
    }

    /**
     * Update meta description
     *
     * @param $blogId
     * @param $metaDescription
     * @return int
     */
    public function updateMetaDescription($blogId, $metaDescription) {
        $this->tableGateway->update(array(
            'meta_description' => $metaDescription,
                ), array(
            'id' => $blogId
        ));
    }

    /**
     * Update meta keywords
     *
     * @param $blogId
     * @param $metaKeywords
     * @return int
     */
    public function updateMetaKeywords($blogId, $metaKeywords) {
        $this->tableGateway->update(array(
            'meta_keywords' => $metaKeywords,
                ), array(
            'id' => $blogId
        ));
    }

}
