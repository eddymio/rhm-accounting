<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a legal status.
 * @ORM\Entity
 * @ORM\Table(name="legal")
 */
class Legal
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="status_name")
	 */
	protected $statusName;
	
	
	// Returns ID of this status.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this status.
	public function setId($id)
	{
		$this->id = $id;
	}
	// Returns name of this status.
	public function getStatusName()
	{
		return $this->statusName;
	}
	
	// Sets name of this status.
	public function setStatusName($name)
	{
		$this->statusName = $name;
	}
	

}