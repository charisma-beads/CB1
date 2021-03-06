<?php

namespace Common\Service;

use Common\Cache\CacheStorageAwareInterface;
use Common\Cache\CacheTrait;
use Common\Mapper\MapperInterface;
use Common\Mapper\MapperManager;
use Common\Model\ModelInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Form\Form;
use Laminas\Paginator\Paginator;


class AbstractMapperService extends AbstractService implements MapperServiceInterface, CacheStorageAwareInterface
{
    use CacheTrait;

    const EVENT_PRE_ADD     = 'pre.add';
    const EVENT_POST_ADD    = 'post.add';
    const EVENT_PRE_EDIT    = 'pre.edit';
    const EVENT_POST_EDIT   = 'post.edit';
    const EVENT_PRE_SAVE    = 'pre.save';
    const EVENT_POST_SAVE   = 'post.save';
    const EVENT_PRE_DELETE  = 'pre.delete';
    const EVENT_POST_DELETE = 'post.delete';

    /**
     * @var string
     */
    protected $mapper;

    /**
     * @var array
     */
    protected $mappers = [];

    /**
     * return one or more records from database by id
     *
     * @param $id
     * @param null $col
     * @return array|mixed|ModelInterface
     */
    public function getById($id, $col = null)
    {
        $id = (int) $id;
        $model = $this->getCacheItem($id);

        if (!$model) {
            $model = $this->getMapper()->getById($id, $col);

            if ($this->useCache) {
                $this->setCacheItem($id, $model);
            }
        }

        return $model;
    }

    /**
     * fetch all records form database
     *
     * @param null|string $sort
     * @return HydratingResultSet|ResultSet|Paginator
     */
    public function fetchAll($sort = null)
    {
        return $this->getMapper()
            ->fetchAll($sort);
    }

    /**
     * basic search on database
     *
     * @param array $post
     * @return ResultSet|Paginator|HydratingResultSet
     */
    public function search(array $post)
    {
        $sort = (isset($post['sort'])) ? $post['sort'] : '';
        unset($post['sort'], $post['count'], $post['offset'], $post['page']);

        $searches = [];

        foreach ($post as $key => $value) {
            $searches[] = [
                'searchString' => $value,
                'columns' => explode('-', $key),
            ];
        }

        $models = $this->getMapper()->search($searches, $sort);

        return $models;
    }

    /**
     * prepare and return form
     *
     * @param array $post
     * @param Form $form
     * @return int|Form
     * @throws ServiceException
     */
    public function add(array $post, Form $form = null)
    {
        $model  = $this->getModel();
        $form   = ($form instanceof Form) ?
            $form->setData($post) :
            $this->prepareForm($model, $post, true, true);
        $argv   = compact('post', 'form');
        $argv   = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_PRE_ADD, $this, $argv);

        if (!$form->isValid()) {
            return $form;
        }

        $saved  = $this->save($form->getData());
        $argv   = compact('post', 'form', 'saved');
        $argv   = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_POST_ADD, $this, $argv);

        return $saved;
    }

    /**
     * prepare data to be updated and saved into database.
     *
     * @param ModelInterface $model
     * @param array $post
     * @param Form $form
     * @return Form|int results from self::save()
     * @throws ServiceException
     */
    public function edit(ModelInterface $model, array $post, Form $form = null)
    {
        $form = ($form instanceof Form) ?
            $form->setData($post) :
            $this->prepareForm($model, $post, true, true);
        $argv = compact('model', 'post', 'form');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_PRE_EDIT, $this, $argv);

        if (!$form->isValid()) {
            return $form;
        }

        $saved  = $this->save($form->getData());
        $argv   = compact('model', 'post', 'form', 'saved');
        $argv   = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_POST_EDIT, $this, $argv);

        $eventSaved = (isset($argv['result'])) ? $argv['result'] : false;

        return (!$saved && !$eventSaved) ? false : true;
    }

    /**
     * updates a row if id is supplied else insert a new row
     *
     * @param array|ModelInterface $data
     * @throws ServiceException
     * @return int $results number of rows affected or insertId
     */
    public function save($data)
    {
        $argv = compact('data');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_PRE_SAVE, $this, $argv);

        $data = $argv['data'];

        if ($data instanceof ModelInterface) {
            $data = $this->getHydrator()->extract($data);
        }

        $pk = $this->getMapper()->getPrimaryKey();
        $id = $data[$pk];
        unset($data[$pk]);

        if (0 === $id || null === $id || '' === $id) {
            $result = $this->getMapper()->insert($data);
            $this->clearCacheTags();
        } else {
            if ($this->getById($id)) {
                $result = $this->getMapper()->update($data, [$pk => $id]);
            } else {
                throw new ServiceException('ID ' . $id . ' does not exist');
            }

            $this->removeCacheItem($id);
        }

        $argv = compact('data', 'result');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_POST_SAVE, $this, $argv);

        return $result;
    }

    /**
     * delete row from database
     *
     * @param int $id
     * @return int $result number of rows affected
     */
    public function delete($id)
    {
        $model = $this->getById($id);

        $argv = compact('id', 'model');
        $argv = $this->prepareEventArguments($argv);

        $this->getEventManager()->trigger(self::EVENT_PRE_DELETE, $this, $argv);

        $result = $this->getMapper()->delete([
            $this->getMapper()->getPrimaryKey() => $id
        ]);

        if ($result) {
            $this->removeCacheItem($id);
            $argv = compact('id', 'model', 'result');
            $argv = $this->prepareEventArguments($argv);
            $this->getEventManager()->trigger(self::EVENT_POST_DELETE, $this, $argv);
        }

        return $result;
    }

    /**
     * gets the mapper class for this service
     *
     * @param null|string $mapperClass
     * @param array $options
     * @return MapperInterface
     */
    public function getMapper($mapperClass = null, array $options = [])
    {
        $mapperClass = $mapperClass ?? $this->mapper ?? $this->serviceAlias;

        if (!array_key_exists($mapperClass, $this->mappers)) {
            $this->setMapper($mapperClass, $options);
        }

        return $this->mappers[$mapperClass];
    }

    /**
     * Sets mapper in mapper array for reuse.
     *
     * @param string $mapperClass
     * @param array $options
     * @return $this
     */
    public function setMapper($mapperClass, array $options = [])
    {
        $sl             = $this->getServiceLocator();
        $mapperManager  = $sl->get(MapperManager::class);

        $defaultOptions = [
            'model' => $this->model ?? $this->serviceAlias,
            'hydrator' => $this->hydrator ?? $this->serviceAlias,
        ];

        $options    = array_merge($defaultOptions, $options);
        $mapper     = $mapperManager->get($mapperClass, $options);

        $this->mappers[$mapperClass] = $mapper;

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function usePaginator($options = [])
    {
        $this->getMapper()
            ->setUsePaginator(true)
            ->setPaginatorOptions($options);

        return $this;
    }
}
