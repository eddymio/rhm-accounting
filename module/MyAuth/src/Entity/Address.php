<?php
namespace MyAuth\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single address
 * @ORM\Entity
 * @ORM\Table(name="company_address")
 */
class Address
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Company")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
	 */
	protected $companyId;
	
	/**
	 * @ORM\Column(name="address_name")
	 */
	protected $addressName;
	
	/**
	 * @ORM\Column(name="date_created",type="datetime")
	 */
	protected $dateCreated;
	
	/**
	 * @ORM\Column(name="company_address")
	 */
	protected $companyAddress;
	
	/**
	 * @ORM\Column(name="company_postcode")
	 */
	protected $companyPostcode;
	
	/**
	 * @ORM\Column(name="company_city")
	 */
	protected $companyCity;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Country")
	 * @ORM\JoinColumn(name="company_country", referencedColumnName="id")
	 */
	protected $companyCountry;
	
	/**
	 * @ORM\Column(name="company_phone")
	 */
	protected $companyPhone;
	
	
	/**
	 * Constructor.
	 
	public function __construct()
	{
		$this->companyCountry = new ArrayCollection();
		$this->companyId      = new ArrayCollection();
	}
	*/
	/**
	 * Returns country for this company address.
	 * @return array
	 */
	public function getCountry()
	{
		return $this->companyCountry;
	}
	
		/**
	 * Returns company ID for this address.
	 * @param companyId
	 */
	public function getCompany()
	{
		return $this->companyId;
	}
	
	
	
	/**
	 * Adds a new country to this company.
	 * @param $companyCountry
	 */
	public function setCountry($id)
	{
		$this->companyCountry = $id;
	}
	
	/**
	 * Adds a new company_id to this address.
	 * @param $companyId
	 */
	public function setCompany($id)
	{
		$this->companyId = $id;
	}
	
	
	// Returns ID of this address.
	public function getId()
	{
		return $this->id;
	}
	
	
	// Returns address name.
	public function getAddressName()
	{
		return $this->addressName;
	}
	
	// Sets Company name
	public function setAddressName($name)
	{
		$this->addressName= $name;
	}
	
	
	// Returns compnay address
	public function getAddress()
	{
		return $this->companyAddress;
	}
	
	// Sets company identification
	public function setAddress($address)
	{
		$this->companyAddress = $address;
	}

	// Returns compnay address
	public function getPostcode()
	{
		return $this->companyPostcode;
	}
	
	// Sets company identification
	public function setPostcode($postcode)
	{
		$this->companyPostcode = $postcode;
	}

	// Returns compnay city
	public function getCity()
	{
		return $this->companyCity;
	}
	
	// Sets company identification
	public function setCity($city)
	{
		$this->companyCity = $city;
	}
	
	// Returns compnay phone
	public function getPhone()
	{
		return $this->companyPhone;
	}
	
	// Sets company identification
	public function setPhone($phone)
	{
		$this->companyPhone = $phone;
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