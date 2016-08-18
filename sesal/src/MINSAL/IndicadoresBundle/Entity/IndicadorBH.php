<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\IndicadorBH
 *
 * @ORM\Table(name="indicadorbh")
 * @ORM\Entity
 */
class IndicadorBH
{
    /**
     * @var integer $idIndicadorbh
     *
     * @ORM\Column(name="id_indicadorbh", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idIndicadorbh;


    /**
     * @var integer $idEstablecimiento
     *
     * @ORM\Column(name="id_establecimiento", type="integer", nullable=false)
     */
    private $idEstablecimiento;
    
    /**
     * @var integer $idVariablePeriodo
     *
     * @ORM\Column(name="id_variable_periodo", type="integer", nullable=false)
     */
    private $idVariablePeriodo;
    
    
    /**
     * @var string $periodo
     *
     * @ORM\Column(name="periodo", type="string", length=4, nullable=false)
     */
    private $periodo;

    
    /**
     * @var string $mes
     *
     * @ORM\Column(name="mes", type="string", nullable=true)
     */
    private $mes;
   
    
    
    
    
/**
     * Get idindicadorbh
     *
     * @return integer
     */
    public function getIdIndicadorbh()
    {
        return $this->idIndicadorbh;
    }


    /**
     * Set idEstablecimiento
     *
     * @param  integer $idEstablecimiento
     * @return indicadorBH
     */
    public function setIdEstablecimiento($idEstablecimiento)
    {
        $this->idEstablecimiento = $idEstablecimiento;

        return $this;
    }
    /**
     * Get idEstablecimiento
     *
     * @return string
     */
    public function getIdEstablecimiento()
    {
        return $this->idEstablecimiento;
    }
    
   
    
    
    /**
     * Set idVariablePeriodo
     *
     * @param  integer $idVariablePeriodo
     * @return indicadorBH
     */
    public function setIdVariablePeriodo($idVariablePeriodo)
    {
        $this->idVariablePeriodo = $idVariablePeriodo;

        return $this;
    }
    /**
     * Get idVariablePeriodo
     *
     * @return string
     */
    public function getIdVariablePeriodo()
    {
        return $this->idVariablePeriodo;
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
     * Get periodo
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    } 
/**
     * Set mes
     *
     * @param  string $mes
     * @return variablePeriodo
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * Get mes
     *
     * @return integer
     */
    public function getMes()
    {
        return $this->mes;
    }   

    public function __toString()
    {
        return $this->descripcion ? :'';
    }

   
}