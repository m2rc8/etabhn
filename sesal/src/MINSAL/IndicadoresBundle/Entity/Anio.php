<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\Anio
 *
 * @ORM\Table(name="anio")
 * @ORM\Entity
 */
class Anio
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=100, nullable=false)
     * @CustomAssert\OnlyAlphanumeric(message="OnlyAlphanumeric.Message")
     */
    private $nombre;

/**
     * @ORM\ManyToMany(targetEntity="VariablePeriodo", mappedBy="anios")
     */
    private $origenes;
    
   /**
     * @ORM\ManyToMany(targetEntity="VariablehPeriodo", mappedBy="aniosh")
     */
    private $origenesh;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->origenes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->origenesh = new \Doctrine\Common\Collections\ArrayCollection();
    }



 /**
     * Add origenesh
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\VariablehPeriodo $origenesh
     * @return Periodo
     */
    public function addOrigenhe(\MINSAL\IndicadoresBundle\Entity\VariablehPeriodo $origenesh)
    {
        $this->origenesh[] = $origenesh;

        return $this;
    }

    /**
     * Remove origenesh
     *
     * @param \MINSAL\IndicadoresBundle\Entity\VariablehPeriodo $origenesh
     */
    public function removeOrigenhe(\MINSAL\IndicadoresBundle\Entity\VariablehPeriodo $origenesh)
    {
        $this->origenesh->removeElement($origenesh);
    }

    /**
     * Get origenesh
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrigenesh()
    {
        return $this->origenesh;
    }







    /**
     * Add origenes
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\VariablePeriodo $origenes
     * @return Periodo
     */
    public function addOrigene(\MINSAL\IndicadoresBundle\Entity\VariablePeriodo $origenes)
    {
        $this->origenes[] = $origenes;

        return $this;
    }

    /**
     * Remove origenes
     *
     * @param \MINSAL\IndicadoresBundle\Entity\VariablePeriodo $origenes
     */
    public function removeOrigene(\MINSAL\IndicadoresBundle\Entity\VariablePeriodo $origenes)
    {
        $this->origenes->removeElement($origenes);
    }

    /**
     * Get origenes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrigenes()
    {
        return $this->origenes;
    }
  

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param  string   $nombre
     * @return Anio
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    
   

   
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Set id
     *
     * @param  integer  $id
     * @return Anio
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
}