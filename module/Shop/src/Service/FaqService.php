<?php

namespace Shop\Service;

use Shop\Form\FaqForm;
use Shop\Hydrator\FaqHydrator;
use Shop\InputFilter\FaqInputFilter;
use Shop\Mapper\FaqMapper as FaqMapper;
use Shop\Model\FaqModel as FaqModel;
use Shop\ShopException;
use Common\Model\ModelInterface;
use Common\Service\AbstractMapperService;
use Laminas\EventManager\Event;
use Laminas\Form\Form;

/**
 * Class Faq
 *
 * @package Shop\Service
 * @method FaqMapper getMapper($mapperClass = null, array $options = [])
 */
class FaqService extends AbstractMapperService
{
    protected $form         = FaqForm::class;
    protected $hydrator     = FaqHydrator::class;
    protected $inputFilter  = FaqInputFilter::class;
    protected $mapper       = FaqMapper::class;
    protected $model        = FaqModel::class;

    /**
     * @var array
     */
    protected $tags = [
        'faq',
    ];

    /**
     * Attach events
     */
    public function attachEvents()
    {
        $this->getEventManager()->attach([
            'pre.form'
        ], [$this, 'preForm']);
    }

    /**
     * @param bool $topLevelOnly
     * @return \Laminas\Db\ResultSet\HydratingResultSet|\Laminas\Db\ResultSet\ResultSet|\Laminas\Paginator\Paginator
     */
    public function fetchAll($topLevelOnly=false)
    {
        $mapper = $this->getMapper();

        if ($topLevelOnly) {
            $faqs = $mapper->fetchTopLevelOnly();
        } else {
            $faqs = $mapper->fetchAll();
        }

        return $faqs;
    }

    /**
     * @param int $faqId
     * @param bool $recursive
     * @return array
     */
    public function getFaqChildrenIds($faqId, $recursive=false)
    {
        $mapper = $this->getMapper();

        $faqs = $mapper->getDecendentsByParentId($faqId, $recursive);

        $ids = [];

        /* @var $faq \Shop\Model\FaqModel */
        foreach ($faqs as $faq) {
            $ids[] = $faq->getFaqId();
        }

        return $ids;
    }

    /**
     * @param array $post
     * @param Form $form
     * @return int|Form
     */
    public function add(array $post, Form $form = null)
    {
        /* @var $mapper FaqMapper */
        $mapper = $this->getMapper();

        /* @var $model \Shop\Model\FaqModel */
        $model = $mapper->getModel();

        $form  = $this->prepareForm($model, $post, true, true);

        if (!$form->isValid()) {
            return $form;
        }

        $data = $mapper->extract($form->getData());

        $pk = $this->getMapper()->getPrimaryKey();
        unset($data[$pk]);

        $position = (int) $post['parent'];
        $insertType = (string) $post['faqInsertType'];

        $result = $mapper->insertRow($data, $position, $insertType);

        return $result;
    }

    /**
     * @param FaqModel|ModelInterface $model
     * @param array $post
     * @param Form $form
     * @return int|Form
     *@throws \Common\Service\ServiceException
     * @throws ShopException
     */
    public function edit(ModelInterface $model, array $post, Form $form = null)
    {

        if (!$model instanceof FaqModel) {
            throw new ShopException('$model must be an instance of Shop\Model\Faq, ' . get_class($model) . ' given.');
        }

        $form = $this->prepareForm($model, $post, true, true);

        if (!$form->isValid()) {
            return $form;
        }

        $faq = $this->getById($model->getFaqId());

        $data = $this->getMapper()
            ->extract($form->getData());

        if ($faq) {
            if ('noInsert' !== $post['faqInsertType']) {

                $position = (int) $post['parent'];
                $insertType = (string) $post['paqInsertType'];

                $result = $this->getMapper()->move($data, $position, $insertType);

            } else {
                $result = $this->save($model);
            }
            $this->removeCacheItem($model->getFaqId());
        } else {
            throw new ShopException('FAQ id does not exist');
        }

        return $result;
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        $id = (int) $id;
        $childIds = $this->getFaqChildrenIds($id);

        foreach ($childIds as $catId) {
            $this->removeCacheItem($catId);
        }

        return parent::delete($id);
    }

    /**
     * @param Event $e
     */
    public function preForm(Event $e)
    {
        $model = $e->getParam('model');

        if ($model instanceof FaqModel) {
            $this->setFormOptions([
                'faqId' => $model->getFaqId(),
            ]);
        }
    }
}
