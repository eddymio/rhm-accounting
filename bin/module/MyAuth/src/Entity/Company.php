<?php
namespace MyAuth\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single company
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="company_identification")
	 */
	protected $companyIdentification;
	
	/**
	 * @ORM\Column(name="company_name")
	 */
	protected $companyName;
	
	/**
	 * @ORM\Column(name="date_created")
	 */
	protected $dateCreated;
	
	/**
	 * @ORM\Column(name="vat_number")
	 */
	protected $vatNumber;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Legal")
     * @ORM\JoinColumn(name="legal_status_id", referencedColumnName="id") 
	 */
	protected $status;
	

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->status = new ArrayCollection();
	}
	
	/**
	 * Returns status for this company.
	 * @return array
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Adds a new status to this company.
	 * @param $status
	 */
	public function setStatus($id)
	{
		$this->status = $id;
	}
	
	// Returns ID of this company.
	public function getId()
	{
		return $this->id;
	}
	
	
	// Returns company name.
	public function getCompanyName()
	{
		return $this->companyName;
	}
	
	// Sets Company name
	public function setCompanyName($name)
	{
		$this->companyName= $name;
	}
	
	
	// Returns compnay identification
	public function getCompanyIdentification()
	{
		return $this->companyIdentification;
	}
	
	// Sets company identification
	public function setCompanyIdentification($identification)
	{
		$this->companyIdentification= $identification;
	}
	
	// Returns the date when this user was created.
	public function getDateCreated()
	{
		return $this->dateCreated;
	}
	
	// Sets the date when this user was created.
	public function setDateCreated($dateCreated)
	{
		$this->dateCreated = $dateCreated;
	}
	
}