<?php
namespace MyAuth\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single user
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="user_name")
	 */
	protected $user_name;
	
	/**
	 * @ORM\Column(name="user_email")
	 */
	protected $email;
	
	/**
	 * @ORM\Column(name="date_created")
	 */
	protected $dateCreated;
	
	/**
	 * @ORM\Column(name="user_password")
	 */
	protected $user_password;
	
	/**
	 * @ORM\OneToMany(targetEntity="\MyAuth\Entity\Log", mappedBy="user_id", cascade={"persist", "remove"})
	 */
	protected $logs;
	
	/**
	 * @ORM\Column(name="pwd_reset_token")
	 */
	protected $passwordResetToken;
	
	/**
	 * @ORM\Column(name="pwd_reset_token_creation_date")
	 */
	protected $passwordResetTokenCreationDate;
	
	/**
	 * @ORM\Column(name="company_id")
	 * @ORM\ManyToOne(targetEntity="\MyAuth\Entity\Company")
	 * @ORM\JoinColumn(name="company", referencedColumnName="id")
	 */
	protected $company;
	
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->logs = new ArrayCollection();
	}
	
	/**
	 * Returns logs for this user.
	 * @return array
	 */
	public function getLogs()
	{
		return $this->logs;
	}
	
	/**
	 * Adds a new log to this user.
	 * @param $log
	 */
	public function addLog()
	{
		// Log is an array with user_id and dateLog
		$newlog = new Log();
				
		$currentDate = date('Y-m-d H:i:s');
		
		$newlog->setDate($currentDate);
		$newlog->setUserId($this->id);
		
		$this->logs->add($newlog);
	}
	
	// Returns ID of this user.
	public function getId()
	{
		return $this->id;
	}
	
	// Sets ID of this user.
	public function setId($id)
	{
		$this->id = $id;
	}
	
	
	// Returns ID of company .
	public function getCompany()
	{
		return $this->company;
	}
	
	// Sets ID of this user's company.
	public function setCompany($id)
	{
		$this->company= $id;
	}
	
	
	
	// Returns username.
	public function getUsername()
	{
		return $this->user_name;
	}
	
	// Sets title.
	public function setUsername($user_name)
	{
		$this->user_name= $user_name;
	}
	
	
	// Returns post content.
	public function getUseremail()
	{
		return $this->email;
	}
	
	// Sets post content.
	public function setUseremail($user_email)
	{
		$this->email= $user_email;
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
	
	/**
	 * Returns password.
	 * @return string
	 */
	public function getPassword()
	{
		return $this->user_password;
	}
	
	/**
	 * Sets password.
	 * @param string $user_password
	 */
	public function setPassword($password)
	{
		$this->user_password = $password;
	}
	
	/**
	 * Returns password reset token.
	 * @return string
	 */
	public function getResetPasswordToken()
	{
		return $this->passwordResetToken;
	}
	
	/**
	 * Sets password reset token.
	 * @param string $token
	 */
	public function setPasswordResetToken($token)
	{
		$this->passwordResetToken = $token;
	}
	
	/**
	 * Returns password reset token's creation date.
	 * @return string
	 */
	public function getPasswordResetTokenCreationDate()
	{
		return $this->passwordResetTokenCreationDate;
	}
	
	/**
	 * Sets password reset token's creation date.
	 * @param string $date
	 */
	public function setPasswordResetTokenCreationDate($date)
	{
		$this->passwordResetTokenCreationDate = $date;
	}
	
}