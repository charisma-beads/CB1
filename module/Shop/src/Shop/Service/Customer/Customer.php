<?php
namespace Shop\Service\Customer;

use \Shop\Model\Customer\Customer as CustomerModel;
use UthandoCommon\Service\AbstractRelationalMapperService;
use UthandoCommon\Service\ServiceException;
use UthandoUser\Model\User;
use Zend\EventManager\Event;
use Zend\Math\BigInteger\BigInteger;
use Zend\Math\Rand;

/**
 * Class Customer
 * @package Shop\Service\Customer
 * @method CustomerModel populate()
 */
class Customer extends AbstractRelationalMapperService
{
    /**
     * @var string
     */
    protected $serviceAlias = 'ShopCustomer';

    /**
     * @var array
     */
    protected $referenceMap = [
        'user'              => [
            'refCol'    => 'userId',
            'service'   => 'UthandoUser\Service\User',
        ],
        'prefix'            => [
            'refCol'    => 'prefixId',
            'service'   => 'Shop\Service\Customer\Prefix',
        ],
        'deliveryAddress'   => [
            'refCol'    => 'deliveryAddressId',
            'service'   => 'Shop\Service\Customer\Address',
            'getMethod' => 'getFullAddressById',
        ],
        'billingAddress'    => [
            'refCol'    => 'billingAddressId',
            'service'   => 'Shop\Service\Customer\Address',
            'getMethod' => 'getFullAddressById',
        ],
    ];

    /**
     * @var User
     */
    protected $user;

    /**
     * Attach events
     */
    public function attachEvents()
    {
        $this->getEventManager()->attach(
            ['post.edit'], [$this, 'postEdit']
        );
    }

    /**
     * @param $userId
     * @return array|\ArrayObject|null|\Shop\Model\Customer\Customer
     */
    public function getCustomerByUserId($userId)
    {
        $userId = (int) $userId;

        /* @var $mapper \Shop\Mapper\Customer\Customer */
        $mapper = $this->getMapper();
        $customer = $mapper->getCustomerByUserId($userId);
        
        return $customer;
    }

    /**
     * @param $id
     * @return array|mixed|\Shop\Model\Customer\Customer
     */
    public function getCustomerDetailsByCustomerId($id)
    {
        $customer = $this->getById($id);
        
        $this->populate($customer, true);
        
        return $customer;
    }

    /**
     * @param null $userId
     * @return array|\ArrayObject|null|\Shop\Model\Customer\Customer
     */
    public function getCustomerDetailsFromUserId($userId = null)
    {
        $userId = ($userId) ? $userId : $this->getUser()->getUserId();

        /* @var $mapper \Shop\Mapper\Customer\Customer */
        $mapper = $this->getMapper();

        $customer = $mapper->getCustomerByUserId($userId);
        
        $this->populate($customer, true);
        
        return $customer;
    }

    /**
     * @param $month
     * @param null $year
     * @return \Zend\Db\ResultSet\HydratingResultSet
     */
    public function getCustomersByMonth($month, $year = null)
    {
        $month = (int) $month;
        $year = ($year) ?: date('Y');
        $date = new \DateTime(join('-',[
            $year, $month
        ]));
        $startDate = $date->format('Y-m-01');
        $endDate = $date->format('Y-m-t');

        /* @var $mapper \Shop\Mapper\Customer\Customer */
        $mapper = $this->getMapper();

        $customers = $mapper->getCustomersByDate($startDate, $endDate);

        return $customers;
    }

    /**
     * Update or set customer default address for billing or delivery.
     *
     * @param array $post
     * @return int
     * @throws ServiceException
     */
    public function setCustomerAddress(array $post)
    {
        $id = (isset($post['customerId'])) ? (int) $post['customerId'] : null;

        $form = $this->getForm($this->getById($id), $post, true, true);
        $form->setValidationGroup(array_keys($post));

        if (!$form->isValid()) {
            throw new ServiceException('values not valid');
        }

        return $this->save($form->getData());
    }

    /**
     * Generate a customer number with check digit at end
     * to test use simple test:
     *
     * $pass = ($checkDigit === substr($num, 0, -1) % 11 % 10) ? true : false
     *
     * @param CustomerModel $customer
     * @return string
     */
    public function generateCustomerNumber(CustomerModel $customer)
    {
        $part1 = $customer->getDateCreated()->format('Y');
        $part2 = sprintf('%06d', $customer->getCustomerId());;
        $part3 = Rand::getString(5, '0123456789');

        $num = join('', [
            $part1, $part2, $part3
        ]);

        $bigInt = BigInteger::factory('bcmath');
        $checkDigit = $bigInt->mod($num, 11);
        $checkDigit = $bigInt->mod($checkDigit, 10);

        return $num . $checkDigit;
    }

    /**
     * @param Event $e
     */
    public function postEdit(Event $e)
    {
        /* @var CustomerModel $model */
        $model = $e->getParam('model');
        $post = $e->getParam('post');
        $this->populate($model, ['user']);

        $user = $model->getUser();

        if ($user->getFirstname() != $model->getFirstname() ||
            $user->getLastname() != $model->getLastname() ||
            $user->getEmail() != $model->getEmail()) {

            /* @var \UthandoUser\Service\User $userService */
            $userService = $this->getService('UthandoUser\Service\User');
            $userService->edit($user, $post);
        }
    }

    /**
     * @return \UthandoUser\Model\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
}
