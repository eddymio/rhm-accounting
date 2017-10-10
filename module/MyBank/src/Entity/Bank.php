<?php
namespace MyBank\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single bank
 * @ORM\Entity
 * @ORM\Table(name="bank_detail")
 */
class Bank
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="detail_name")
	 */
	protected $name;
	
	/**
	 * @ORM\Column(name="date_creation",type="date")
	 */
	protected $date;
	
	/**
	 * @ORM\Column(name="is_default")
	 */
	protected $isDefault;
	
	/**
	 * @ORM\Column(name="bank_name")
	 */
	protected $bankName;
	
	/**
	 * @ORM\Column(name="bank_address")
	 */
	protected $address;
	
	/**
	 * @ORM\Column(name="bank_postcode")
	 */
	protected $postcode;
	
	/**
	 * @ORM\Column(name="bank_city")
	 */
	protected $city;
	
	/**
	 * @ORM\Column(name="bank_phone")
	 */
	protected $phone;
	
	/**
	 * @ORM\OneToOne(targetEntity="\MyAuth\Entity\Country")
	 * @ORM\JoinColumn(name="bank_country", referencedColumnName="id")
	 */
	protected $country;
	
	
	
	// Returns ID of this code.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns NAME
	public function getName()
	{
		return $this->name;
	}
	
	// Sets detail name
	public function setName($name)
	{
		$this->name = $name;
	}
	
	
	// Returns date
	public function getDate()
	{
		return $this->date;
	}
	
	// Sets date
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	
	// Returns is default ?
	public function getIsDefault()
	{
		return $this->isDefault;
	}
	
	// Sets default
	public function setIsDefault($bool)
	{
		$this->isDefault = $bool;
	}
	
	// Returns bank NAME
	public function getBankName()
	{
		return $this->bankName;
	}
	
	// Sets detail name
	public function setBankName($name)
	{
		$this->bankName = $name;
	}
	// Returns compnay address
	public function getAddress()
	{
		return $this->address;
	}
	
	// Sets company identification
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	// Returns compnay address
	public function getPostcode()
	{
		return $this->postcode;
	}
	
	// Sets company identification
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
	}
	
	// Returns compnay city
	public function getCity()
	{
		return $this->city;
	}
	
	// Sets company identification
	public function setCity($city)
	{
		$this->city = $city;
	}
	
	// Returns compnay phone
	public function getPhone()
	{
		return $this->phone;
	}
	
	// Sets company identification
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}
	
	/**
	 * Returns country for this bank.
	 * @return id
	 */
	public function getCountry()
	{
		return $this->country;
	}
	
	// Sets bank country
	public function setCountry($country)
	{
		$this->country = $country;
	}
}