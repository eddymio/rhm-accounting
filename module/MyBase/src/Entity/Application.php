<?php
namespace MyBase\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single application
 * @ORM\Entity
 * @ORM\Table(name="application")
 */
class Application
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\ManyToOne(targetEntity="\MyBase\Entity\Category")
	 * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
	 */
	protected $category;
	
	/**
	 * @ORM\Column(name="application_name")
	 */
	protected $applicationName;
	
	/**
	 * @ORM\Column(name="short_name")
	 */
	protected $shortName;
	
	
	/**
	 * @ORM\Column(name="application_description")
	 */
	protected $applicationDescription;
	
	
	/**
	 * Returns category object for this application .
	 * @return object
	 */
	public function getCategory()
	{
		return $this->category;
	}
	
	/**
	 * set category for this application .
	 * @set id
	 */
	public function setCategory($id)
	{
		$this->category = $id;
	}
		
	
	// Returns ID of this app.
	public function getId()
	{
		return $this->id;
	}
	
	// Returns app name
	public function getName()
	{
		return $this->applicationName;
	}
	
	// Sets name of app
	public function setName($name)
	{
		$this->applicationName = $name;
	}
	
	// Returns app short name
	public function getShortname()
	{
		return $this->shortName;
	}
	
	// Sets short name of app
	public function setShortname($name)
	{
		$this->shortName = $name;
	}
	
	// Returns description
	public function getDescription()
	{
		return $this->applicationDescription;
	}
	
	// Sets company identification
	public function setDescription($description)
	{
		$this->applicationDescription = $description;
	}
	

	
}