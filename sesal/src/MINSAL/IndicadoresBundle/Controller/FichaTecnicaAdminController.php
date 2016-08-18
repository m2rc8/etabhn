<?php

namespace MINSAL\IndicadoresBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\Console\Input\ArrayInput;

class FichaTecnicaAdminController extends Controller
{

public function bloqueo($em, $IdEstablecimiento, $periodo, $m) {
        //$objID1->setIdEstablecimiento($estP);
        //$objID1->setIdVariablePeriodo($campo['id']);
        //$objID1->setPeriodo($periodo);
        //$objID1->setMes($m);
        //$em = $this->getDoctrine()->getManager();//
        $sql = 'SELECT vp.idIndicadorb
              FROM IndicadoresBundle:IndicadorB vp 
              WHERE  vp.idEstablecimiento = :pidEstablecimiento AND vp.periodo= :pperiodo
              AND vp.mes = :pmes';

        //$sql="SELECT vp.id  FROM IndicadoresBundle:VariablehPeriodo vp ";
        //$sql="SELECT id.m1 as Ene FROM IndicadoresBundle:IndicadorhDetalle id";
        $query = $em->createQuery($sql);
        $query->setParameter('pperiodo', $periodo);
        $query->setParameter('pidEstablecimiento', $IdEstablecimiento);
        $query->setParameter('pmes', $m);
        //$res= $query->getResult();
        $Establecimiento = $query->getResult();
        if (!$Establecimiento) {
            return 'false';
        } else {
            return 'true';
        }
    }






    public function editAction($id = null)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('IndicadoresBundle:FichaTecnica');
        $this->admin->setRepository($repo);

