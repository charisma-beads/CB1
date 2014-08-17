<?php
namespace Shop\Model\Post;

use UthandoCommon\Model\Model;
use UthandoCommon\Model\ModelInterface;

class Unit implements ModelInterface
{
    use Model;
    
	/**
	 * @var int
	 */
	protected $postUnitId;
	
	/**
	 * @var float
	 */
	protected $postUnit;
	
	/**
	 * @return the $postUnitId
	 */
	public function getPostUnitId()
	{
		return $this->postUnitId;
	}

	/**
	 * @param number $postUnitId
	 */
	public function setPostUnitId($postUnitId)
	{
		$this->postUnitId = $postUnitId;
		return $this;
	}

	/**
	 * @return the $postUnit
	 */
	public function getPostUnit()
	{
		return $this->postUnit;
	}

	/**
	 * @param number $postUnit
	 */
	public function setPostUnit($postUnit)
	{
		$this->postUnit = $postUnit;
		return $this;
	}
}
