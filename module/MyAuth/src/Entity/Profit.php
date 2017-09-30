<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a profit system.
 * @ORM\Entity
 * @ORM\Table(name="profit_type")
 */
class Profit
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="profit_name")
	 */
	protected $profitName;
	
	/**
	 * @ORM\Column(name="profit_description")
	 */
	protected $profitDescription;
	
	// Returns ID of this system.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this system.
	public function setId($id)
	{
		$this->id = $id;
	}
	// Returns name of this status.
	public function getProfitName()
	{
		return $this->profitName;
	}
	
	// Sets name of this status.
	public function setProfitName($name)
	{
		$this->profitName = $name;
	}
	
	// Returns $descriptionof this status.
	public function getProfitDescription()
	{
		return $this->profitDescription;
	}
	
	// Sets $descriptionof this status.
	public function setProfitDescription($description)
	{
		$this->profitDescription = $description;
	}
	
}