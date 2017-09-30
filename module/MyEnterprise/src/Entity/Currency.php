<?php
namespace MyEnterprise\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single account
 * @ORM\Entity
 * @ORM\Table(name="currency")
 */
class Currency
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="currency_name")
	 */
	protected $currencyName;
	
	/**
	 * @ORM\Column(name="currency_label")
	 */
	protected $currencyLabel;
	
	
	/**
	 * @ORM\Column(name="is_default")
	 */
	protected $isDefault;
	
	
	
	// Returns ID of this currency.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns currency name
	public function getName()
	{
		return $this->currencyName;
	}
	
	// Sets Name of currency
	public function setName($name)
	{
		$this->currencyName = $name;
	}
	

	// Returns label - ie EUR
	public function getLabel()
	{
		return $this->currencyLabel;
	}
	
	// Sets label
	public function setLabel($label)
	{
		$this->currencyLabel = $label;
	}
	
	
	// Returns is default ?
	public function getIsDefault()
	{
		return $this->isDefault;
	}
	
	// Sets  Default if it is
	public function setIsDefault($val)
	{
		$this->isDefault = $val;
	}
	
	
	
}