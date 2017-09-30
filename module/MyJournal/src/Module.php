<?php
namespace MyJournal;

use MyJournal\Form\JournalFieldset;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module  implements FormElementProviderInterface
{
	
	public function getConfig() {
		

		return include __DIR__ . '/../config/module.config.php';
		
	}
	
	//https://docs.zendframework.com/tutorials/advanced-config/
	
	//https://framework.zend.com/manual/2.4/en/modules/zend.form.advanced-use-of-forms.html
	public function getFormElementConfig()
	{
		return array(
				'factories' => array(
						'JournalFieldset' => function($sm) {
							// I assume here that the MyJournal\Entity\Journal
							// dependency have been defined too
							
							$serviceLocator = $sm->getServiceLocator();
							$journal = $serviceLocator->get('MyJournal\Entity\Journal');
							$fieldset = new JournalFieldset($journal);
							return $fieldset;
							}
						)
				);
	}

}