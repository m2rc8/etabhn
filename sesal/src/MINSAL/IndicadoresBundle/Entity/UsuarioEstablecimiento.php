<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 *MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento
 *
 * @ORM\Table(name="usuario_establecimiento")
 * @ORM\Entity
 */
class UsuarioEstablecimiento
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
     *
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="gruposEstablecimientos")
     * @ORM\JoinColumn(name="id_establecimiento", referencedColumnName="id_establecimiento")
     */
    
    
      /**
     * @var integer $idEstablecimiento
     *
     * @ORM\Column(name="id_establecimiento", type="integer", nullable=false)
     */
    
   
    
    
    
    
    private $idEstablecimiento;  
    
     /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="gruposIndicadores")
     * @ORM\JoinColumn(name="id_usuario", referencedColumnName="id")
     */
    
   /**
     * @var integer $idUsuario
     *
     * @ORM\Column(name="id_usuario", type="integer", nullable=false)
     */
    private $idUsuario;
   
    
    
    
    
    
	
    

     /**
     * Set idEstablecimiento
     *
     * @param  $idEstablecimiento
     * @return UsuarioEstablecimiento
     */
    public function setIdEstablecimiento( $idEstablecimiento)//\MINSAL\IndicadoresBundle\Entity\Establecimiento
    {
        $this->idEstablecimiento = $idEstablecimiento;
    
        return $this;
    }
    /**
     * Get idEstablecimiento
     *
     * @return idEstablecimiento
     */
    public function getIdEstablecimiento()
    {
        return $this->idEstablecimiento;
    }
        /**
     * Set idUsuario
     *
     * @param  $usuario
     * @return $usuario
     */
    public function setIdUsuario($usuario)//\MINSAL\IndicadoresBundle\Entity\User 
    {
        $this->idUsuario = $usuario;

        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \MINSAL\IndicadoresBundle\Entity\User
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
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
    public function __toString()
    {
        return $this->id ? :'';
    }

   
}