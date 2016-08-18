<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\VariablehPeriodo
 *
 * @ORM\Table(name="variableh_periodo")
 * @ORM\Entity
 */
class VariablehPeriodo
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
 /**
     * @var string $csrCmo
     *
     * @ORM\Column(name="csr_cmo", type="string", length=1, nullable=false)
     */
    private $csrCmo;
   



 /**
     * @var Aniosh
     *
     * @ORM\ManyToMany(targetEntity="Anio", inversedBy="origenesh")
     * @ORM\JoinTable(name="variableh_anio")
     */
    private $aniosh;
    
     /**
     * @var Pintars
     *
     * @ORM\ManyToMany(targetEntity="Pintar", inversedBy="origenesh")
     * @ORM\JoinTable(name="variableh_pintar")
     */
    private $pintars;
    
      public function __construct()
    {
        $this->aniosh = new \Doctrine\Common\Collections\ArrayCollection();
          $this->pintars = new \Doctrine\Common\Collections\ArrayCollection();
       
    }
    /**
     * Add aniosh
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\Anio $aniosh
     * @return OrigenDatos
     */
    public function addAniohe(\MINSAL\IndicadoresBundle\Entity\Anio $aniosh)
    {
        $this->aniosh[] = $aniosh;

        return $this;
    }

    /**
     * Remove anios
     *
     * @param \MINSAL\IndicadoresBundle\Entity\Anio $aniosh
     */
    public function removeAniohe(\MINSAL\IndicadoresBundle\Entity\Anio $aniosh)
    {
        $this->aniosh->removeElement($aniosh);
    }

    /**
     * Get anios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAniosh()
    {
        return $this->aniosh;
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
     * @return variablehPeriodo
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
     * @return variablehPeriodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }
    //$csrCmo
    
      /**
     * Get csrCmo
     *
     * @return string
     */
    public function getCsrCmo()
    {
        return $this->csrCmo;
    }

  /**
     * Set csrCmo
     *
     * @param  string $csrCmo
     * @return variablehPeriodo
     */
    public function setCsrCmo($csrCmo)
    {
        $this->csrCmo = $csrCmo;

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