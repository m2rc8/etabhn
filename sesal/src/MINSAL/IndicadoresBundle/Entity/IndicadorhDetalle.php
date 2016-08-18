<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\IndicadorhDetalle
 *
 * @ORM\Table(name="indicadorh_detalle")
 * @ORM\Entity
 */
class IndicadorhDetalle
{
    /**
     * @var integer $idIndicadorDetalle
     *
     * @ORM\Column(name="id_indicador_detalle", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idIndicadorDetalle;


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
     * @var integer $m1
     *
     * @ORM\Column(name="m1", type="integer", nullable=true)
     */
    private $m1;
    /**
     * @var integer $m2
     *
     * @ORM\Column(name="m2", type="integer", nullable=true)
     */
    private $m2;
    /**
     * @var integer $m3
     *
     * @ORM\Column(name="m3", type="integer", nullable=true)
     */
    private $m3;
    /**
     * @var integer $m4
     *
     * @ORM\Column(name="m4", type="integer", nullable=true)
     */
    private $m4;
      /**
     * @var integer $m5
     *
     * @ORM\Column(name="m5", type="integer", nullable=true)
     */
    private $m5;
    /**
     * @var integer $m6
     *
     * @ORM\Column(name="m6", type="integer", nullable=true)
     */
    private $m6;
    /**
     * @var integer $m7
     *
     * @ORM\Column(name="m7", type="integer", nullable=true)
     */
    private $m7; 
    /**
     * @var integer $m8
     *
     * @ORM\Column(name="m8", type="integer", nullable=true)
     */
    private $m8;    
    /**
     * @var integer $m9
     *
     * @ORM\Column(name="m9", type="integer", nullable=true)
     */
    private $m9;
    /**
     * @var integer $m10
     *
     * @ORM\Column(name="m10", type="integer", nullable=true)
     */
    private $m10;    
    
    
    /**
     * @var integer $m11
     *
     * @ORM\Column(name="m11", type="integer", nullable=true)
     */
    private $m11;
    
    /**
     * @var integer $m12
     *
     * @ORM\Column(name="m12", type="integer", nullable=true)
     */
    private $m12;
    
    
    
/**
     * Get idindicadorDetalle
     *
     * @return integer
     */
    public function getIdIndicadorDetalle()
    {
        return $this->idIndicadorDetalle;
    }


    /**
     * Set idEstablecimiento
     *
     * @param  integer $idEstablecimiento
     * @return indicadorhDetalle
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
     * @return indicadorhDetalle
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
     * Set m1
     *
     * @param  integer $m1
     * @return variablePeriodo
     */
    public function setM1($m1)
    {
        $this->m1 = $m1;

        return $this;
    }

    /**
     * Get m1
     *
     * @return integer
     */
    public function getM1()
    {
        return $this->m1;
    }   
    
    
/**
     * Set m2
     *
     * @param  integer $m2
     * @return variablePeriodo
     */
    public function setM2($m2)
    {
        $this->m2 = $m2;

        return $this;
    }

    /**
     * Get m2
     *
     * @return integer
     */
    public function getM2()
    {
        return $this->m2;
    }   
    /**
     * Set m3
     *
     * @param  integer $m3
     * @return variablePeriodo
     */
    public function setM3($m3)
    {
        $this->m3 = $m3;

        return $this;
    }

    /**
     * Get m3
     *
     * @return integer
     */
    public function getM3()
    {
        return $this->m3;
    }   
    
    /**
     * Set m4
     *
     * @param  integer $m4
     * @return variablePeriodo
     */
    public function setM4($m4)
    {
        $this->m4 = $m4;

        return $this;
    }

    /**
     * Get m4
     *
     * @return integer
     */
    public function getM4()
    {
        return $this->m4;
    }       
    
    /**
     * Set m5
     *
     * @param  integer $m5
     * @return variablePeriodo
     */
    public function setM5($m5)
    {
        $this->m5 = $m5;

        return $this;
    }

    /**
     * Get m5
     *
     * @return integer
     */
    public function getM5()
    {
        return $this->m5;
    }   
    
    
    /**
     * Set m6
     *
     * @param  integer $m6
     * @return variablePeriodo
     */
    public function setM6($m6)
    {
        $this->m6 = $m6;

        return $this;
    }

    /**
     * Get m6
     *
     * @return integer
     */
    public function getM6()
    {
        return $this->m6;
    }   
    
        /**
     * Set m7
     *
     * @param  integer $m7
     * @return variablePeriodo
     */
    public function setM7($m7)
    {
        $this->m7 = $m7;

        return $this;
    }

    /**
     * Get m7
     *
     * @return integer
     */
    public function getM7()
    {
        return $this->m7;
    }   
    /**
     * Set m8
     *
     * @param  integer $m8
     * @return variablePeriodo
     */
    public function setM8($m8)
    {
        $this->m8 = $m8;

        return $this;
    }

    /**
     * Get m8
     *
     * @return integer
     */
    public function getM8()
    {
        return $this->m8;
    }   
    
     /**
     * Set m9
     *
     * @param  integer $m9
     * @return variablePeriodo
     */
    public function setM9($m9)
    {
        $this->m9 = $m9;

        return $this;
    }

    /**
     * Get m9
     *
     * @return integer
     */
    public function getM9()
    {
        return $this->m9;
    }      
    
    
    /**
     * Set m10
     *
     * @param  integer $m10
     * @return variablePeriodo
     */
    public function setM10($m10)
    {
        $this->m10 = $m10;

        return $this;
    }

    /**
     * Get m10
     *
     * @return integer
     */
    public function getM10()
    {
        return $this->m10;
    }   
    
    
    
    
    /**
     * Set m11
     *
     * @param  integer $m11
     * @return variablePeriodo
     */
    public function setM11($m11)
    {
        $this->m11 = $m11;

        return $this;
    }

    /**
     * Get m11
     *
     * @return integer
     */
    public function getM11()
    {
        return $this->m11;
    }     
    
    
    /**
     * Set m12
     *
     * @param  integer $m12
     * @return variablePeriodo
     */
    public function setM12($m12)
    {
        $this->m12 = $m12;

        return $this;
    }

    /**
     * Get m12
     *
     * @return integer
     */
    public function getM12()
    {
        return $this->m12;
    }   
    
    
    public function __toString()
    {
        return $this->descripcion ? :'';
    }

   
}