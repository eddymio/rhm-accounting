<?php
namespace MyEnterprise\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single account
 * @ORM\Entity
 * @ORM\Table(name="tax")
 */
class Tax
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="tax_name",type="string")
	 */
	protected $taxName;
	
	/**
	 * @ORM\Column(name="tax_amount",type="decimal")
	 */
	protected $taxAmount;
	
	/**
	 * @ORM\OneToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="account_debit_id", referencedColumnName="id")
	 */
	protected $accountDebitId;
	
	/**
	 * @ORM\OneToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="account_credit_id", referencedColumnName="id")
	 */
	protected $accountCreditId;
	
	// Returns ID of this tax.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns tax name
	public function getName()
	{
		return $this->taxName;
	}
	
	// Sets Name of tax
	public function setName($name)
	{
		$this->taxName = $name;
	}
	

	// Returns amountl - ie 20%
	public function getAmount()
	{
		return $this->taxAmount;
	}
	
	// Sets amount
	public function setAmount($amount)
	{
		$this->taxAmount = $amount;
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
	
}