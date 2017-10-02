<?php
namespace MyBank\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a bank account.
 * @ORM\Entity
 * @ORM\Table(name="bank_account")
 */
class Account
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="account_name")
	 */
	protected $name;
	
	/**
	 * @ORM\Column(name="date_creation")
	 */
	protected $date;
	
	/**
	 * @ORM\Column(name="account_number")
	 */
	protected $number;

	/**
	 * @ORM\Column(name="account_iban")
	 */
	protected $iban;
	
	/**
	 * @ORM\Column(name="account_swift")
	 */
	protected $swift;
	/**
	 * @ORM\Column(name="account_bic")
	 */
	protected $bic;

	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyBank\Entity\Bank")
	 * @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
	 */
	protected $bank;
	
	
	// Returns ID of this account.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this status.
	public function setId($id)
	{
		$this->id = $id;
	}
	
	
	// Returns name of this account.
	public function getName()
	{
		return $this->name;
	}
	
	// Sets name of this status.
	public function setName($name)
	{
		$this->name = $name;
	}
	
	// Returns $descriptionof this status.
	public function getDate()
	{
		return $this->date;
	}
	
	// Sets $descriptionof this status.
	public function setDate($date)
	{
		$this->date = $date;
	}
	
	// Returns $descriptionof this status.
	public function getNumber()
	{
		return $this->number;
	}
	
	// Sets $descriptionof this status.
	public function setNumber($number)
	{
		$this->number = $number;
	}
	// Returns $descriptionof this status.
	public function getIban()
	{
		return $this->iban;
	}
	
	// Sets $descriptionof this status.
	public function setIban($iban)
	{
		$this->iban = $iban;
	}
	// Returns $descriptionof this status.
	public function getBic()
	{
		return $this->bic;
	}
	
	// Sets $descriptionof this status.
	public function setBic($bic)
	{
		$this->bic = $bic;
	}
	// Returns $descriptionof this status.
	public function getSwift()
	{
		return $this->swift;
	}
	
	// Sets $descriptionof this status.
	public function setSwift($swift)
	{
		$this->swift = $swift;
	}
	
	/**
	 * Returns id for this bank.
	 * @return object
	 */
	public function getBank()
	{
		return $this->bank;
	}
	
	// Sets bank country
	public function setBank($bank)
	{
		$this->bank = $bank;
	}
}