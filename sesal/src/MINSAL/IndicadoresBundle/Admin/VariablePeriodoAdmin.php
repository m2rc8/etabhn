<?php

namespace MINSAL\IndicadoresBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use MINSAL\IndicadoresBundle\Entity\VariablePeriodo;

class VariablePeriodoAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1, // Display the first page (default = 1)
        '_sort_order' => 'ASC', // Descendant ordering (default = 'ASC')
        '_sort_by' => 'id' // name of the ordered field (default = the model id field, if any)
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $s=0;
        //$esFusionado = $this->getSubject()->getEsFusionado();

        $formMapper
                ->with($this->getTranslator()->trans('datos_generales'), array('collapsed' => false))
                //->add('id', 'hidden', array('label' => $this->getTranslator()->trans('Id')))
                ->add('descripcion', null, array('label' => $this->getTranslator()->trans('descripcion'), 'required' => true))
                ->add('periodo', null, array('label' => $this->getTranslator()->trans('periodo'), 'required' => true))
                //->add('periodicidad', null, array('label' => 'Periodicidad de actualización'))                
                //->add('ventana', null, array('attr' => array('label' => 'Ventana de actualización', 'min'=>'0')))
		//		->add('fechaCorte', null, array('attr' => array('label' => 'Dia de Corte', 'min'=>'1', 'max'=>'30')))
		//		->add('actualizacionIncremental', null, array('attr' => array('label' => '¿Es actualización incremental?')))				
                ->end()
->with('Periodos Habiles')
                    ->add('anios', null, array('label' => 'Periodos', 'required' => false, 'expanded' => true))
                ->end()
                ->with('Periodos a Pintar de rosa (Clinica Materno Infantil)')
                    ->add('pintars', null, array('label' => 'Pintar', 'required' => false, 'expanded' => true))
                ->end()
        ;
        if ($esFusionado == false)
            $formMapper
                    ->with($this->getTranslator()->trans('datos_generales'), array('collapsed' => false))
                    //->add('esCatalogo', null, array('label' => $this->getTranslator()->trans('es_catalogo')))
                    ->end()
                    //->with($this->getTranslator()->trans('origen_datos_sql'), array('collapsed' => true))
                    //->add('conexiones', null, array('label' => $this->getTranslator()->trans('nombre_conexion'), 'required' => false, 'expanded' => true))
                    /*->add('sentenciaSql', null, array('label' => $this->getTranslator()->trans('sentencia_sql'),
                        'required' => false,
                        'attr' => array('rows' => 7, 'cols' => 50)
                    ))*/
                    //->end()
                    //->with($this->getTranslator()->trans('origen_datos_archivo'), array('collapsed' => true))
                    //->add('archivoNombre', null, array('label' => $this->getTranslator()->trans('archivo_asociado'), 'required' => false, 'read_only' => true))
                    //->add('file', 'file', array('label' => $this->getTranslator()->trans('subir_nuevo_archivo'), 'required' => false))
                    //->end()
            ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $test=0;
        $datagridMapper
                ->add('id', null, array('label' => $this->getTranslator()->trans('id')))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $test=0;
        $listMapper
                ->addIdentifier('id', null, array('label' => $this->getTranslator()->trans('id')))
                ->add('descripcion', null, array('label' => $this->getTranslator()->trans('descripcion')))
                ->add('periodo', null, array('label' => $this->getTranslator()->trans('periodo')))
                //->add('esFusionado', null, array('label' => $this->getTranslator()->trans('fusion.es_fusionado')))
                //->add('esCatalogo', null, array('label' => $this->getTranslator()->trans('es_catalogo')))
                /*->add('sentenciaSql', null, array('label' => $this->getTranslator()->trans('sentencia_sql'),
                    'template'=>'IndicadoresBundle:CRUD:list_sentencia_sql.html.twig'))*/
                //->add('archivoNombre', null, array('label' => $this->getTranslator()->trans('archivo_asociado2')))
				
		//		->add('ultima_lectura', 'string', array('label' => $this->getTranslator()->trans('_ultima_actualizacion_')))				       			          				
                //->add('_action', 'actions', array(
                //    'actions' => array(
                //        'load_data' => array('template' => 'IndicadoresBundle:OrigenDatosAdmin:list__action_load_data.html.twig')
                //    )
                //))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        
        
        $piecesURL = explode("/", $_SERVER['REQUEST_URI']);
        $pieceAction = $piecesURL[count($piecesURL) - 1]; // create or update
        $pieceId = $piecesURL[count($piecesURL) - 2]; // id/edit
         
        $obj = new \MINSAL\IndicadoresBundle\Entity\VariablePeriodo;
         
        $rowsRD = $this->getModelManager()->findBy('IndicadoresBundle:VariablePeriodo',
        		array('id' => $object->getId()));
        
        if (strpos($pieceAction,'create') !== false) // entra cuando es ALTA
        {
        	if (count($rowsRD) > 0){
        		$errorElement
        		->with('codigo')
        		->addViolation($this->getTranslator()->trans('registro existente, no se puede duplicar'))
        		->end();
        	}
        }
        else // entra cuando es EDICION
        {
        	if (count($rowsRD) > 0){
        		$obj = $rowsRD[0];
        		if ($obj->getId() != $pieceId)
        		{
        			$errorElement
        			->with('codigo')
        			->addViolation($this->getTranslator()->trans('registro existente, no se puede duplicar'))
        			->end();
        		}
        	}
        }
    }

    public function getBatchActions()
    {
        //$actions = parent::getBatchActions();
        $actions = array();

        //$actions['load_data'] = array(
        //    'label' => $this->trans('action_load_data'),
        //    'ask_confirmation' => false // If true, a confirmation will be asked before performing the action
        //);
		/*$actions['ultima_lectura'] = array(
            'label' => $this->trans('_ultima_actualizacion_'),
            'ask_confirmation' => false // If true, a confirmation will be asked before performing the action
        );
        $actions['merge'] = array(
            'label' => $this->trans('action_merge'),
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        );*/
        $actions['crear_pivote'] = array(
            'label' => $this->trans('crear_pivote'),
            'ask_confirmation' => true // If true, a confirmation will be asked before performing the action
        );

        return $actions;
    }

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'IndicadoresBundle:CRUD:variable_periodo-edit.html.twig';
                break;
		case 'list':
                return 'IndicadoresBundle:CRUD:variable_periodo-list.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function prePersist($origenDato)
    {
        $valor=$origenDato;
        //$this->saveFile($origenDato);
        //$this->setNombreCatalogo($origenDato);

        //$this->guardarDrescripcion($origenDato);
    }

    public function preUpdate($origenDato)
    {
        $valor=$origenDato;
        //$this->saveFile($origenDato);
        //$this->guardarDrescripcion($origenDato);
        //$this->setNombreCatalogo($origenDato);
    }







    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('merge_save', 'merge/save');
        $collection->add('load_data');
    }
}
