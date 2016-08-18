<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\VariablePeriodo
 *
 * @ORM\Table(name="variable_periodo")
 * @ORM\Entity
 */
class VariablePeriodo
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
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=2048, nullable=false)
     */
    private $descripcion;

    
    /**
     * @var string $periodo
     *
     * @ORM\Column(name="periodo", type="string", length=4, nullable=false)
     */
    private $periodo;
//inicio
/**
     * @var Anios
     *
     * @ORM\ManyToMany(targetEntity="Anio", inversedBy="origenes")
     * @ORM\JoinTable(name="variable_anio")
     */
    private $anios;
    
     /**
     * @var Pintars
     *
     * @ORM\ManyToMany(targetEntity="Pintar", inversedBy="origenes")
     * @ORM\JoinTable(name="variable_pintar")
     */
    private $pintars;
    
    
    
    

    
     public function __construct()
    {
        $this->anios = new \Doctrine\Common\Collections\ArrayCollection();
         $this->pintars = new \Doctrine\Common\Collections\ArrayCollection();
    }
     /**
     * Add anios
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\Anio $anios
     * @return OrigenDatos
     */
    public function addAnioe(\MINSAL\IndicadoresBundle\Entity\Anio $anios)
    {
        $this->anios[] = $anios;

        return $this;
    }

    /**
     * Remove anios
     *
     * @param \MINSAL\IndicadoresBundle\Entity\Anio $anios
     */
    public function removeAnioe(\MINSAL\IndicadoresBundle\Entity\Anio $anios)
    {
        $this->anios->removeElement($anios);
    }

    /**
     * Get anios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnios()
    {
        return $this->anios;
    }
     /**
     * Add pintars
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\Pintar $pintars
     * @return OrigenDatos
     */
    public function addPintare(\MINSAL\IndicadoresBundle\Entity\Pintar $pintars)
    {
        $this->pintars[] = $pintars;

        return $this;
    }

    /**
     * Remove pintars
     *
     * @param \MINSAL\IndicadoresBundle\Entity\Pintar $pintars
     */
    public function removePintare(\MINSAL\IndicadoresBundle\Entity\Pintar $pintars)
    {
        $this->pintars->removeElement($pintars);
    }

    /**
     * Get pintars
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPintars()
    {
        return $this->pintars;
    }
//fin



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
     * Set descripcion
     *
     * @param  string $descripcion
     * @return variablePeriodo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }
    /**
     * Get periodo
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

  /**
     * Set periodo
     *
     * @param  string $periodo
     * @return variablePeriodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }    
    
    
    public function __toString()
    {
        return $this->descripcion ? :'';
    }

   
}