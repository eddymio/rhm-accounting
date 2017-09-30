<?php
namespace MyBase\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single category of app
 * @ORM\Entity
 * @ORM\Table(name="application_category")
 */
class Category
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(name="category_name")
	 */
	protected $categoryName;
	
	
	/**
	 * @ORM\Column(name="category_description")
	 */
	protected $categoryDescription;
	
	

	// Returns ID of this categ.
	public function getId()
	{
		return $this->id;
	}
	

	
	// Returns  name
	public function getName()
	{
		return $this->categoryName;
	}
	
	// Sets name of app
	public function setName($name)
	{
		$this->categoryName = $name;
	}
	
	// Returns description
	public function getDescription()
	{
		return $this->categoryDescription;
	}
	
	// Sets company identification
	public function setDescription($description)
	{
		$this->categoryDescription = $description;
	}
	

	
}