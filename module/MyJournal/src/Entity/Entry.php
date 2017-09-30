<?php
namespace MyJournal\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single journal entry
 * @ORM\Entity
 * @ORM\Table(name="journal_entry")
 */
class Entry
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="entry_wording")
	 */
	protected $wording;
	
	/**
	 * @ORM\Column(name="entry_amount")
	 */
	protected $amount;
	
	/**
	 * @ORM\Column(name="internal_wording")
	 */
	protected $internalWording;
	
	/**
	 * @ORM\Column(name="internal_wording_date")
	 */
	protected $internalWordingDate;
	
	/**
	 * @ORM\Column(name="currency_amount")
	 */
	protected $currencyAmount;
	
	/**
	 * Several entries for one Journal element
	 * @ORM\ManyToOne(targetEntity="\MyJournal\Entity\Journal")
	 * @ORM\JoinColumn(name="journal_id", referencedColumnName="id")
	 */
	protected $journal;
	
	/**
	 * One entry of one type only - several entries can have same type
	 * @ORM\ManyToOne(targetEntity="\MyJournal\Entity\Type")
	 * @ORM\JoinColumn(name="entry_type_id", referencedColumnName="id")
	 */
	protected $type;
	
	/**
	 * One entry refers to an account from the chart
	 * @ORM\ManyToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="chart_account_id", referencedColumnName="id")
	 */
	protected $chartAccount;
	
	/**
	 * One entry refers to an account from the chart
	 * @ORM\ManyToOne(targetEntity="\MyChart\Entity\Account")
	 * @ORM\JoinColumn(name="chart_account_aux_id", referencedColumnName="id")
	 */
	protected $chartAccountAux;
	
	/**
	 * One entry refers to one currency
	 * @ORM\ManyToOne(targetEntity="\MyEnterprise\Entity\Currency")
	 * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
	 */
	protected $currency;
	
		
	
	// Returns ID of this journal element.
	public function getId()
	{
		return $this->id;
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
	
	
	// Returns amount
	public function getAmount()
	{
		return $this->amount;
	}
	
	// Sets entry amount
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}
	
	
	// Returns internal wording
	public function getInternalWording()
	{
		return $this->internalWording;
	}
	
	// Sets journal internal wording
	public function setInternalWording($wording)
	{
		$this->internalWording = $wording;
	}
	
	// Returns internal wording date
	public function getInternalWordingDate()
	{
		return $this->internalWordingDate;
	}
	
	// Sets journal internal wording
	public function setInternalWordingDate($date)
	{
		$this->internalWordingDate = $date;
	}
	
	// Returns currency amount
	public function getCurrencyAmount()
	{
		return $this->currencyAmount;
	}
	
	// Sets entry currency amount
	public function setCurrencyAmount($amount)
	{
		$this->currencyAmount = $amount;
	}
	
	// Returns journal entity
	public function getJournal()
	{
		return $this->journal;
	}
	
	// Sets entry journal
	public function setJournal($journal)
	{
		$this->journal = $journal;
	}
	
	// Returns type entity
	public function getType()
	{
		return $this->type;
	}
	
	// Sets entry type
	public function setType($type)
	{
		$this->type = $type;
	}
	
	// Returns account entity
	public function getAccount()
	{
		return $this->chartAccount;
	}
	
	// Sets entry account
	public function setAccount($account)
	{
		$this->chartAccount = $account;
	}
	
	// Returns account entity
	public function getAuxAccount()
	{
		return $this->chartAccountAux;
	}
	
	// Sets entry account
	public function setAuxAccount($account)
	{
		$this->chartAccountAux = $account;
	}
	
	// Returns currency 
	public function getCurrency()
	{
		return $this->currency;
	}
	
	// Sets entry currency 
	public function setCurrency($currency)
	{
		$this->currency = $currency;
	}
}