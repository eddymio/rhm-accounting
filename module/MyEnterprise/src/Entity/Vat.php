<?php
namespace MyEnterprise\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single account
 * @ORM\Entity
 * @ORM\Table(name="vat")
 */
class Vat
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="vat_name",type="string")
	 */
	protected $vatName;
	
	/**
	 * @ORM\Column(name="vat_amount",type="decimal")
	 */
	protected $vatAmount;
	
	/**
	 * One VAT entry has One debit entry in Account chart
	 * @ORM\OneToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="account_debit_id", referencedColumnName="id")
	 */
	protected $accountDebitId;
	
	/**
	 * One VAT entry has One credit entry in Account chart
	 * @ORM\OneToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="account_credit_id", referencedColumnName="id")
	 */
	protected $accountCreditId;
	
	
	/**
	 * @ORM\Column(name="is_default")
	 */
	protected $isDefault;
	
	
	// Returns ID of this vat.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns vat name
	public function getName()
	{
		return $this->vatName;
	}
	
	// Sets Name of vat
	public function setName($name)
	{
		$this->vatName = $name;
	}
	

	// Returns amount - ie 20%
	public function getAmount()
	{
		return $this->vatAmount;
	}
	
	// Sets amount
	public function setAmount($amount)
	{
		$this->vatAmount = $amount;
	}
	
	/**
	 * Returns account debit id for this vat entry.
	 * @param accountDebitId
	 */
	public function getAccountDebitId()
	{
		return $this->accountDebitId;
	}
	
	/**
	 * Adds a new account ID to this VAT.
	 * @param accountDebitId
	 */
	public function setAccountDebitId($id)
	{
		$this->accountDebitId= $id;
	}
	
	/**
	 * Returns account credit id for this vat entry.
	 * @param accountCreditId
	 */
	public function getAccountCreditId()
	{
		return $this->accountCreditId;
	}
	
	/**
	 * Adds a new account ID to this VAT.
	 * @param accountCreditId
	 */
	public function setAccountCreditId($id)
	{
		$this->accountCreditId= $id;
	}
	
	// Returns is default ?
	public function getIsDefault()
	{
		return $this->isDefault;
	}
	
	// Sets as Default
	public function setIsDefault($val)
	{
		$this->isDefault = $val;
	}
	
	
	
}