<?php
namespace MyJournal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single type of entry - ie : DEBIT or CREDIT
 * @ORM\Entity
 * @ORM\Table(name="entry_type")
 */
class Type
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="name")
	 */
	protected $name;
	
	/**
	 * @ORM\Column(name="description")
	 */
	protected $description;
	
	
	// Returns ID of this type.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns name
	public function getName()
	{
		return $this->name;
	}
	
	// Sets type name
	public function setName($name)
	{
		$this->name = $name;
	}
	

	// Returns desdcription
	public function getDescription()
	{
		return $this->description;
	}
	
	// Sets description
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	
	
}