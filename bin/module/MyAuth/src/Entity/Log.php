<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a log.
 * @ORM\Entity
 * @ORM\Table(name="log")
 */
class Log
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="user_id")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\User", inversedBy="logs")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id") 
	 */
	protected $user_id;
	
	/**
	 * @ORM\Column(name="date_log")
	 */
	protected $date_log;
	
	// Returns ID of this log.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this log.
	public function setId($id)
	{
		$this->id = $id;
	}
	// Returns User ID of this log.
	public function getUserId()
	{
		return $this->user_id;
	}
	
	// Sets ID of this log.
	public function setUserId($id)
	{
		$this->user_id = $id;
	}
	
	// Returns date.
	public function getDate()
	{
		return $this->date_log;
	}
	
	// Sets name.
	public function setDate($date_log)
	{
		$this->date_log = $date_log;
	}
}