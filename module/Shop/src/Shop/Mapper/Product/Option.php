<?php
namespace Shop\Mapper\Product;

use Application\Mapper\AbstractMapper;

class Option extends AbstractMapper
{
	protected $table = 'productOption';
	protected $primary = 'productOptionId';
	protected $model = 'Shop\Model\Product\Option';
	protected $hydrator = 'Shop\Hydrator\Product\Option';
}