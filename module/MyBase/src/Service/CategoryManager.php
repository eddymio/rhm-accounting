<?php
namespace MyBase\Service;

use MyBase\Entity\Category;
/**
 * This service is responsible for adding/editing categories
 */
class CategoryManager
{
	/**
	 * Doctrine entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	
	/**
	 * Constructs the service.
	 */
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	/**
	 * This method adds a new application.
	 */
	public function addCategory($data)
	{
		// Do not allow several application with the same name.
		if($this->checkCategoryExists($data['name'])) {
			throw new \Exception("Category with  name " . $data['name'] . " already exists");
		}
		
		// Create new category entity.
		$category = new Category();
		$category->setName($data['name']);
		$category->setDescription($data['description']);
		
		if(isset($data['id'])) {
						
			$category->setId($data['id']);
		}
		
		// Add the entity to the entity manager.
		$this->entityManager->persist($category);
		
		// Apply changes to database.
		$this->entityManager->flush();
		
		return $category;
	}
	
	/**
	 * Get the list of applications
	 */
	public function getCategories() {
		
		$categories = $this->entityManager->getRepository(Category::class)
		->findAll();
		
		$categories= [];
		
		// transform the object into an array for the select form
		foreach ($categories as $object)
		{
			$categories[$object->getId()] = $object->getName();
		}
		
		return $categories;
	}
	
	
	
	/**
	 * Checks whether an active app with given name already exists in the database.
	 */
	public function checkCategoryExists($name) {
		
		$category  = $this->entityManager->getRepository(Category::class)
		->findOneBy(array('category_name' => $name));
		
		return $category !== null;
	}
	
}