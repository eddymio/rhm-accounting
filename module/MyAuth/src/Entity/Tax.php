<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a tax system.
 * @ORM\Entity
 * @ORM\Table(name="tax_system")
 */
class Tax
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="system_name")
	 */
	protected $systemName;
	
	/**
	 * @ORM\Column(name="system_description")
	 */
	protected $systemDescription;
	
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
	public function getSystemName()
	{
		return $this->systemName;
	}
	
	// Sets name of this status.
	public function setSystemName($name)
	{
		$this->systemName = $name;
	}
	
	// Returns $descriptionof this status.
	public function getSystemDescription()
	{
		return $this->systemDescription;
	}
	
	// Sets $descriptionof this status.
	public function setSystemDescription($description)
	{
		$this->systemDescription = $description;
	}
	
}