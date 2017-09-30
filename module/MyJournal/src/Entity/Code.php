<?php
namespace MyJournal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single account
 * @ORM\Entity
 * @ORM\Table(name="journal_code")
 */
class Code
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="journal_code")
	 */
	protected $code;
	
	/**
	 * @ORM\Column(name="journal_wording")
	 */
	protected $wording;
	
	
	// Returns ID of this code.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns code
	public function getCode()
	{
		return $this->code;
	}
	
	// Sets journal code
	public function setCode($code)
	{
		$this->code = $code;
	}
	

	// Returns wording
	public function getWording()
	{
		return $this->wording;
	}
	
	// Sets wording
	public function setWording($wording)
	{
		$this->wording = $wording;
	}
	
	
	
}