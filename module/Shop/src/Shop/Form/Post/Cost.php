<?php

namespace Shop\Form\Post;

use Shop\Service\Post\Level;
use Shop\Service\Post\Zone;
use Zend\Form\Form;

class Cost extends Form
{
	/**
	 * @var Level
	 */
	protected $postLevelService;
	
	/**
	 * @var Zone
	 */
	protected $postZoneService;
	
	public function __construct()
	{
		parent::__construct('Post Cost Form');
		
		$this->add(array(
			'name'	=> 'postCostId',
			'type'	=> 'hidden',
		));
		
		$this->add(array(
			'name'			=> 'cost',
			'type'			=> 'number',
			'attributes'	=> array(
				'placeholder'	=> 'Price:',
				'autofocus'		=> true,
				'step'			=> '0.01'
			),
			'options'		=> array(
				'label'			=> 'Cost:',
				'required'		=> true,
				'help-inline'	=> 'Do not include the currency sign or commas.',
			),
		));
		
		$this->add(array(
			'name'			=> 'vatInc',
			'type'			=> 'checkbox',
			'options'		=> array(
				'label'			=> 'Vat Included:',
				'required' 		=> true,
				'use_hidden_element' => true,
				'checked_value' => '1',
				'unchecked_value' => '0',
			),
		));
	}
	
	public function init()
	{
		$this->add(array(
			'name'		=> 'postLevelId',
			'type'		=> 'select',
			'options'	=> array(
				'label'			=> 'Post Level:',
				'required'		=> true,
				'empty_option'	=> '---Please select a level---',
				'value_options'	=> $this->getPostLevelList(),
			),
		));
		
		$this->add(array(
			'name'		=> 'postZoneId',
			'type'		=> 'select',
			'options'	=> array(
				'label'			=> 'Post Zone:',
				'required'		=> true,
				'empty_option'	=> '---Please select a weight---',
				'value_options'	=> $this->getPostZoneList(),
			),
		));
	}
	
	public function getPostLevelList()
	{
		$postLevels = $this->getPostLevelService()->fetchAll();
		$postLevelOptions = array();
		
		/* @var $level \Shop\Model\Post\Level */
		foreach($postLevels as $level) {
			$postLevelOptions[$level->getPostLevelId()] = $level->getPostLevel();
		}
		
		return $postLevelOptions;
	}
	
	public function getPostZoneList()
	{
		$postZones = $this->getPostZoneService()->fetchAll();
		$postZoneOptions = array();
		
		/* @var $zone \Shop\Model\Post\Zone */
		foreach($postZones as $zone) {
			$postZoneOptions[$zone->getPostZoneId()] = $zone->getZone();
		}
		
		return $postZoneOptions;
	}
	
	/**
	 * @return \Shop\Service\Post\Level
	 */
	public function getPostLevelService()
	{
		return $this->postLevelService;
	}
	
	/**
	 * @param Level $postLevelService
	 * @return \Shop\Form\Post\Cost
	 */
	public function setPostLevelService(Level $postLevelService)
	{
		$this->postLevelService = $postLevelService;
		return $this;
	}
	
	/**
	 * @return \Shop\Service\Post\Zone
	 */
	public function getPostZoneService()
	{
		return $this->postZoneService;
	}
	
	/**
	 * @param Zone $postZoneService
	 * @return \Shop\Form\Post\Cost
	 */
	public function setPostZoneService(Zone $postZoneService)
	{
		$this->postZoneService = $postZoneService;
		return $this;
	}
	
}