<?php
namespace MyChart\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single account
 * @ORM\Entity
 * @ORM\Table(name="chart_account")
 */
class Account
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="account_id")
	 */
	protected $account;
	
	/**
	 * @ORM\Column(name="account_description")
	 */
	protected $description;
	
	
	// Returns ID of this account.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns account_id
	public function getAccount()
	{
		return $this->account;
	}
	
	// Sets pcg of account
	public function setAccount($id)
	{
		$this->account = $id;
	}
	

	// Returns description
	public function getDescription()
	{
		return $this->description;
	}
	
	// Sets description
	public function setDescription($description)
	{
		$this->description = $description;
	}
	
	
	
}