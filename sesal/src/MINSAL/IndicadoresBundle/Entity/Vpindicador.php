<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\Vpindicador
 *
 * @ORM\Table(name="vpindicador")
 * @ORM\Entity
 */
class Vpindicador
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
     * @var integer $idindicador
     *
     * @ORM\Column(name="idindicador", type="integer", nullable=false)
     */
    private $idindicador;    
    
    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=2048, nullable=true)
     */
    private $descripcion;
    
    /**
     * @var string $periodo
     *
     * @ORM\Column(name="periodo", type="string", length=4, nullable=true)
     */
    private $periodo;
    /**
     * @var string $estado
     *
     * @ORM\Column(name="estado", type="boolean",   nullable=true)
     */
    private $estado;
    /**
     * @var string $pintar
     *
     * @ORM\Column(name="pintar", type="boolean",   nullable=true)
     */
    private $pintar;


   
     /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=false)
     */
    private $tipo;
    
    /**
     * @param  integer $value
     * @return boolean
     */
    public function reverseTransform($value)
    {
        return $value === true ? 1 : 0;
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
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

  /**
     * Set descripcion
     *
     * @param  string $descripcion
     * @return vpindicador
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
     * @return vpindicador
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get estado
     *
     * @return boolean
     */
    public function getEstado()
    {
        return $this->estado;
    }    
        /**
     * Set estado
     *
     * @param  string $estado
     * @return vpindicador
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }
    /**
     * Get pintar
     *
     * @return boolean
     */
    public function getPintar()
    {
        return $this->pintar;
    }    
        /**
     * Set pintar
     *
     * @param  boolean $pintar
     * @return vpindicador
     */
    public function setPintar($pintar)
    {
        $this->pintar = $pintar;

        return $this;
    }  







     /**
     * Get estado
     *
     * @return boolean
     */
    public function getFalse()
    {
        return $this->estado;
    }  
       /**
     * Set estado
     *
     * @param  string $estado
     * @return vpindicador
     */
    public function setFalse($estado)
    {
        $this->estado = $estado;

        return $this;
    }
    
    
  /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }    
        /**
     * Set tipo
     *
     * @param  string $tipo
     * @return vpindicador
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }   
    
    
      /**
     * Get idindicador
     *
     * @return integer
     */
    public function getIdindicador()
    {
        return $this->idindicador;
    }

  /**
     * Set idindicador
     *
     * @param  integer $idindicador
     * @return vpindicador
     */
    public function setIdindicador($idindicador)
    {
        $this->idindicador = $idindicador;

        return $this;
    }
    
    
    public function __toString()
    {
        return $this->tipo ? :'';
    }

   
}