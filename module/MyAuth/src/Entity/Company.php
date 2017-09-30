<?php
namespace MyAuth\Entity;

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
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="company_identification")
	 */
	protected $companyIdentification;
	
	/**
	 * @ORM\Column(name="company_name",unique=TRUE)
	 */
	protected $companyName;
	
	/**
	 * @ORM\Column(name="date_created",type="datetime")
	 */
	protected $dateCreated;

	
	/**
	 * @ORM\Column(name="company_url",nullable=true)
	 */
	protected $url;
	
	
	/**
	 * @ORM\Column(name="fiscal_year_start",type="string",length=4,nullable=true)
	 */
	protected $start;

	/**
	 * @ORM\Column(name="fiscal_year_end",type="string",length=4,nullable=true)
	 */
	protected $end;
	
	

	
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
	
	// Returns company name.
	public function getUrl()
	{
		return $this->url;
	}
	
	// Sets Company name
	public function setUrl($url)
	{
		$this->url= $url;
	}
	
	// Returns company fiscal start.
	public function getStart()
	{
		return $this->start;
	}
	
	// Sets Company name
	public function setStart($str)
	{
		$this->start= $str;
	}
	
	
	
	// Returns company fiscal end.
	public function getEnd()
	{
		return $this->end;
	}
	
	// Sets Company name
	public function setEnd($str)
	{
		$this->end= $str;
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
		return $this->dateCreated = $dateCreated;
	}
	
}