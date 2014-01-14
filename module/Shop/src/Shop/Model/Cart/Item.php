<?php
namespace Shop\Model\Cart;

use Shop\Model\Product;

class Item
{
    /**
     * @var int
     */
	protected $qty;
	
	/**
	 * @var Product
	 */
	protected $product;
    
    public function getQty()
    {
        return $this->qty;
    }
    
    public function setQty($qty)
    {
        $qty = (int) $qty;
        $this->qty = $qty;
        return $this;
    }
    
    public function getProduct()
    {
        return $this->product;
    }
    
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }
    
    /**
     * Proxies caals to Product Model.
     * 
     * @param method $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array(array($this->getProduct(), $method), $arguments);
    }
}