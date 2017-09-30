<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a company data update.
 * @ORM\Entity
 * @ORM\Table(name="user_update",indexes={@ORM\Index(name="update_idx", columns={"user_id", "date_update"})})
 */
	
class Update
{
	
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="integer")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="user_id",type="integer")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id") 
	 */
	protected $user_id;
	
	/**
	 * @ORM\Column(name="date_update",type="date")
	 */
	protected $dateOfUpdate;
	
	/**
	 * @ORM\Column(name="legal_status_id")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Legal")
	 * @ORM\JoinColumn(name="legal_status_id", referencedColumnName="id")
	 */
	protected $legalStatus;
	
	/**
	 * @ORM\Column(name="tax_system_id",type="smallint")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Tax")
	 * @ORM\JoinColumn(name="tax_system_id", referencedColumnName="id")
	 */
	protected $taxSystem;
	
	/**
	 * @ORM\Column(name="social_system_id",type="smallint")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Social")
	 * @ORM\JoinColumn(name="social_system_id", referencedColumnName="id")
	 */
	protected $socialSystem;
	
	/**
	 * @ORM\Column(name="profit_type_id",type="smallint")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Profit")
	 * @ORM\JoinColumn(name="profit_type_id", referencedColumnName="id")
	 */
	protected $profitType;
	
	
	
	// Returns ID of this update.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this update.
	public function setId($id)
	{
		$this->id = $id;
	}
	// Returns User ID of this update.
	public function getUserId()
	{
		return $this->user_id;
	}
	
	// Sets ID of this update user.
	public function setUserId($id)
	{
		$this->user_id = $id;
	}
	
	// Returns date.
	public function getDate()
	{
		return $this->dateOfUpdate;
	}
	
	// Sets date.
	public function setDate($date)
	{
		$this->dateOfUpdate= $date;
	}
	
	// Returns legal status.
	public function getLegal()
	{
		return $this->legalStatus;
	}
	
	// Sets date.
	public function setLegal($data)
	{
		$this->legalStatus= $data;
	}
	
	// Returns tax status.
	public function getTax()
	{
		return $this->taxSystem;
	}
	
	// Sets tax.
	public function setTax($data)
	{
		$this->taxSystem= $data;
	}
	
	// Returns social status.
	public function getSocial()
	{
		return $this->socialSystem;
	}
	
	// Sets social.
	public function setSocial($data)
	{
		$this->socialSystem= $data;
	}
	
	
	// Returns profit type.
	public function getProfit()
	{
		return $this->profitType;
	}
	
	// Sets profit.
	public function setProfit($data)
	{
		$this->profitType= $data;
	}
}