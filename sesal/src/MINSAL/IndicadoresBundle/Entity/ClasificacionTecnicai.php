<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\ClasificacionTecnicai
 *
 * @ORM\Table(name="clasificacion_tecnicai")
 * @ORM\Entity
 */
class ClasificacionTecnicai
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
     * @var string $codigo
     *
     * @ORM\Column(name="codigo", type="string", length=50, nullable=false)
     * @CustomAssert\OnlyAlphanumeric(message="OnlyAlphanumeric.Message")
     */
    private $codigo;

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=150, nullable=false)
     */
    private $descripcion;

    /**
     * @var string $comentario
     *
     * @ORM\Column(name="comentario", type="text", nullable=true)
     * @CustomAssert\AlphanumericPlus(message="AlphanumericPlus.Message")
     */
    private $comentario;


    /**
     * @var string $meta
     *
     * @ORM\Column(name="meta", type="float", scale=2, nullable=true)
     */
    private $meta;
   
    /**
     * @var string $tipo
     *
     * @ORM\Column(name="tipo", type="text", nullable=true)
     */
    private $tipo;

    /**
     *
     * @var ficha
     *
     * @ORM\ManyToOne(targetEntity="FichaTecnica")
     * @ORM\OrderBy({"nombre" = "ASC"})
     **/
    private $ficha;
   
  /**
     *
     * @var clasificacionUso
     *
     * @ORM\ManyToOne(targetEntity="ClasificacionTecnicam")
     * @ORM\OrderBy({"codigo" = "ASC"})
     **/
    private $clasificacionTecnicam;
        /**
     * Set tipo
     *
     * @param  string       $tipo
     * @return ClasificacionTecnicai
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

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
     * Set meta
     *
     * @param  string       $meta
     * @return ClasificacionTecnicai
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get formula
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
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
     * @param  string               $descripcion
     * @return ClasificacionTecnicai
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

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

    /**
     * Set comentario
     *
     * @param  string               $comentario
     * @return ClasificacionTecnicai
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    public function __toString()
    {
        if ($this->clasificaciontecnicam)
            return $this->clasificaciontecnicam->getDescripcion().' -- '.$this->descripcion;
        else
            return ' -- '.$this->descripcion;
    }

    /**
     * Set codigo
     *
     * @param  string               $codigo
     * @return ClasificacionTecnicai
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set id
     *
     * @param  integer              $id
     * @return ClasificacionTecnicai
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set clasificaciontecnicam
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\ClasificacionTecnicam $clasificacionTecnicam
     * @return ClasificacionTecnicai
     */
    public function setClasificacionTecnicam(\MINSAL\IndicadoresBundle\Entity\ClasificacionTecnicam $clasificacionTecnicam = null)
    {
        $this->clasificacionTecnicam = $clasificacionTecnicam;

        return $this;
    }

    /**
     * Get clasificacionTecnicam
     *
     * @return \MINSAL\IndicadoresBundle\Entity\clasificacionTecnicam
     */
    public function getClasificacionTecnicam()
    {
        return $this->clasificacionTecnicam;
    }
   
   
   
     /**
     * Set ficha
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\ClasificacionTecnicam $ficha
     * @return ClasificacionTecnicai
     */
    public function setFicha(\MINSAL\IndicadoresBundle\Entity\FichaTecnica $ficha = null)
    {
        $this->ficha = $ficha;

        return $this;
    }

    /**
     * Get ficha
     *
     * @return \MINSAL\IndicadoresBundle\Entity\ficha
     */
    public function getFicha()
    {
        return $this->ficha;
    }
   
   
   
   
}