        return parent::editAction($id);
    }

    public function createAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('IndicadoresBundle:FichaTecnica');
        $this->admin->setRepository($repo);

        return parent::createAction();
    }

    public function tableroAction()
    {
		$user = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $clasificacionUso = $em->getRepository("IndicadoresBundle:ClasificacionUso")->findAll();

        //Luego agregar un método para obtener la clasificacion de uso por defecto del usuario
        $usuario = $this->getUser();
        if ($usuario->getClasificacionUso()) {
            $clasificacionUsoPorDefecto = $usuario->getClasificacionUso();
        } 
		else 
		{
            $clasificacionUsoPorDefecto = $clasificacionUso[0];
        }
        $categorias = $em->getRepository("IndicadoresBundle:ClasificacionTecnica")->findBy(array('clasificacionUso' => $clasificacionUsoPorDefecto));

        //Salas por usuario
        $usuarioSalas = array();
        if (($usuario->hasRole('ROLE_SUPER_ADMIN')))
		{
            foreach ($em->createQuery('SELECT g FROM IndicadoresBundle:GrupoIndicadores g ORDER BY g.nombre ASC')->getResult() as $sala) 	
			{
                $usuarioSalas[$sala->getId()] = $sala;
            } 
        }
		else
		{
           foreach ($usuario->getGruposIndicadores() as $sala) 
		   {
                $usuarioSalas[$sala->getGrupoIndicadores()->getId()] = $sala->getGrupoIndicadores();
           } 
        }
		
		$salasXusuario=array();
		$i=0;
		foreach ($usuarioSalas as $sala) {
			$esduenio=$em->createQuery("SELECT u.esDuenio FROM IndicadoresBundle:UsuarioGrupoIndicadores u WHERE u.usuario='".$user->getId()."' and u.grupoIndicadores='".$sala->getId()."'")->getResult();
			if($esduenio[0]["esDuenio"])
			{
            $salasXusuario[$i]['datos_sala'] = $sala;
            $salasXusuario[$i]['indicadores_sala'] = $em->getRepository('IndicadoresBundle:GrupoIndicadores')
                    ->getIndicadoresSala($sala);
            $i++;
			}
        }
		
        //Salas asignadas al grupo al que pertenece el usuario
		$salasXgrupoTemp=array();
        foreach ($usuario->getGroups() as $grp)
		{
            foreach ($grp->getSalas() as $sala)
			{
                $usuarioSalas[$sala->getId()] = $sala;
				$salasXgrupoTemp[]=$sala;
            }
        }
        $salasXgrupo=array();
		$i=0;
		
		$uXg=$em->getRepository('IndicadoresBundle:GrupoIndicadores')->getSalaGrupo($user->getId());
		foreach ($uXg as $sala) 
		{
			$id=$sala["grupoindicadores_id"];
			$sala=$em->createQuery("SELECT g FROM IndicadoresBundle:GrupoIndicadores g WHERE g='$id' ORDER BY g.nombre ASC")->getResult();
			$salasXgrupo[$i]['datos_sala'] = $sala[0];
			$salasXgrupo[$i]['indicadores_sala'] = $em->getRepository('IndicadoresBundle:GrupoIndicadores')
					->getIndicadoresSala($sala[0]);
			$i++;
		}
		
        $i = 0;
        $salas = array();
        foreach ($usuarioSalas as $sala) {
            $salas[$i]['datos_sala'] = $sala;
            $salas[$i]['indicadores_sala'] = $em->getRepository('IndicadoresBundle:GrupoIndicadores')
                    ->getIndicadoresSala($sala);
            $i++;
        }

        //Indicadores asignados por usuario
        $usuarioIndicadores = ($usuario->hasRole('ROLE_SUPER_ADMIN')) ?
                //$em->getRepository("IndicadoresBundle:FichaTecnica")->findAll() :
                $this->get('doctrine')->getManager()->createQuery('SELECT c FROM IndicadoresBundle:FichaTecnica c ORDER BY c.id,c.nombre ASC')->getResult() :
                $usuario->getIndicadores();
        //Indicadores asignadas al grupo al que pertenece el usuario
        $indicadoresPorGrupo = array();
        foreach ($usuario->getGroups() as $grp){            
            foreach ($grp->getIndicadores() as $indicadores_grupo){
                $indicadoresPorGrupo[] = $indicadores_grupo;
            }
        }
        
        $indicadores_por_usuario = array();
        $indicadores_clasificados = array();
        foreach ($usuarioIndicadores as $ind) {
            $indicadores_por_usuario[] = $ind->getId();
        }
        
        foreach ($indicadoresPorGrupo as $ind){
            $indicadores_por_usuario[] = $ind->getId();
        }
        
        $categorias_indicador = array();
        foreach ($categorias as $cat) {
            $categorias_indicador[$cat->getId()]['cat'] = $cat;
            $categorias_indicador[$cat->getId()]['indicadores'] = array();
            $indicadores_por_categoria = $cat->getIndicadores();
            foreach ($indicadores_por_categoria as $ind) {
                if (in_array($ind->getId(), $indicadores_por_usuario)) {
                    $categorias_indicador[$cat->getId()]['indicadores'][] = $ind;
                    $indicadores_clasificados[] = $ind->getId();
                }
            }
        }

        $indicadores_no_clasificados = array();
        foreach ($usuarioIndicadores as $ind) {
            if (!in_array($ind->getId(), $indicadores_clasificados)) {
                $indicadores_no_clasificados[] = $ind;
            }
        }
        foreach ($indicadoresPorGrupo as $ind) {
            if (!in_array($ind->getId(), $indicadores_clasificados)) {
                $indicadores_no_clasificados[] = $ind;
            }
        }
		
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:tablero.html.twig', array(
                    'categorias' => $categorias_indicador,
                    'clasificacionUso' => $clasificacionUso,
                    'salas' => $salas,
					'salasXusuario' => $salasXusuario,
					'salasXgrupo' => $salasXgrupo,
                    'indicadores_no_clasificados' => $indicadores_no_clasificados
        ));
    }

    public function CubosAction()
    {
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:cubos.html.twig', array());
    }
    
    /*
    Mostrar Reporte Gerenciales generados por Pentaho
    */
    public function reporteAction() {

        $id = $this->getRequest()->get('id');
        $reporte= "http://etab.salud.gob.sv:8080/pentaho/content/reporting/reportviewer/report.html?solution=reportes&path=&name=indicador".$id.".prpt";
        return new RedirectResponse($reporte);
        
        } 


    public function batchActionVerFicha($idx = null)
    {
        $parameterBag = $this->get('request')->request;
        $em = $this->getDoctrine()->getManager();

        $selecciones = $parameterBag->get('idx');

        $salida = '';
        foreach ($selecciones as $ficha) {
            $fichaTec = $em->find('IndicadoresBundle:FichaTecnica', $ficha);

            $admin = $this->get('sonata.admin.ficha');
            $admin->setSubject($fichaTec);

            $html = $this->render($admin->getTemplate('show'), array(
                'action' => 'show',
                'object' => $fichaTec,
                'elements' => $admin->getShow(),
                'admin' => $admin,
                'base_template' => 'IndicadoresBundle::ajax_layout.html.twig'
            ));

            $salida .= $html->getContent() . '<BR /><BR />';
        }
        //Quitar los comentarios del código html, enlaces y aplicar estilos
        $salida = preg_replace('/<!--(.|\s)*?-->/', '', $salida);
        $salida = preg_replace('/<a(.|\s)*?>/', '', $salida);
        $salida = str_ireplace('</a>', '', $salida);
        $salida = str_ireplace('TD',"TD STYLE='border: 2px double black'", $salida);
        $salida = str_ireplace('TH',"TH STYLE='border: 2px double black'", $salida);
        $salida = str_ireplace('<TABLE',"<TABLE width=95% ", $salida);

        return new Response('<HTML>'.$salida.'</HTML>', 200, array(
            'Content-Type' => 'application/msword',
            'Content-Disposition' => 'attachment; filename="ficha_tecnica.doc"',
            'Pragma' => 'no-cache',
            'Expires' => '0'
            )
        );
    }	
}
