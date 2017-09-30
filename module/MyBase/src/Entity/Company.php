<?php
namespace MyBase\Entity;

//use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single company application
 * @ORM\Entity
 * @ORM\Table(name="company_application")
 */
class Company
{
	//http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#annref-onetoone
	/**
	 * @ORM\Id
	 * @ORM\Column(name="application_id",type="integer")
	 */
	protected $application;
	
	/**
	 * @ORM\Id
	 * @ORM\Column(name="company_id",type="integer")
	 */
	protected $company;
	
	/*
	 * Constructor
	 */
	public function __construct()
	{
		/*$this->application = new ArrayCollection();
		$this->company = new ArrayCollection();*/
	}
	
	
	// Returns application
	public function getApplication()
	{
		return $this->application;
	}
	
	// Sets Application
	public function setApplication($app)
	{
		$this->application = $app;
	}
	
	// Returns Company
	public function getCompany()
	{
		return $this->company;
	}
	
	
	// Sets Company
	public function setCompany($comp)
	{
		$this->company = $comp;
	}
}