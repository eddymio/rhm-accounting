<?php
namespace MyJournal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single journal element
 * @ORM\Entity
 * @ORM\Table(name="journal")
 */
class Journal
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="journal_date")
	 */
	protected $date;
	
	/**
	 * @ORM\Column(name="journal_wording")
	 */
	protected $wording;
	
	/**
	 * @ORM\Column(name="proof_reference")
	 */
	protected $reference;
	
	/**
	 * @ORM\Column(name="proof_reference_date")
	 */
	protected $referenceDate;
	
	/**
	 * @ORM\Column(name="validation_date")
	 */
	protected $validationDate;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyJournal\Entity\Code")
	 * @ORM\JoinColumn(name="journal_code_id", referencedColumnName="id")
	 */
	protected $code;
	
	
	// Returns ID of this journal element.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns date
	public function getDate()
	{
		return $this->date;
	}
	
	// Sets journal date
	public function setDate($date)
	{
		$this->date = $date;
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
	
	// Returns reference
	public function getReference()
	{
		return $this->reference;
	}
	
	// Sets reference
	public function setReference($reference)
	{
		$this->reference = $reference;
	}
	
	// Returns ref date
	public function getReferenceDate()
	{
		return $this->referenceDate;
	}
	
	// Sets journal ref date
	public function setReferenceDate($ref)
	{
		$this->referenceDate= $ref;
	}

	// Returns validation date
	public function getValidationDate()
	{
		return $this->validationDate;
	}
	
	// Sets journal validation date
	public function setValidationDate($date)
	{
		$this->validationDate = $date;
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
		
}