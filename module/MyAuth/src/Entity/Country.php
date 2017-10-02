<?php
namespace MyAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a country.
 * @ORM\Entity
 * @ORM\Table(name="country")
 */
class Country
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(name="id",type="smallint")
	 */
    protected $id;

    /**
     * @ORM\Column(name="country_name")
     */
    protected $country_name;

    /**
     * @ORM\Column(name="country_code")
     */
    protected $country_code;



    public function getId()
    {
        return $this->id;
    }


    public function setCountryName($countryName)
    {
        $this->country_name = $countryName;

    }

    public function getCountryName()
    {
        return $this->country_name;
    }


    public function setCountryCode($countryCode)
    {
        $this->country_code = $countryCode;

    }


    public function getCountryCode()
    {
        return $this->country_code;
    }
}

