<?php
namespace MyChart;


class Module 
{
	
	public function getConfig() {
		

		return include __DIR__ . '/../config/module.config.php';
		
	}
	
	//https://docs.zendframework.com/tutorials/advanced-config/


}