<?php

namespace MINSAL\IndicadoresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use MINSAL\IndicadoresBundle\Validator as CustomAssert;

/**
 * MINSAL\IndicadoresBundle\Entity\Establecimiento
 *
 * @ORM\Table(name="establecimiento")
 * @ORM\Entity
 */
class Establecimiento
{
        /**
     * Constructor
     */
    public function __construct()
    {
        $this->gruposEstablecimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->establecimiento		 = new \Doctrine\Common\Collections\ArrayCollection();
        parent::__construct();
    }
    /**
     * @var integer $idEstablecimiento
     *
     * @ORM\Column(name="id_establecimiento", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEstablecimiento;

    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="UsuarioEstablecimiento", mappedBy="idEstablecimiento", cascade={"all"}, orphanRemoval=true)
     **/
    protected $gruposEstablecimientos;
    

    /**
     * @var string $descripcion
     *
     * @ORM\Column(name="descripcion", type="string", length=1024, nullable=false)
     * @CustomAssert\OnlyAlphanumeric(message="OnlyAlphanumeric.Message")
     */
    private $descripcion;

	/**
     * @ORM\ManyToOne(targetEntity="Municipio")
     * @ORM\JoinColumn(name="id_municipio", referencedColumnName="id")
     * @return integer
     */
    
    private $idMunicipio;    
    
    /**
     * Set idMunicipio
     *
     * @param integer $idMunicipio
     * @return Establecimiento
     */
    public function setIdMunicipio($idMunicipio)
    {
        $this->idMunicipio = $idMunicipio;
    
        return $this;
    }

    /**
     * Get idMunicipio
     *
     * @return integer 
     */
    public function getIdMunicipio()
    {
        return $this->idMunicipio;
    }
    /**
     * Get id
     *
     * @return integer
     */
    public function getIdEstablecimiento()
    {
        return $this->idEstablecimiento;
    }

    /**
     * Set descripcion
     *
     * @param  string $descripcion
     * @return Establecimiento
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

    public function __toString()
    {
        return $this->descripcion ? :'';
    }
    
    
/**
     * Add gruposEstablecimientos
     *
     * @param  \MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento $gruposEstablecimientos
     * @return User
     */
    public function addGruposEstablecimientos(\MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento $gruposEstablecimientos)
    {
        $this->gruposEstablecimientos[] = $gruposEstablecimientos;

        return $this;
    }

    /**
     * Remove gruposEstablecimientos
     *
     * @param \MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento $gruposEstablecimientos
     */
    public function removeGruposEstablecimientos(\MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento $gruposEstablecimientos)
    {
        $this->gruposEstablecimientos->removeElement($gruposEstablecimientos);
    }

    /**
     * Get $gruposEstablecimientos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGruposEstablecimientos()
    {
        return $this->gruposEstablecimientos;
    }

   
}