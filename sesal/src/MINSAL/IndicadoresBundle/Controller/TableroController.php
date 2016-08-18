<?php

namespace MINSAL\IndicadoresBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use MINSAL\IndicadoresBundle\Entity\IndicadorDetalle;
use MINSAL\IndicadoresBundle\Entity\IndicadorhDetalle;
use MINSAL\IndicadoresBundle\Entity\VariablehPeriodo;
use MINSAL\IndicadoresBundle\Entity\UsuarioEstablecimiento;
use MINSAL\IndicadoresBundle\Entity\IndicadorB;
use MINSAL\IndicadoresBundle\Entity\IndicadorBH;
use MINSAL\IndicadoresBundle\Entity\fichaDetalle;
use Doctrine\ORM\Query;
class TableroController extends Controller
{

    public function removeMatrizGestor($gestor, $periodo, $em) {
        $sql = 'delete from IndicadoresBundle:fichaDetalle id where id.gestor =:gestor and id.periodo =:periodo';
        $query = $em->createQuery($sql);
        $query->setParameter('gestor', $gestor);
        $query->setParameter('periodo', $periodo);
        $numDel = $query->execute();
        return $numDel;
    }

public function matrizdosAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:matrizdos.html.twig', $array);
    }


 public function color($valor) {
        $color = 'blanco';
        if ($valor < 70 &&  $valor > 0) {
           $color = 'rojo';
        } elseif ($valor > 69 && $valor < 80) {
             $color = 'naranja';
        } elseif ($valor > 79 && $valor < 1000) {
             $color = 'verde';
        }
        return $color;
    }

public function fornpfichaAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[0];
        $est = $parametros[1];
        $gestor = $est;
        $em = $this->getDoctrine()->getManager(); //


        $sql = "SELECT vp.id, vp.descripcion, id.m1 as ene, id.m2 as feb, id.m3 as mar, id.m4 as abr, id.m5 as may, id.m6 as jun,
            id.m7 as jul, id.m8 as ago, id.m9 as sep, id.m10 as oct, id.m11 as nov, id.m12 as dic
,
 CASE WHEN  (select variableperiodo_id
             from variable_pintar
             where vp.id = variableperiodo_id and pintar_id = an.id) IS NULL THEN false else true end as pintar
FROM variable_periodo vp 
              LEFT JOIN anio an ON (an.nombre='" . $periodo . "')
              JOIN variable_anio vpi ON (vpi.anio_id = an.id and  vpi.variableperiodo_id = vp.id )
              LEFT JOIN  indicador_detalle id ON (id.periodo='" . $periodo . "' and id.id_variable_periodo = vp.id and id.id_establecimiento=  " . $est . ") ";

// QUERY PARA OBTENER EL NIVEL 1
        $sql = "select distinct ct.descripcion, ct.id
                from fichatecnica_clasificaciontecnica fc
                left join   clasificacion_tecnica ct on (fc.clasificaciontecnica_id = ct.id)
                order by ct.id ";

        $sql = "select distinct descripcion, id
                from clasificacion_tecnicam
                order by id";



        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        //$Establecimiento = $stmt->fetchAll();

        $indicadores = $stmt->fetchAll();
        $Establecimiento = $indicadores;
        //2
        //buscar las temp

        $indicadorFinal = array();
        $cont = 0;
        foreach ($indicadores as $vars) {
            //if ($vars['id'] == 66) {
            //    continue;
            //}
            $bandera = 0;
            //QUERY PARA OBTENER LOS SUB TIPOS NIVEL 2
            $sql = "select ct.id, ct.descripcion as clasificacion,
                  ft.nombre  as indicador, ft.meta, ft.id as idft,
                  CASE WHEN  (fd.m1) IS NULL THEN  ft.meta  else fd.m1 end as m1,
                  CASE WHEN  (fd.m2) IS NULL THEN  ft.meta  else fd.m2 end as m2,
                  CASE WHEN  (fd.m3) IS NULL THEN  ft.meta  else fd.m3 end as m3,
                  CASE WHEN  (fd.m4) IS NULL THEN  ft.meta  else fd.m4 end as m4,
                  CASE WHEN  (fd.m5) IS NULL THEN  ft.meta  else fd.m5 end as m5,
                  CASE WHEN  (fd.m6) IS NULL THEN  ft.meta  else fd.m6 end as m6,
                  CASE WHEN  (fd.m7) IS NULL THEN  ft.meta  else fd.m7 end as m7,
                  CASE WHEN  (fd.m8) IS NULL THEN  ft.meta  else fd.m8 end as m8,
                  CASE WHEN  (fd.m9) IS NULL THEN  ft.meta  else fd.m9 end as m9,
                  CASE WHEN  (fd.m10) IS NULL THEN  ft.meta  else fd.m10 end as m10,
                  CASE WHEN  (fd.m11) IS NULL THEN  ft.meta  else fd.m11 end as m11,
                  CASE WHEN  (fd.m12) IS NULL THEN  ft.meta  else fd.m12 end as m12, fd.observacion
                from fichatecnica_clasificaciontecnica fc
                left join clasificacion_tecnica ct on (ct.id = fc.clasificaciontecnica_id)
                left join ficha_tecnica ft on (ft.id = fc.fichatecnica_id)
                left join ficha_detalle fd on
                (fd.id_ficha = ft.id
                and fd.id_clas = ct.id
                and fd.tipo=0
                and fd.periodo='" . $periodo . "'
                and fd.gestor= " . $gestor . ")
                where ct.id= " . $vars['id'] . " ;";

            $sql = " select fc.id, fc.descripcion as clasificacion,
                  ft.descripcion  as indicador, ft.meta, ft.id as idft, ft.tipo, ft.ficha_id,
                  CASE WHEN  (fd.m1) IS NULL THEN  ft.meta  else fd.m1 end as m1,
                  CASE WHEN  (fd.m2) IS NULL THEN  ft.meta  else fd.m2 end as m2,
                  CASE WHEN  (fd.m3) IS NULL THEN  ft.meta  else fd.m3 end as m3,
                  CASE WHEN  (fd.m4) IS NULL THEN  ft.meta  else fd.m4 end as m4,
                  CASE WHEN  (fd.m5) IS NULL THEN  ft.meta  else fd.m5 end as m5,
                  CASE WHEN  (fd.m6) IS NULL THEN  ft.meta  else fd.m6 end as m6,
                  CASE WHEN  (fd.m7) IS NULL THEN  ft.meta  else fd.m7 end as m7,
                  CASE WHEN  (fd.m8) IS NULL THEN  ft.meta  else fd.m8 end as m8,
                  CASE WHEN  (fd.m9) IS NULL THEN  ft.meta  else fd.m9 end as m9,
                  CASE WHEN  (fd.m10) IS NULL THEN  ft.meta  else fd.m10 end as m10,
                  CASE WHEN  (fd.m11) IS NULL THEN  ft.meta  else fd.m11 end as m11,
                  CASE WHEN  (fd.m12) IS NULL THEN  ft.meta  else fd.m12 end as m12, fd.observacion
                from  clasificacion_tecnicam fc
                left join clasificacion_tecnicai ft on (fc.id = ft.clasificaciontecnicam_id)
                left join ficha_detalle fd on
                (fd.id_ficha = ft.id
                and fd.id_clas = fc.id
                and fd.tipo=0
                and fd.periodo='" . $periodo . "'
                and fd.gestor= " . $gestor . ")
                where fc.id= " . $vars['id'] . " ;";


            $stmt2 = $em->getConnection()->prepare($sql);
            $stmt2->execute();

            $resultadoDos = $stmt2->fetchAll();

            $var2 = array('id' => $vars['id'],
                'descripcion' => substr($vars['descripcion'], 0, 150), 'tipo' => '',
                'ene' => null, 'feb' => null, 'mar' => null, 'abr' => null,
                'may' => null, 'jun' => null, 'jul' => null, 'ago' => null,
                'sep' => null, 'oct' => null, 'nov' => null, 'dic' => null);
            array_push($indicadorFinal, $var2);
            foreach ($resultadoDos as $vars2) {
                if (is_null($vars2['idft'])) {
                    continue;
                }
       if ($vars2['tipo'] == 'Planificacion') {

                   
//buscar real
                  
                   
                if (is_null($vars2['ficha_id'])){
                $vars2['ficha_id']= 'null';
                }
                    $sql = "select fd.id_ficha, fd.id_clas,
                  fd.m1 as m1,
                  fd.m2 as m2,
                  fd.m3 as m3,
                  fd.m4 as m4,
                  fd.m5 as m5,
                  fd.m6 as m6,
                  fd.m7 as m7,
                  fd.m8 as m8,
                  fd.m9 as m9,
                  fd.m10 as m10,
                  fd.m11 as m11,
                  fd.m12 as m12, observacion  
                  FROM ficha_detalle fd 
                  where fd.tipo= 1 and fd.id_clas= " . $vars['id'] .
                            " and fd.id_ficha= " . $vars2['idft'] .
                            " and fd.periodo='" . $periodo . "' and fd.gestor =" . $gestor;

                    $stmt3 = $em->getConnection()->prepare($sql);
                  try {
                       $stmt3->execute();
                   } catch (\Exception $e) {
                      continue;
                   }
                  
                    $vars3 = $stmt3->fetchAll();

                    $v3 = array('id' => $vars2['idft'],
                        'descripcion' => substr($vars2['indicador'], 0, 150), 'tipo' => 'Real',
                        'ene' => $vars3[0]['m1'], 'feb' => $vars3[0]['m2'], 'mar' => $vars3[0]['m3'], 'abr' => $vars3[0]['m4'],
                        'may' => $vars3[0]['m5'], 'jun' => $vars3[0]['m6'], 'jul' => $vars3[0]['m7'], 'ago' => $vars3[0]['m8'],
                        'sep' => $vars3[0]['m9'], 'oct' => $vars3[0]['m10'], 'nov' => $vars3[0]['m11'], 'dic' => $vars3[0]['m12'],
                        'observacion' => $vars3[0]['observacion'],
                    );
                    array_push($indicadorFinal, $v3);
                    //buscar Planificado
                    $var2 = array('id' => null,
                        'descripcion' => null, 'tipo' => 'Planificado',
                        'ene' => $vars2['m1'], 'feb' => $vars2['m2'], 'mar' => $vars2['m3'], 'abr' => $vars2['m4'],
                        'may' => $vars2['m5'], 'jun' => $vars2['m6'], 'jul' => $vars2['m7'], 'ago' => $vars2['m8'],
                        'sep' => $vars2['m9'], 'oct' => $vars2['m10'], 'nov' => $vars2['m11'], 'dic' => $vars2['m12'],
                        'observacion' => $vars2['observacion'],
                    );
                    array_push($indicadorFinal, $var2);

                    //buscar status

                    $sql = "select fd.id_ficha, fd.id_clas,
                  fd.m1 as m1,
                  fd.m2 as m2,
                  fd.m3 as m3,
                  fd.m4 as m4,
                  fd.m5 as m5,
                  fd.m6 as m6,
                  fd.m7 as m7,
                  fd.m8 as m8,
                  fd.m9 as m9,
                  fd.m10 as m10,
                  fd.m11 as m11,
                  fd.m12 as m12, observacion  
                  FROM ficha_detalle fd 
                  where fd.tipo= 2 and fd.id_clas= " . $vars['id'] .
                            " and fd.id_ficha= " . $vars2['idft'] .
                            " and fd.periodo='" . $periodo . "'" . " and fd.gestor =" . $gestor;

                    $stmt4 = $em->getConnection()->prepare($sql);
                    $stmt4->execute();
                    $vars4 = $stmt4->fetchAll();

                    //CALCULO DE COLORES, SEGUN VALOR REAL Y PLANIFICADO,
                    //EN LOS CASOS QUE SEA NULL EL STATUS
                    $estatus = 0;
                    //m1
                    if (is_null($vars4[0]['m1'])) {
                        $vars4[0]['m1'] = (intval($vars3[0]['m1']) / intval($vars2['m1'])) * 100; //status
                        $ecolor = $this->color($vars4[0]['m1']);
                    } else {
                        $vars4[0]['m1'] = (intval($vars4[0]['m1']) / intval($vars2['m1'])) * 100; //status
                        $ecolor = $this->color($vars4[0]['m1']);
                    }
                    //m2
                    if (is_null($vars4[0]['m2'])) {
                        $vars4[0]['m2'] = (intval($vars3[0]['m2']) / intval($vars2['m2'])) * 100; //status
                        $fcolor = $this->color($vars4[0]['m2']);
                    } else {
                        $vars4[0]['m2'] = (intval($vars4[0]['m2']) / intval($vars2['m2'])) * 100; //status
                        $fcolor = $this->color($vars4[0]['m2']);
                    }
                    //m3
                    if (is_null($vars4[0]['m3'])) {
                        $vars4[0]['m3'] = (intval($vars3[0]['m3']) / intval($vars2['m3'])) * 100; //status
                        $mcolor = $this->color($vars4[0]['m3']);
                    } else {
                        $vars4[0]['m3'] = (intval($vars4[0]['m3']) / intval($vars2['m3'])) * 100; //status
                        $mcolor = $this->color($vars4[0]['m3']);
                    }
                    //m4
                    if (is_null($vars4[0]['m4'])) {
                        $vars4[0]['m4'] = (intval($vars3[0]['m4']) / intval($vars2['m4'])) * 100; //status
                        $acolor = $this->color($vars4[0]['m4']);
                    } else {
                        $vars4[0]['m4'] = (intval($vars4[0]['m4']) / intval($vars2['m4'])) * 100; //status
                        $acolor = $this->color($vars4[0]['m4']);
                    }
                    //m5
                    if (is_null($vars4[0]['m5'])) {
                        $vars4[0]['m5'] = (intval($vars3[0]['m5']) / intval($vars2['m5'])) * 100; //status
                        $macolor = $this->color($vars4[0]['m5']);
                    } else {
                        $vars4[0]['m5'] = (intval($vars4[0]['m5']) / intval($vars2['m5'])) * 100; //status
                        $macolor = $this->color($vars4[0]['m5']);
                    }
                    //m6
                    if (is_null($vars4[0]['m6'])) {
                        $vars4[0]['m6'] = (intval($vars3[0]['m6']) / intval($vars2['m6'])) * 100; //status
                        $jcolor = $this->color($vars4[0]['m6']);
                    } else {
                        $vars4[0]['m6'] = (intval($vars4[0]['m6']) / intval($vars2['m6'])) * 100; //status
                        $jcolor = $this->color($vars4[0]['m6']);
                    }
                    //m7
                    if (is_null($vars4[0]['m7'])) {
                        $vars4[0]['m7'] = (intval($vars3[0]['m7']) / intval($vars2['m7'])) * 100; //status
                        $jucolor = $this->color($vars4[0]['m7']);
                    } else {
                        $vars4[0]['m7'] = (intval($vars4[0]['m7']) / intval($vars2['m7'])) * 100; //status
                        $jucolor = $this->color($vars4[0]['m7']);
                    }
                    //m8
                    if (is_null($vars4[0]['m8'])) {
                        $vars4[0]['m8'] = (intval($vars3[0]['m8']) / intval($vars2['m8'])) * 100; //status
                        $agcolor = $this->color($vars4[0]['m8']);
                    } else {
                        $vars4[0]['m8'] = (intval($vars4[0]['m8']) / intval($vars2['m8'])) * 100; //status
                        $agcolor = $this->color($vars4[0]['m8']);
                    }
                    //m9
                    if (is_null($vars4[0]['m9'])) {
                        $vars4[0]['m9'] = (intval($vars3[0]['m9']) / intval($vars2['m9'])) * 100; //status
                        $scolor = $this->color($vars4[0]['m9']);
                    } else {
                        $vars4[0]['m9'] = (intval($vars4[0]['m9']) / intval($vars2['m9'])) * 100; //status
                        $scolor = $this->color($vars4[0]['m9']);
                    }
                    //m10
                    if (is_null($vars4[0]['m10'])) {
                        $vars4[0]['m10'] = (intval($vars3[0]['m10']) / intval($vars2['m10'])) * 100; //status
                        $ocolor = $this->color($vars4[0]['m10']);
                    } else {
                        $vars4[0]['m10'] = (intval($vars4[0]['m10']) / intval($vars2['m10'])) * 100; //status
                        $ocolor = $this->color($vars4[0]['m10']);
                    }
                    //m11
                    if (is_null($vars4[0]['m11'])) {
                        $vars4[0]['m11'] = (intval($vars3[0]['m11']) / intval($vars2['m11'])) * 100; //status
                        $ncolor = $this->color($vars4[0]['m11']);
                    } else {
                        $vars4[0]['m11'] = (intval($vars4[0]['m11']) / intval($vars2['m11'])) * 100; //status
                        $ncolor = $this->color($vars4[0]['m11']);
                    }
                    //m12
                    if (is_null($vars4[0]['m12'])) {
                        $vars4[0]['m12'] = (intval($vars3[0]['m12']) / intval($vars2['m12'])) * 100; //status
                        $dcolor = $this->color($vars4[0]['m12']);
                    } else {
                        $vars4[0]['m12'] = (intval($vars4[0]['m12']) / intval($vars2['m12'])) * 100; //status
                        $dcolor = $this->color($vars4[0]['m12']);
                    }


                    //CASO QUE REAL SEA NULL O CERO
                    //CASO QUE NO EXISTA VALOR PLANIFICADO



                    $va3 = array('id' => null,
                        'descripcion' => null, 'tipo' => 'Status %',
                        'ene' => $vars4[0]['m1'], 'feb' => $vars4[0]['m2'], 'mar' => $vars4[0]['m3'], 'abr' => $vars4[0]['m4'],
                        'may' => $vars4[0]['m5'], 'jun' => $vars4[0]['m6'], 'jul' => $vars4[0]['m7'], 'ago' => $vars4[0]['m8'],
                        'sep' => $vars4[0]['m9'], 'oct' => $vars4[0]['m10'], 'nov' => $vars4[0]['m11'], 'dic' => $vars4[0]['m12'],
                        'observacion' => $vars4[0]['observacion'],
                        'epintar' => $ecolor,
                        'fpintar' => $fcolor,
                        'mpintar' => $mcolor,
                        'apintar' => $acolor,
                        'mapintar' => $macolor,
                        'jpintar' => $jcolor,
                        'jupintar' => $jucolor,
                        'agpintar' => $agcolor,
                        'spintar' => $scolor,
                        'opintar' => $ocolor,
                        'npintar' => $ncolor,
                        'dpintar' => $dcolor);
                    array_push($indicadorFinal, $va3);
                   
                   
                   
                }elseif ($vars2['tipo'] == 'Produccion') {
                   
                  //en el caso de los indicadores de produccion se traera el valor real del campo meta de ficha tecnica.
                  //campos_indicador
                  //buscar tofos los campos necesarios
                  //valor planificado, es la meta de la tabla ficha tecnica, ademas se necesitan los campos
                    // que se buscaran en las temporales, para extraer la info real
                    //obtener todos los indicador
               $sql = "SELECT id, nombre, unidad_medida, meta,formula, campos_indicador FROM ficha_tecnica where  id= " . $vars2['ficha_id'];
               $stmt = $em->getConnection()->prepare($sql);
               $stmt->execute();
               $vars3 = $stmt->fetchAll(); 
               $vars =$vars3;
              
               $v3 = array('id' => $vars3[0]['id'],
                        'descripcion' => substr($vars3[0]['nombre'], 0, 150), 'tipo' => 'Planificado',
                        'ene' => $vars3[0]['meta'], 'feb' => $vars3[0]['meta'], 'mar' => $vars3[0]['meta'], 'abr' => $vars3[0]['meta'],
                        'may' => $vars3[0]['meta'], 'jun' => $vars3[0]['meta'], 'jul' => $vars3[0]['meta'], 'ago' => $vars3[0]['meta'],
                        'sep' => $vars3[0]['meta'], 'oct' => $vars3[0]['meta'], 'nov' => $vars3[0]['meta'], 'dic' => $vars3[0]['meta'],
                        'observacion' => '--PRODUCCION--',
                    );
                    array_push($indicadorFinal, $v3);  
               //inicio
                //buscar las temp
               //$indicadorFinal = array();
            
              //$cont++;
           
            $bandera = 0;
            $id = $vars[0]['id'];
            $un = $vars[0]['unidad_medida'];
            $ci = $vars[0]['campos_indicador'];
            $varMes = "";
            $varGestor = "";
             //FORMULA NUMERO O PORCENTAJE
            if (strtolower($un) == 'numero') {
                $formula = str_replace(' ', '', $vars[0]['formula']);
                preg_match_all('/\{([\w]+)\}/', $formula, $vars[0]['formula']);
                $formula = strtolower($formula);
                $formula = str_replace('{', '(', $formula);
                $formula = str_replace('}', ')', $formula);
                $formula = 'sum' . $formula;
            } else {
                $formula = str_replace(' ', '', $vars[0]['formula']);
                preg_match_all('/\{([\w]+)\}/', $formula, $vars[0]['formula']);
                $formula = strtolower($formula);
                $formula = str_replace('{', '(', $formula);
                $formula = str_replace('}', ')', $formula);
                $formula = str_replace('(', 'sum(', $formula);
            }
                   
//ENCONTRAR EL NOMBRE, MES O ID_MES
            if (strpos($ci, 'id_mes') !== false) {
                $varMes = 'id_mes';
            } else {
                $varMes = 'mes';
            }
//PARAMETROS: AÑO
            //GESTOR
            //ESTABLECIMIENTO
            $sql = "";

            if (strpos($ci, 'gestor') !== false AND $gest != '-- TODOS LOS GESTORES--') {
                $gest = str_replace(' ', '', $gest);
                $gest = strtolower($gest);
                $sql = " select " . $varMes . " as mes, " . $formula .
                        " as formula from tmp_ind_" . $id .
                        " WHERE anio = " . $periodo . " AND gestor ='" . $gest . "'" .
                        " group by " . $varMes;
            } else {
                $sql = " select " . $varMes . " as mes, " . $formula .
                        " as formula from tmp_ind_" . $id .
                        " WHERE anio = " . $periodo .
                        " group by " . $varMes;
            }
$stmt = $em->getConnection()->prepare($sql);
            try {
                $stmt->execute();
            } catch (\Exception $e) {
                continue;
            }

            $temp = $stmt->fetchAll();
 foreach ($temp as $temp_) {
                $valFormula = round($temp_['formula'], 2);
                //SEGUN EL VALOR QUE TRAIGA, HAY Q SACAR LOS COLORES
                $sql2 = " Select " .
                        "CASE WHEN id_color_alerta = 1 then 'verde'  " .
                        "WHEN id_color_alerta = 2 then 'naranja'  " .
                        "WHEN id_color_alerta = 3 then 'rojo' " .
                        "END as color " .
                        "FROM indicador_alertas where limite_inferior <= " . $valFormula . " and limite_superior >= " . $valFormula . " and id_indicador =" . $id;
                $stmt2 = $em->getConnection()->prepare($sql2);
                try {
                    $stmt2->execute();
                } catch (\Exception $e) {
                    continue;
                    $bandera = 1;
                }
                $temp_2 = $stmt2->fetchAll();
                $color = $temp_2[0]['color'];

                $columnas2 = $vars[5];
                switch ($temp_['mes']) {
                    case '1-Enero':
                    case 'a-ENE':
                    case 'ENERO':
                    case 1:
                        $var2['ene'] = $valFormula;
                        $var2['epintar'] = $color;
                        break;
                    case '2-Febrero':
                    case 'b-FEB':
                    case 'FEBRERO':
                    case 2:
                        $var2['feb'] = $valFormula;
                        $var2['fpintar'] = $color;
                        break;
                    case '3-Marzo':
                    case 'c-MAR':
                    case 'MARZO':
                    case 3:
                        $var2['mar'] = $valFormula;
                        $var2['mpintar'] = $color;
                        break;
                    case '4-Abril':
                    case 'd-ABR':
                    case 'ABRIL':
                    case 4:
                        $var2['abr'] = $valFormula;
                        $var2['bpintar'] = $color;
                        break;
                    case '5-Mayo':
                    case 'e-MAY':
                    case 'MAYO':
                    case 5:
                        $var2['may'] = $valFormula;
                        $var2['ypintar'] = $color;
                        break;
                    case '6-Junio':
                    case 'f-JUN':
                    case 'JUNIO':
                    case 6:
                        $var2['jun'] = $valFormula;
                        $var2['jpintar'] = $color;
                        break;
                    case '7-Julio':
                    case 'g-JUL':
                    case 'JULIO':
                    case 7:
                        $var2['jul'] = $valFormula;
                        $var2['lpintar'] = $color;
                        break;
                    case '8-Agosto':
                    case 'h-AGO':
                    case 'AGOSTO':
                    case 8:
                        $var2['ago'] = $valFormula;
                        $var2['apintar'] = $color;
                        break;
                    case '9-Septiembre':
                    case 'i-SEP':
                    case 'SEPTIEMBRE':
                    case 9:
                        $var2['sep'] = $valFormula;
                        $var2['spintar'] = $color;
                        break;
                    case '10-Octubre':
                    case 'j-OCT':
                    case 'OCTUBRE':
                    case 10:
                        $var2['oct'] = $valFormula;
                        $var2['opintar'] = $color;
                        break;
                    case '11-Noviembre':
                    case 'k-NOV':
                    case 'NOVIEMBRE':
                    case 11:
                        $var2['nov'] = $valFormula;
                        $var2['npintar'] = $color;
                        break;
                    case '12-Diciembre':
                    case 'l-DIC':
                    case 'DICIEMBRE':
                    case 12:
                        $var2['dic'] = $valFormula;
                        $var2['dpintar'] = $color;
                        break;
                }
            }
            if ($bandera == 0) {
               
                $var2['tipo'] = 'Status %';
                $var2['id']='';//$vars3[0]['id'];
                $var2['descripcion']=null;//$vars3[0]['nombre'];
                $var2['observacion']= '--PRODUCCION--';
                array_push($indicadorFinal, $var2);

                //$formula = $vars['formula'];
                //$columnas = $vars[5];
            } else {
                continue;
            }
              
               //fin
              
              
              
                   
                   
                }


                $cont++;
                //$id = $vars['id'];
                //$un = $vars['unidad_medida'];
                //$ci = $vars['campos_indicador'];
                //$varMes = "";
                //$varGestor = "";
            }
             $cont++;
        }












        $response = new JsonResponse();
        if (!$indicadorFinal) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => $indicadorFinal, 'mes' => $per[0]));
        }
        return $response;
        //$array = $this->ParametrosAction();
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formN.html.twig', $array);
    }





 public function fornpfichasAction() {
        $request = $this->getRequest();

        $fechaP = $request->request->get('fecha');
        $parametros = explode("&&", $fechaP);
        $fecha = $parametros[0];
        $per = $fecha; //explode("-", $fecha);
        $periodo = $fecha;
        $m = $per[0];
        $estP = $request->request->get('est');
        $matrizOriginal = $request->request->get('datos');
        $matriz = explode("&-&", $matrizOriginal);

        //$matriz = $request->request->get('datos');
        $em = $this->getDoctrine()->getManager();

        //$bloqueado = $this->bloqueo($em, $estP, $periodo, $m); //  NUEVO
        $response = new JsonResponse();
        //if ($bloqueado == 'true') {
        //    $response->setData(array('message' => 'false'));
        //    return $response;
        //}
        //$el = $this->removeMatrizg($periodo, $estP, $em);
        //$CONTEO = count($matriz);
        //GUARDA BLOQUEO
        //$objID1 = new indicadorB();
        //$objID1->setIdEstablecimiento($estP);
        //$objID1->setIdVariablePeriodo($campo['id']);
        //$objID1->setPeriodo($periodo);
        //$objID1->setMes($m);
        //$em->persist($objID1);
        //$em->flush();
        $idFichaTecnica;
        $idClas;
        $res = $this->removeMatrizGestor($estP, $periodo, $em);
        $contador = count($matriz);
        for ($i = 0; $i < $contador; $i++) {
           
            $amatriz = $matriz[$i];
            $campo = explode("!+", $amatriz);
            if($campo[15]!='--PRODUCCION--'){
               
           
            //REPLACE
            $conta = 0;
            //
            if ($campo[2] == '') {
                $idClas = $campo[0];
            } else {
                if ($campo[2] == 'Real') {
                    $idFichaTecnica = $campo[0];
                }
                $id = $campo[0];
                $ene = $campo[3];
                $feb = $campo[4];
                $mar = $campo[5];
                $abr = $campo[6];
                $may = $campo[7];
                $jun = $campo[8];
                $jul = $campo[9];
                $ago = $campo[10];
                $sep = $campo[11];
                $oct = $campo[12];
                $nov = $campo[13];
                $dic = $campo[14];

                if ($ene == "") {
                    $ene = 0;
                    $conta = $conta + 1;
                }
                if ($feb == "") {
                    $feb = 0;
                    $conta = $conta + 1;
                }
                if ($mar == "") {
                    $mar = 0;
                    $conta = $conta + 1;
                }
                if ($abr == "") {
                    $abr = 0;
                    $conta = $conta + 1;
                }
                if ($may == "") {
                    $may = 0;
                    $conta = $conta + 1;
                }
                if ($jun == "") {
                    $jun = 0;
                    $conta = $conta + 1;
                }
                if ($jul == "") {
                    $jul = 0;
                    $conta = $conta + 1;
                }
                if ($ago == "") {
                    $ago = 0;
                    $conta = $conta + 1;
                }
                if ($sep == "") {
                    $sep = 0;
                    $conta = $conta + 1;
                }
                if ($oct == "") {
                    $oct = 0;
                    $conta = $conta + 1;
                }
                if ($nov == "") {
                    $nov = 0;
                    $conta = $conta + 1;
                }
                if ($dic == "") {
                    $dic = 0;
                    $conta = $conta + 1;
                }
                if ($campo[2] == 'Real') {
                    $campo[2] = 1;
                } elseif ($campo[2] == 'Planificado') {
                    $campo[2] = 0;
                } elseif ($campo[2] == 'Status %') {
                    $campo[2] = 2;
                }
                if ($campo[2] != 2) {
                    //if ($conta != 12) {

                    $objID = new FichaDetalle();

                    $objID->setIdFicha($idFichaTecnica);
                    $objID->setIdClas($idClas);
                    $objID->setPeriodo($periodo);
                    $objID->setGestor($estP);
                    $objID->setTipo($campo[2]);
                    $objID->setM1($ene);
                    $objID->setM2($feb);
                    $objID->setM3($mar);
                    $objID->setM4($abr);
                    $objID->setM5($may);
                    $objID->setM6($jun);
                    $objID->setM7($jul);
                    $objID->setM8($ago);
                    $objID->setM9($sep);
                    $objID->setM10($oct);
                    $objID->setM11($nov);
                    $objID->setM12($dic);
                    $objID->setObservacion($campo[15]);
                    $em->persist($objID);
                    $em->flush();

                    // }
                }
            }
           
                }
        }
        //$response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'TRUE'));
        }
        return $response;



        //if (isset($_POST['datos'])) {
        //    $json = $_POST['datos'];
        //    var_dump(json_decode($json, true));
        //} else {
        //    echo "Noooooooob";
        //}
    }


  public function forndhAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:fornNDH.html.twig', $array);
    }
  public function forndAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formND.html.twig', $array);
    }



public function fornpdhAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorBH vp 
              WHERE  vp.idEstablecimiento = :pidEstablecimiento AND vp.periodo= :pperiodo
              AND vp.mes = :pmes';
        $query = $em->createQuery($sql);
        $query = $em->createQuery($sql);
        $query->setParameter('pperiodo', $periodo);
        $query->setParameter('pidEstablecimiento', $est);
        $query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }
public function fornpdhhtAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorBH vp ';
        $query = $em->createQuery($sql);
        //$query = $em->createQuery($sql);
        //$query->setParameter('pperiodo', $periodo);
        //$query->setParameter('pidEstablecimiento', $est);
        //$query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }
public function fornpdhhAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorBH vp 
              WHERE  vp.idEstablecimiento = :pidEstablecimiento ';
        $query = $em->createQuery($sql);
        //$query = $em->createQuery($sql);
        //$query->setParameter('pperiodo', $periodo);
        $query->setParameter('pidEstablecimiento', $est);
        //$query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }


public function fornpdgtAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorB vp  ';
        $query = $em->createQuery($sql);
        //$query = $em->createQuery($sql);
        //$query->setParameter('pperiodo', $periodo);
        //$query->setParameter('pidEstablecimiento', $est);
        //$query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }
public function fornpdgAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorB vp 
              WHERE  vp.idEstablecimiento = :pidEstablecimiento ';
        $query = $em->createQuery($sql);
        //$query = $em->createQuery($sql);
        //$query->setParameter('pperiodo', $periodo);
        $query->setParameter('pidEstablecimiento', $est);
        //$query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }
public function fornpdAction($param) {
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager(); //
        $sql = 'delete
              FROM IndicadoresBundle:IndicadorB vp 
              WHERE  vp.idEstablecimiento = :pidEstablecimiento AND vp.periodo= :pperiodo
              AND vp.mes = :pmes';
        $query = $em->createQuery($sql);
        $query = $em->createQuery($sql);
        $query->setParameter('pperiodo', $periodo);
        $query->setParameter('pidEstablecimiento', $est);
        $query->setParameter('pmes', $m);
        $numDel = $query->execute();
        $response = new JsonResponse();
        if (!$numDel) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => 'true'));
        }
        return $response;
    }

public function bloqueoh($em, $IdEstablecimiento, $periodo, $m) {
        //$objID1->setIdEstablecimiento($estP);
        //$objID1->setIdVariablePeriodo($campo['id']);
        //$objID1->setPeriodo($periodo);
        //$objID1->setMes($m);
        //$em = $this->getDoctrine()->getManager();//
        $sql = 'SELECT vp.idIndicadorbh
              FROM IndicadoresBundle:IndicadorBH vp 
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






 public function crearReporte2Action($gestor, $establecimiento, $periodo){
 $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='GESTOR'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablePeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }
$array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento,
                        'fecha'=> date('Y-m-d'),
			'valores'=>$valores);
        $array3= $array + $array2 + $array4;  
       
        //ob_start();
        //$html = ob_get_clean();
        //$html= $this->get('templating')->render('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);
       
        //$html2pdf = new \Html2Pdf_Html2Pdf('L','letter','fr');
        //$html2pdf->pdf->SetDisplayMode('real');
        //$html2pdf->writeHTML($html);
        //$content =$html2pdf->Output('GestoresVariable.pdf');

        //$response = new Response();
        //$response->setContent($content);
       
        //$response->headers->set('Content-Type', 'application/pdf');

        //$this->headers->set('Content-Type', 'application/pdf');
       
//$array5 = array('html' => $html->getContent());
        //$array6= $array3+ $array5;
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2.html.twig', $array6);
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);
        //return $response;  
    }


public function crearReporte2hAction($gestor, $establecimiento, $periodo){
 $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='HOSP'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablehPeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorhDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorhDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorhDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorhDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorhDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorhDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorhDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorhDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorhDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorhDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorhDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorhDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorhDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }
$array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento,
                        'fecha'=> date('Y-m-d'));
        $array3= $array + $array2 + $array4;  
       
        //ob_start();
        //$html = ob_get_clean();
        //$html= $this->get('templating')->render('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);
       
        //$html2pdf = new \Html2Pdf_Html2Pdf('L','letter','fr');
        //$html2pdf->pdf->SetDisplayMode('real');
        //$html2pdf->writeHTML($html);
        //$content =$html2pdf->Output('GestoresVariable.pdf');

        //$response = new Response();
        //$response->setContent($content);
       
        //$response->headers->set('Content-Type', 'application/pdf');

        //$this->headers->set('Content-Type', 'application/pdf');
       
//$array5 = array('html' => $html->getContent());
        //$array6= $array3+ $array5;
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2.html.twig', $array6);
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reportegeh.html.twig',$array3);
        //return $response;  
    }



 public function crearReporteGAction($gestor, $establecimiento, $periodo){
 $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='GESTOR'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablePeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }       

  	$array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento,
                        'fecha'=> date('Y-m-d'));
        $array3= $array + $array2 + $array4;  
        //ob_start();
        //$html = ob_get_clean();
        $html= $this->renderView('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);  
        $html2pdf = new \Html2Pdf_Html2Pdf('L','letter','fr');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('GestoresVariable.pdf');
        $response = new Response();
        $response->setContent($html);
        $response->headers->set('Content-Type', 'application/pdf');
        //$this->headers->set('Content-Type', 'application/pdf');
        //$array5 = array('html' => $html->getContent());
        //$array6= $array3+ $array5;
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2.html.twig', $array6);
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);
        return $response;  
    }  
 public function crearReporteGHAction($gestor, $establecimiento, $periodo){

 $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='HOSP'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablehPeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorhDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorhDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorhDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorhDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorhDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorhDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorhDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorhDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorhDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorhDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorhDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorhDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorhDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }

  	$array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento,
                        'fecha'=> date('Y-m-d'));
        $array3= $array + $array2 + $array4;  
        //ob_start();
        //$html = ob_get_clean();
        $html= $this->renderView('IndicadoresBundle:FichaTecnicaAdmin:reportegeh.html.twig',$array3);  
        $html2pdf = new \Html2Pdf_Html2Pdf('L','letter','fr');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('GestoresVariable.pdf');
        $response = new Response();
        $response->setContent($html);
        $response->headers->set('Content-Type', 'application/pdf');
        //$this->headers->set('Content-Type', 'application/pdf');
        //$array5 = array('html' => $html->getContent());
        //$array6= $array3+ $array5;
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2.html.twig', $array6);
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reportege.html.twig',$array3);
        return $response;  
    }   
   
   
   
public function crearReporteAction($gestor, $estab, $periodo){
     $array2 = $this->ParametrosAction();
     $array3 = array('periodo' => $periodo,
                     'gestor'=> $gestor,
                     'estab'=>$estab
                     );
     $array= $array2 + $array3;
     return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2.html.twig',$array);
}
public function crearReportehAction($gestor, $estab, $periodo){
     $array2 = $this->ParametrosAction();
     $array3 = array('periodo' => $periodo,
                     'gestor'=> $gestor,
                     'estab'=>$estab
                     );
     $array= $array2 + $array3;
     return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte2h.html.twig',$array);
}





public function bgEstablecimientoUAction($us) {
        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $idUsuario = $usuario->getId();
        $role = $usuario->getRoles();
        $usuarioPermitido = false;
        if (in_array("ROLE_USER_TABLERO", $role)) {
            $usuarioPermitido = true;
        }
        $idDepto = '';
        $superA = $role[0];
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $us = str_replace("'", "", $us);


        $sql = 'SELECT es.idEstablecimiento as id, es.descripcion as nombre
                  FROM IndicadoresBundle:Establecimiento es
                  LEFT JOIN IndicadoresBundle:UsuarioEstablecimiento ues WITH (ues.idEstablecimiento= es.idEstablecimiento)
                  WHERE  ues.idUsuario = :us';

        $query = $em->createQuery($sql);
                   
        $query->setParameter('us', $us);
        $municipioDepto = $query->getResult();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($municipioDepto, 'json');
        $response->setData($municipioDepto);
        return $response;
    }



public function bEstablecimientoUAction($us)
    {
       $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $idUsuario=$usuario->getId();
        $role= $usuario->getRoles();
        $usuarioPermitido= false;
        //in_array("ROLE_USER_TABLERO", $role)
	if ($idUsuario==8) {
          $usuarioPermitido=true;
        }
         $idDepto='';
         $superA= $role[0];
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $us = str_replace("'", "", $us);


         $dql='SELECT a.idEstablecimiento as id, e.descripcion as nombre  
              FROM IndicadoresBundle:UsuarioEstablecimiento a  
              JOIN IndicadoresBundle:Establecimiento e WITH (a.idEstablecimiento = e.idEstablecimiento AND e.idMunicipio =:idMunicipio)
              WHERE a.idUsuario= :idUsuario order by  a.idEstablecimiento';
       
        $query = $em->createQuery($dql);
        //$idUsuario=3;
        $query->setParameter('idUsuario', $idUsuario);
        $query->setParameter('idMunicipio', $us);
        $municipioDepto = $query->getResult();
            
            $encoders = array(new XmlEncoder(), new JsonEncoder());
             $normalizers = array(new GetSetMethodNormalizer());
             $serializer = new Serializer($normalizers, $encoders);
             $jsonContent = $serializer->serialize($municipioDepto, 'json');
             $response->setData($municipioDepto);
        return $response;
    }

public function buscarEstablecimientoUAction($usuario)
    {
       $em = $this->getDoctrine()->getManager();
       $idDepto='';
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $depto = str_replace("'", "", $usuario);

        $dql = 'SELECT e.idEstablecimiento as id, e.descripcion as nombre
    FROM IndicadoresBundle:Establecimiento e 
    WHERE e.idMunicipio = :idMunicipio
    ';
//IDENTITY
        $dql='SELECT a.idEstablecimiento as id, e.descripcion as nombre, IDENTITY(e.idMunicipio), m.codigo, m.nombre as nm  
              FROM IndicadoresBundle:UsuarioEstablecimiento a   
              LEFT JOIN IndicadoresBundle:Establecimiento e WITH (a.idEstablecimiento = e.idEstablecimiento) 
              LEFT JOIN IndicadoresBundle:Municipio m WITH (e.idMunicipio = m.id)
              WHERE a.idUsuario= :idUsuario';
     
    $query = $em->createQuery($dql)->setParameter('idUsuario', $depto);
    $municipioDepto = $query->getResult();
             $encoders = array(new XmlEncoder(), new JsonEncoder());
             $normalizers = array(new GetSetMethodNormalizer());
             $serializer = new Serializer($normalizers, $encoders);
             $jsonContent = $serializer->serialize($municipioDepto, 'json');
             $response->setData($municipioDepto);
        return $response;
    } 



public function buscarEstablecimientoAction($muni)
    {
       $em = $this->getDoctrine()->getManager();
       $idDepto='';
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $depto = str_replace("'", "", $muni);

//IDENTITY
$dql = 'SELECT e.idEstablecimiento as id, e.descripcion as nombre
    FROM IndicadoresBundle:Establecimiento e 
    WHERE e.idMunicipio = :idMunicipio
    ';
    $query = $em->createQuery($dql)->setParameter('idMunicipio', $depto);
    $municipioDepto = $query->getResult();
             $encoders = array(new XmlEncoder(), new JsonEncoder());
             $normalizers = array(new GetSetMethodNormalizer());
             $serializer = new Serializer($normalizers, $encoders);
             $jsonContent = $serializer->serialize($municipioDepto, 'json');
             $response->setData($municipioDepto);
        return $response;
    }    


public function buscarMunicipioAction($depto)
    {
       $em = $this->getDoctrine()->getManager();
       $idDepto='';
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $depto = str_replace("'", "", $depto);

//IDENTITY
$dql = 'SELECT e.id, e.codigo, e.nombre
    FROM IndicadoresBundle:Municipio e 
    WHERE e.idDepartamento = :idDepartamento
    ';
    $query = $em->createQuery($dql)->setParameter('idDepartamento', $depto);
    $municipioDepto = $query->getResult();
             $encoders = array(new XmlEncoder(), new JsonEncoder());
             $normalizers = array(new GetSetMethodNormalizer());
             $serializer = new Serializer($normalizers, $encoders);
             $jsonContent = $serializer->serialize($municipioDepto, 'json');
             $response->setData($municipioDepto);
        return $response;
    }


public function guardarEUAction() {
        $request = $this->getRequest();
        
        $usuarioP= $request->request->get('usuario');
        $parametros = explode("&&", $usuarioP);
        $usuario = $parametros[0];

        $mun= $request->request->get('mun');
        $matrizP   = $request->request->get('datos');
        $matriz  = explode("&&", $matrizP);
        $em = $this->getDoctrine()->getManager();
        $el= $this->removeMatrizEU($usuario, $em); 
        $CONTEO=count($matriz);
         foreach ($matriz as $campo) {
            //REPLACE
            $conta=0;
            $objID = new UsuarioEstablecimiento();
            $usuarios=$em->getRepository("IndicadoresBundle:User")->findBy(array('id' => $usuario));
            $objID->setIdUsuario($usuario);
            $objID->setIdEstablecimiento($campo);
            $em->persist($objID);
            $em->flush();
         }
        
}
public function removeMatrizEU($usuario, $em){
    $sql='delete from IndicadoresBundle:UsuarioEstablecimiento id where id.idUsuario =:usuario';
     $query= $em->createQuery($sql);
     $query ->setParameter('usuario', $usuario);
     $numDel= $query->execute();
     return $numDel;
}

public function fornsAction() {
        $request = $this->getRequest();

        $fechaP = $request->request->get('fecha');
        $parametros = explode("&&", $fechaP);
        $fecha = $parametros[0];
        $per = explode("-", $fecha);
        $periodo = $per[1];
        $m = $per[0];
        $estP = $request->request->get('est');
        $matriz = $request->request->get('datos');
        $em = $this->getDoctrine()->getManager();

        $bloqueado = $this->bloqueo($em, $estP, $periodo, $m);//  NUEVO
        $response = new JsonResponse();
        if ($bloqueado == 'true') {
            $response->setData(array('message' => 'false'));
            return $response;
        }else{
	   //$response->setData(array('message' => 'false'));
           //return $response;

	}
       
        $el = $this->removeMatrizg($periodo, $estP, $em);
        $CONTEO = count($matriz);
        //GUARDA BLOQUEO
        $objID1 = new indicadorB();
        $objID1->setIdEstablecimiento($estP);
        $objID1->setIdVariablePeriodo($campo['id']);
        $objID1->setPeriodo($periodo);
        $objID1->setMes($m);
        $em->persist($objID1);
        $em->flush();
        foreach ($matriz as $campo) {
            //REPLACE
            $conta = 0;
            $ene = $campo['ene'];
            $feb = $campo['feb'];
            $mar = $campo['mar'];
            $abr = $campo['abr'];
            $may = $campo['may'];
            $jun = $campo['jun'];
            $jul = $campo['jul'];
            $ago = $campo['ago'];
            $sep = $campo['sep'];
            $oct = $campo['oct'];
            $nov = $campo['nov'];
            $dic = $campo['dic'];

            if ($ene == "") {
                $ene = 0;
                $conta = $conta + 1;
            }
            if ($feb == "") {
                $feb = 0;
                $conta = $conta + 1;
            }
            if ($mar == "") {
                $mar = 0;
                $conta = $conta + 1;
            }
            if ($abr == "") {
                $abr = 0;
                $conta = $conta + 1;
            }
            if ($may == "") {
                $may = 0;
                $conta = $conta + 1;
            }
            if ($jun == "") {
                $jun = 0;
                $conta = $conta + 1;
            }
            if ($jul == "") {
                $jul = 0;
                $conta = $conta + 1;
            }
            if ($ago == "") {
                $ago = 0;
                $conta = $conta + 1;
            }
            if ($sep == "") {
                $sep = 0;
                $conta = $conta + 1;
            }
            if ($oct == "") {
                $oct = 0;
                $conta = $conta + 1;
            }
            if ($nov == "") {
                $nov = 0;
                $conta = $conta + 1;
            }
            if ($dic == "") {
                $dic = 0;
                $conta = $conta + 1;
            }

            if ($conta != 12) {

                $objID = new IndicadorDetalle();
                $objID->setIdEstablecimiento($estP);
                $objID->setIdVariablePeriodo($campo['id']);
                $objID->setPeriodo($periodo);
                $objID->setM1($ene);
                $objID->setM2($feb);
                $objID->setM3($mar);
                $objID->setM4($abr);
                $objID->setM5($may);
                $objID->setM6($jun);
                $objID->setM7($jul);
                $objID->setM8($ago);
                $objID->setM9($sep);
                $objID->setM10($oct);
                $objID->setM11($nov);
                $objID->setM12($dic);
                $em->persist($objID);
                $em->flush();
            }
        }
        //$response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => $Establecimiento, 'mes' => $per[0]));
        }
        return $response;



        //if (isset($_POST['datos'])) {
        //    $json = $_POST['datos'];
        //    var_dump(json_decode($json, true));
        //} else {
        //    echo "Noooooooob";
        //}
    }
    
    
    public function fornshAction() {
        $request = $this->getRequest();
        
        $fechaP= $request->request->get('fecha');
        $parametros = explode("&&", $fechaP);
        $fecha = $parametros[0];
        $per= explode("-", $fecha);
        $periodo=$per[1];
	$m = $per[0];
        $estP= $request->request->get('est');
        $matriz   = $request->request->get('datos');
        $em = $this->getDoctrine()->getManager();
	
	$bloqueado = $this->bloqueoh($em, $estP, $periodo, $m);//  NUEVO
        $response = new JsonResponse();
        if ($bloqueado == 'true') {
            $response->setData(array('message' => 'false'));
            return $response;
        }else{
	   //$response->setData(array('message' => 'false'));
           //return $response;

	}
	
        $el= $this->removeMatriz($periodo,$estP, $em); 
        $CONTEO=count($matriz);
//GUARDA BLOQUEO
        $objID1 = new indicadorBH();
        $objID1->setIdEstablecimiento($estP);
        $objID1->setIdVariablePeriodo($campo['id']);
        $objID1->setPeriodo($periodo);
        $objID1->setMes($m);
        $em->persist($objID1);
        $em->flush();
        foreach ($matriz as $campo) {
            //REPLACE
            $conta=0;
            $ene=$campo['ene'];
            $feb=$campo['feb'];
            $mar=$campo['mar'];
            $abr=$campo['abr'];
            $may=$campo['may'];
            $jun=$campo['jun'];
            $jul=$campo['jul'];
            $ago=$campo['ago'];
            $sep=$campo['sep'];
            $oct=$campo['oct'];
            $nov=$campo['nov'];
            $dic=$campo['dic'];
            
            if($ene==""){
                $ene=0;
                $conta=$conta+1;
            }
            if($feb==""){
                $feb=0;
                $conta=$conta+1;
            }
            if($mar==""){
                $mar=0;
                $conta=$conta+1;
            }
            if($abr==""){
                $abr=0;
                $conta=$conta+1;
            }
            if($may==""){
                $may=0;
                $conta=$conta+1;
            }
            if($jun==""){
                $jun=0;
                $conta=$conta+1;
            }
            if($jul==""){
                $jul=0;
                $conta=$conta+1;
            }
            if($ago==""){
                $ago=0;
                $conta=$conta+1;
            }
            if($sep==""){
                $sep=0;
                $conta=$conta+1;
            }
            if($oct==""){
                $oct=0;
                $conta=$conta+1;
            }
            if($nov==""){
                $nov=0;
                $conta=$conta+1;
            }
             if($dic==""){
                $dic=0;
                $conta=$conta+1;
            }
            
            if ($conta != 12){
            $objID = new IndicadorhDetalle();
            $objID->setIdEstablecimiento($estP);
            $objID->setIdVariablePeriodo($campo['id']);
            $objID->setPeriodo($periodo);
            $objID->setM1($ene);
            $objID->setM2($feb);
            $objID->setM3($mar);
            $objID->setM4($abr);
            $objID->setM5($may);
            $objID->setM6($jun);
            $objID->setM7($jul);
            $objID->setM8($ago);
            $objID->setM9($sep);
            $objID->setM10($oct);
            $objID->setM11($nov);
            $objID->setM12($dic);
            $em->persist($objID);
            $em->flush();
            }
            
            
        }
       //$response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => $Establecimiento, 'mes' => $per[0]));
        }
        return $response;
        
        
        //if (isset($_POST['datos'])) {
        //    $json = $_POST['datos'];
        //    var_dump(json_decode($json, true));
        //} else {
        //    echo "Noooooooob";
        //}
    }
    
    
    
    
    // Elimina todos y los vuelve a cargar
    public function removeMatriz($per, $est, $em){
    $sql='delete from IndicadoresBundle:IndicadorhDetalle id where id.idEstablecimiento = :est and id.periodo =:per';
     $query= $em->createQuery($sql);
     $query ->setParameter('est', $est);
     $query ->setParameter('per', $per);
     $numDel= $query->execute();
     return $numDel;
}

// Elimina todos y los vuelve a cargar
    public function removeMatrizg($per, $est, $em){
    $sql='delete from IndicadoresBundle:IndicadorDetalle id where id.idEstablecimiento = :est and id.periodo =:per';
     $query= $em->createQuery($sql);
     $query ->setParameter('est', $est);
     $query ->setParameter('per', $per);
     $numDel= $query->execute();
     return $numDel;
}
    
   public function fornpmAction($param) {
        $parametros = explode("&&", $param);
        //$fecha = //$parametros[0];
        //$per = explode("-", $fecha);
        $periodo =  $parametros[0];//$per[1];
        $est = $parametros[1];//$parametros[1];
        $gest= $parametros[2];
        $em = $this->getDoctrine()->getManager(); //
       
        $sql="SELECT vp.id, vp.descripcion, id.m1 as ene, id.m2 as feb, id.m3 as mar, id.m4 as abr, id.m5 as may, id.m6 as jun, id.m7 as jul, id.m8 as ago, id.m9 as sep, id.m10 as oct, id.m11 as nov, id.m12 as dic
,
 CASE WHEN  (select variableperiodo_id
             from variable_pintar
             where vp.id = variableperiodo_id and pintar_id = an.id) IS NULL THEN false else true end as pintar
FROM variable_periodo vp 
              LEFT JOIN anio an ON (an.nombre='" . $periodo ."')
              JOIN variable_anio vpi ON (vpi.anio_id = an.id and  vpi.variableperiodo_id = vp.id )
              LEFT JOIN  indicador_detalle id ON (id.periodo='" . $periodo ."' and id.id_variable_periodo = vp.id and id.id_establecimiento=  " . $est .") ";
         $stmt = $em->getConnection()->prepare($sql);    
         $stmt->execute();
         $Establecimiento =$stmt->fetchAll();
        //1
        //obtener todos los indicador
        $sql="SELECT id, nombre, unidad_medida, meta,formula, campos_indicador FROM ficha_tecnica order by id DESC";
       
       
         $stmt = $em->getConnection()->prepare($sql);    
         $stmt->execute();
         $indicadores =$stmt->fetchAll();
        
         //2
         //buscar las temp
        
         $indicadorFinal = array();
         $cont =0;
         foreach ($indicadores as $vars) {
             $var2 = array('id'=> $vars['id'],
                           'descripcion'=> $vars['nombre'],
                           'ene'=> null,'feb'=> null,'mar'=> null,'abr'=> null,
                           'may'=> null,'jun'=> null,'jul'=> null,'ago'=> null,
                           'sep'=> null,'oct'=> null,'nov'=> null,'dic'=> null,
                           'epintar'=> 'blanco',
                           'fpintar'=> 'blanco',
                           'mpintar'=> 'blanco',
                           'bpintar'=> 'blanco',
                           'ypintar'=> 'blanco',
                           'jpintar'=> 'blanco',
                           'lpintar'=> 'blanco',
                           'apintar'=> 'blanco',
                           'spintar'=> 'blanco',
                           'opintar'=> 'blanco',
                           'npintar'=> 'blanco',
                           'dpintar'=> 'blanco',
                 );
             
              $cont++;
            $id = $vars['id'];
            $un = $vars['unidad_medida'];
            $ci = $vars['campos_indicador'];
            $varMes ="";
             //CAMPOS DE FORMULA
            //FORMULA NUMERO O PORCENTAJE
            if (strtolower($un) == 'numero'){
                $formula = str_replace(' ', '', $vars['formula']);
                preg_match_all('/\{([\w]+)\}/', $formula, $vars['formula']);
                $formula = strtolower($formula);
                $formula = str_replace('{', '(', $formula);
                $formula = str_replace('}', ')', $formula);
                $formula = 'sum' . $formula;
            } else {
                $formula = str_replace(' ', '', $vars['formula']);
                preg_match_all('/\{([\w]+)\}/', $formula, $vars['formula']);
                $formula = strtolower($formula);
                $formula = str_replace('{', '(', $formula);
                $formula = str_replace('}', ')', $formula);
                 $formula = str_replace('(', 'sum(', $formula);
            }
            //ENCONTRAR EL NOMBRE, MES O ID_MES
            if(strpos($ci, 'id_mes') !== false){
                $varMes='id_mes';
            }else{
                $varMes='mes';
            }
            //PARAMETROS: AÑO
            //GESTOR

//ESTABLECIMIENTO
            $sql = "";
           
             if(strpos($ci, 'gestor') !== false AND $gest != '-- TODOS LOS GESTORES--'){
                   $gest = str_replace(' ', '', $gest);
                    $gest = strtolower($gest);
                 $sql = " select " . $varMes . " as mes, " . $formula .
                   " as formula from tmp_ind_" . $id .
                   " WHERE anio = " . $periodo . " AND gestor ='" . $gest . "'".
                   " group by " . $varMes;
            } else{
                   $sql = " select " . $varMes . " as mes, " . $formula .
                   " as formula from tmp_ind_" . $id .
                   " WHERE anio = " . $periodo .
                   " group by " . $varMes;
            }
            $stmt = $em->getConnection()->prepare($sql);




try{
             $stmt->execute();
            $temp = $stmt->fetchAll();
} catch (\Exception $e){
   continue;
}
           
           
        
            foreach ($temp as $temp_) {
                $valFormula = round($temp_['formula'], 2);
                //SEGUN EL VALOR QUE TRAIGA, HAY Q SACAR LOS COLORES
                $sql2 = " Select " .
                        "CASE WHEN id_color_alerta = 1 then 'verde'  " .
                        "WHEN id_color_alerta = 2 then 'naranja'  " .
                        "WHEN id_color_alerta = 3 then 'rojo' " .
                        "END as color " .
                        "FROM indicador_alertas where limite_inferior <= " .$valFormula ." and limite_superior >= " . $valFormula ." and id_indicador =" .$id  ;
                $stmt2 = $em->getConnection()->prepare($sql2);
                try{
             $stmt2->execute();
            $temp_2= $stmt2->fetchAll();
} catch (\Exception $e){
   continue;
}
 
                $color= $temp_2[0]['color'];  

                $columnas2 = $vars[5];
                switch ($temp_['mes']) {
                    case '1-Enero':
                    case 'a-ENE':
                    case 'ENERO':
                    case 1:
                        $var2['ene'] = $valFormula;
                        $var2['epintar'] = $color;
                        break;
                    case '2-Febrero':
                    case 'b-FEB':
                    case 'FEBRERO':
                    case 2:
                        $var2['feb'] = $valFormula;
                        $var2['fpintar'] = $color;
                        break;
                    case '3-Marzo':
                    case 'c-MAR':
                    case 'MARZO':
                    case 3:
                        $var2['mar'] = $valFormula;
                        $var2['mpintar'] = $color;
                        break;
                    case '4-Abril':
                    case 'd-ABR':
                    case 'ABRIL':
                    case 4:
                        $var2['abr'] = $valFormula;
                        $var2['bpintar'] = $color;
                        break;
                    case '5-Mayo':
                    case 'e-MAY':
                    case 'MAYO':
                    case 5:
                        $var2['may'] = $valFormula;
                        $var2['ypintar'] = $color;
                        break;
                    case '6-Junio':
                    case 'f-JUN':
                    case 'JUNIO':
                    case 6:
                        $var2['jun'] = $valFormula;
                        $var2['jpintar'] = $color;
                        break;
                    case '7-Julio':
                    case 'g-JUL':
                    case 'JULIO':
                    case 7:
                        $var2['jul'] = $valFormula;
                        $var2['lpintar'] = $color;
                        break;
                    case '8-Agosto':
                    case 'h-AGO':
                    case 'AGOSTO':
                    case 8:
                        $var2['ago'] = $valFormula;
                        $var2['apintar'] = $color;
                        break;
                    case '9-Septiembre':
                    case 'i-SEP':
                    case 'SEPTIEMBRE':
                    case 9:
                        $var2['sep'] = $valFormula;
                        $var2['spintar'] = $color;
                        break;
                    case '10-Octubre':
                    case 'j-OCT':   
                    case 'OCTUBRE':
                    case 10:
                        $var2['oct'] = $valFormula;
                        $var2['opintar'] = $color;
                        break;
                    case '11-Noviembre':
                    case 'k-NOV':
                    case 'NOVIEMBRE':
                    case 11:
                        $var2['nov'] = $valFormula;
                        $var2['npintar'] = $color;
                        break;
                    case '12-Diciembre':
                    case 'l-DIC':
                    case 'DICIEMBRE':
                    case 12:
                        $var2['dic'] = $valFormula;
                        $var2['dpintar'] = $color;
                        break;
                }
            }

            array_push($indicadorFinal, $var2);
           
            $formula = $vars['formula'];
            $columnas = $vars[5];
           
        }


        $response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'false'));
        } else {
            //$enc= json_encode($Establecimiento);
            $response->setData(array('message' => $indicadorFinal, 'mes' => '01'));
        }
        return $response;
        //$array = $this->ParametrosAction();
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formN.html.twig', $array);
    } 

    public function fornpAction($param) {
        //$serializer = $container->get('jms_serializer');
        //$serializer->serialize($data, 'json'); // json|xml|yml
        //$data = $serializer->deserialize($inputStr, $typeName, $format);
        
        
        
        
        $parametros = explode("&&", $param);
        $fecha = $parametros[0];
        $per= explode("-", $fecha);
        $periodo=$per[1];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager();// 
        $sql="SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic, vpi.pintar as Pintar
              FROM IndicadoresBundle:VariablePeriodo vp 
              JOIN IndicadoresBundle:Vpindicador vpi WITH (vpi.tipo= 'G' and vpi.periodo =:pperiodo and vpi.idindicador = vp.id and vpi.estado='TRUE' )
              LEFT JOIN IndicadoresBundle:IndicadorDetalle id WITH (id.periodo=:pperiodo and id.idVariablePeriodo = vp.id and id.idEstablecimiento= :pestablecimiento) 
              ORDER BY vp.id";
            
//'SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic
//              FROM IndicadoresBundle:VariablePeriodo vp  
//              LEFT JOIN IndicadoresBundle:IndicadorDetalle id WITH (id.periodo=:pperiodo and id.idVariablePeriodo = vp.id and id.idEstablecimiento= :pestablecimiento)  
//              WHERE  vp.periodo = :pperiodo ORDER BY vp.id';




//$query= $em->createQuery($sql);
//            $query ->setParameter('pperiodo', $periodo);
//            $query ->setParameter('pestablecimiento', $est);
//            //$res= $query->getResult();
//            $Establecimiento=$query->getResult();
   $sql="SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic
,
 CASE WHEN  (select variableperiodo_id
             from variable_pintar
             where vp.id = variableperiodo_id and pintar_id = an.id) IS NULL THEN false else true end as pintar
FROM variable_periodo vp 
              LEFT JOIN anio an ON (an.nombre='" . $periodo ."')
              JOIN variable_anio vpi ON (vpi.anio_id = an.id and  vpi.variableperiodo_id = vp.id )
              LEFT JOIN  indicador_detalle id ON (id.periodo='" . $periodo ."' and id.id_variable_periodo = vp.id and id.id_establecimiento=  " . $est .") ORDER BY vp.id";
       
       
       
         $stmt = $em->getConnection()->prepare($sql);    
         $stmt->execute();
         $Establecimiento =$stmt->fetchAll();      
        
        
         $response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'false'));
        } else {
          //$enc= json_encode($Establecimiento);
          $response->setData(array('message' => $Establecimiento,'mes'=>$per[0])); 
        }
        return $response;
        //$array = $this->ParametrosAction();
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formN.html.twig', $array);
    }
    //indicadores Hospitales
     public function fornphAction($paramh) {
        //$serializer = $container->get('jms_serializer');
        //$serializer->serialize($data, 'json'); // json|xml|yml
        //$data = $serializer->deserialize($inputStr, $typeName, $format);
        
        
        
        
        $parametros = explode("&&", $paramh);
        $fecha = $parametros[0];
        $per= explode("-", $fecha);
        $periodo=$per[1];
        $est = $parametros[1];
        $em = $this->getDoctrine()->getManager();// 
        $sql= "SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic, vpi.pintar as Pintar 
              FROM IndicadoresBundle:VariablehPeriodo vp  
              JOIN IndicadoresBundle:Vpindicador vpi WITH (vpi.tipo= 'H' and vpi.periodo =:pperiodo and vpi.idindicador = vp.id and vpi.estado='TRUE' ) 
              LEFT JOIN IndicadoresBundle:IndicadorhDetalle id WITH (id.periodo=:pperiodo and id.idVariablePeriodo = vp.id and id.idEstablecimiento= :pestablecimiento)  
              ORDER BY vp.id ";



// 'SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic, vpi.pintar as Pintar
//               FROM IndicadoresBundle:VariablehPeriodo vp  
//               LEFT JOIN IndicadoresBundle:IndicadorhDetalle id WITH (id.periodo=:pperiodo and id.idVariablePeriodo = vp.id and id.idEstablecimiento= :pestablecimiento)  
//               WHERE  vp.periodo = :pperiodo ORDER BY vp.id ';



            
           //$sql="SELECT vp.id  FROM IndicadoresBundle:VariablehPeriodo vp ";
        //$sql="SELECT id.m1 as Ene FROM IndicadoresBundle:IndicadorhDetalle id";
            //$query= $em->createQuery($sql);
            //$query ->setParameter('pperiodo', $periodo);
            //$query ->setParameter('pestablecimiento', $est);
            //$res= $query->getResult();
            //$Establecimiento=$query->getResult();
 $sql="SELECT vp.id, vp.descripcion, id.m1 as Ene, id.m2 as Feb, id.m3 as Mar, id.m4 as Abr, id.m5 as May, id.m6 as Jun, id.m7 as Jul, id.m8 as Ago, id.m9 as Sep, id.m10 as Oct, id.m11 as Nov, id.m12 as Dic
,
 CASE WHEN  (select variablehperiodo_id
             from variableh_pintar
             where vp.id = variablehperiodo_id and pintar_id = an.id) IS NULL THEN false else true end as pintar
FROM variableh_periodo vp 
              LEFT JOIN anio an ON (an.nombre='" . $periodo ."')
              JOIN variableh_anio vpi ON (vpi.anio_id = an.id and  vpi.variablehperiodo_id = vp.id )
              LEFT JOIN  indicadorh_detalle id ON (id.periodo='" . $periodo ."' and id.id_variable_periodo = vp.id and id.id_establecimiento=  " . $est .") order by vp.id";
       
       
       
         $stmt = $em->getConnection()->prepare($sql);    
         $stmt->execute();
         $Establecimiento =$stmt->fetchAll(); 
        
        
         $response = new JsonResponse();
        if (!$Establecimiento) {
            $response->setData(array('message' => 'false'));
        } else {
          //$enc= json_encode($Establecimiento);
          $response->setData(array('message' => $Establecimiento,'mes'=>$per[0]));  
        }
        return $response;
        //$array = $this->ParametrosAction();
        //return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formN.html.twig', $array);
    }

    public function fornAction()
       {
               
              
		$array=$this->ParametrosAction();
		return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formN.html.twig', $array );
	} 

   public function ueAction()
       {
        $array=$this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:usuarioEstablecimiento.html.twig', $array );
    }

     public function fornhAction()
       {
               
              
		$array=$this->ParametrosAction();
		return $this->render('IndicadoresBundle:FichaTecnicaAdmin:formNH.html.twig', $array );
	}   
    
    

public function reporteAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporte.html.twig', $array);
    }
public function matrizAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:matriz.html.twig', $array);
    }
public function reportehAction() {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:reporteh.html.twig', $array);
    }
public function reporterAction($gestor, $establecimiento,$periodo) {
        $array = $this->ParametrosAction();
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:print.html.twig', $array);
    }



public function reporterexcelAction($gestor, $establecimiento,$periodo) {
 $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='GESTOR'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablePeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }
       
    
        $Establecimiento = $query->getResult();
       

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($Establecimiento, 'json');
        $response = new JsonResponse();
        $response->setData($Establecimiento);
        return $response;
       
        } 
public function reporterexcelhAction($gestor, $establecimiento,$periodo) {
  
    $em = $this->getDoctrine()->getManager();
$indicador=0;
$valores="";
if($gestor==0){
            $vgestor= "WHERE fuu.id IN (:padmin)";
            //$vgestor= "WHERE :padmin MEMBER OF ";
            $sqlxx="SELECT f3.id  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='HOSP'";//, f3.username
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();
            $valores="";
            $ids = array();
            foreach ($usuariosf as $campo) {
            //REPLACE
            $conta = 0;
             if($valores!=""){
                $valores=$valores . ", ";
               }
            $valores = $valores .$campo['id'];
           
            }
            //$vgestor= $vgestor . $valores . ")";
        }else{
            $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }     

        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }   
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE,
    es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablehPeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorhDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorhDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorhDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorhDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorhDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorhDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorhDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorhDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorhDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorhDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorhDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorhDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorhDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY  fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
       
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }else{
	    $cc=explode(",", $valores);
            $query->setParameter('padmin', $cc);//array(15,16,17,18,19,20,21,22,27) 
	}
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        //$Estable = $query->getResult();
        $array = $this->ParametrosAction();
        if($valores=="" and $indicador == 0){
           $array2 = array('entity' => "");
        }else{       
	   $array2 = array('entity' => $Estable);
        }   
    
        $Establecimiento = $query->getResult();
       
        //$municipioDepto = $query->getResult();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($Establecimiento, 'json');
        $response = new JsonResponse();
        $response->setData($Establecimiento);
        return $response;
       
        }   



 
   
public function printAction($gestor, $establecimiento, $periodo) {
        //$request= $this->getRequest();
        //$gestor=$request->request->get('ddl04');
        //$establecimiento=$request->request->get('ddl01');
        //$periodo=$request->request->get('ddl02');
        
        $em = $this->getDoctrine()->getManager(); //
        //VALORES
        $vgestor="";
        $vestablecimiento="";
        $indicador=0;
        if($gestor==0){
            $vgestor=" ";
        }else{
           $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }
        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }
       
       
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE, es.descripcion AS ESTAB_ASIGNADO ,
    fuu.id AS IDU,
    fuu.username AS USUARIO,
    (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablePeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
   
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
   
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
   
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
   
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
   
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,


    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
   
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
   
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
   
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
   
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,

    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12



    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablePeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY   fuu.id, ue.idEstablecimiento';
       
        $ges='Todos los Valores';
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        //$res= $query->getResult();
        $Estable = $query->getResult();
       
        
       
       
       
        $array = $this->ParametrosAction();
        $array2 = array('entity' => $Estable);
        $array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento);
        $array3= $this->ParametrosAction() + $array2 + $array4;
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:print.html.twig', $array3);
    }







public function printhAction($gestor, $establecimiento, $periodo) {
        //$request= $this->getRequest();
        //$gestor=$request->request->get('ddl04');
        //$establecimiento=$request->request->get('ddl01');
        //$periodo=$request->request->get('ddl02');
        
        $em = $this->getDoctrine()->getManager(); //
        //VALORES
        $vgestor="";
        $vestablecimiento="";
        $indicador=0;
        if($gestor==0){
            $vgestor=" ";
        }else{
           $vgestor="WHERE fuu.id=:padmin ";
           $indicador=1;
        }
        if($indicador==0){
            if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" WHERE  ue.idEstablecimiento=:pestablecimiento ";
            }
        }else{
           if($establecimiento==0){
                $vestablecimiento=" ";
            }else{
                $vestablecimiento=" AND  ue.idEstablecimiento=:pestablecimiento ";
            }
        }
$sql='SELECT Distinct(ue.idEstablecimiento) AS IDE, 
      es.descripcion AS ESTAB_ASIGNADO ,
     fuu.id AS IDU,
     fuu.username AS USUARIO,
     (SELECT COUNT(IDE3.id)
        FROM IndicadoresBundle:VariablehPeriodo AS IDE3 WHERE IDE3.periodo=:pperiodo) AS TOTAL_VARIABLES,
    (SELECT count(id2.m1) from IndicadoresBundle:IndicadorhDetalle id2
        WHERE id2.idEstablecimiento = ue.idEstablecimiento AND id2.periodo =:pperiodo AND id2.m1 <> 0) AS m1,
    (SELECT count(id3.m2) from IndicadoresBundle:IndicadorhDetalle id3
        WHERE id3.idEstablecimiento = ue.idEstablecimiento AND id3.periodo =:pperiodo AND id3.m2 <> 0) AS m2,
    (SELECT count(id4.m3) from IndicadoresBundle:IndicadorhDetalle id4
        WHERE id4.idEstablecimiento = ue.idEstablecimiento AND id4.periodo =:pperiodo AND id4.m3 <> 0) AS m3,
    (SELECT count(id5.m4) from IndicadoresBundle:IndicadorhDetalle id5
        WHERE id5.idEstablecimiento = ue.idEstablecimiento AND id5.periodo =:pperiodo AND id5.m4 <> 0) AS m4,  
    (SELECT count(id6.m5) from IndicadoresBundle:IndicadorhDetalle id6
        WHERE id6.idEstablecimiento = ue.idEstablecimiento AND id6.periodo =:pperiodo AND id6.m5 <> 0) AS m5,
    (SELECT count(id7.m6) from IndicadoresBundle:IndicadorhDetalle id7
        WHERE id7.idEstablecimiento = ue.idEstablecimiento AND id7.periodo =:pperiodo AND id7.m6 <> 0) AS m6,
    (SELECT count(id8.m7) from IndicadoresBundle:IndicadorhDetalle id8
        WHERE id8.idEstablecimiento = ue.idEstablecimiento AND id8.periodo =:pperiodo AND id8.m7 <> 0) AS m7,
    (SELECT count(id9.m8) from IndicadoresBundle:IndicadorhDetalle id9
        WHERE id9.idEstablecimiento = ue.idEstablecimiento AND id9.periodo =:pperiodo AND id9.m8 <> 0) AS m8,
    (SELECT count(id10.m9) from IndicadoresBundle:IndicadorhDetalle id10
        WHERE id10.idEstablecimiento = ue.idEstablecimiento AND id10.periodo =:pperiodo AND id10.m9 <> 0) AS m9,  
    (SELECT count(id11.m10) from IndicadoresBundle:IndicadorhDetalle id11
        WHERE id11.idEstablecimiento = ue.idEstablecimiento AND id11.periodo =:pperiodo AND id11.m10 <> 0) AS m10,
    (SELECT count(id12.m11) from IndicadoresBundle:IndicadorhDetalle id12
        WHERE id12.idEstablecimiento = ue.idEstablecimiento AND id12.periodo =:pperiodo AND id12.m11 <> 0) AS m11,
    (SELECT count(id13.m12) from IndicadoresBundle:IndicadorhDetalle id13
        WHERE id13.idEstablecimiento = ue.idEstablecimiento AND id13.periodo =:pperiodo AND id13.m12 <> 0) AS m12
    FROM IndicadoresBundle:User fuu
    JOIN IndicadoresBundle:UsuarioEstablecimiento ue with (ue.idUsuario = fuu.id)
    JOIN IndicadoresBundle:IndicadorhDetalle id with (id.idEstablecimiento= ue.idEstablecimiento and id.periodo=:pperiodo)
    JOIN IndicadoresBundle:VariablehPeriodo vp with (vp.periodo=:pperiodo and vp.id= id.idVariablePeriodo)
    LEFT JOIN IndicadoresBundle:Establecimiento es with (es.idEstablecimiento = ue.idEstablecimiento) 
    '. $vgestor .  $vestablecimiento . '
    ORDER BY fuu.id, ue.idEstablecimiento';
        $ges='Todos los Valores';
        $query = $em->createQuery($sql);
        if($gestor !=0){
           $query->setParameter('padmin', $gestor);
           $objGes = $em->getRepository('IndicadoresBundle:User')->findOneBy(array('id' => $gestor));
           $ges=  $objGes->getUserName();
        }
        $esta="Todos los Valores";
        if($establecimiento !=0){
           $query->setParameter('pestablecimiento', $establecimiento);
           $objEsta = $em->getRepository('IndicadoresBundle:Establecimiento')->findOneBy(array('idEstablecimiento' => $establecimiento));
           $esta=  $objEsta->getDescripcion();
        }
        $query->setParameter('pperiodo', $periodo);
        //$res= $query->getResult();
        $Estable = $query->getResult();
        $array = $this->ParametrosAction();
        $array2 = array('entity' => $Estable);
        $array4 = array('gestor' => $ges,
                        'es' => $esta,
                        'periodo' => $periodo,
                        'vgestor'=> $gestor,
                        'vestable'=>$establecimiento);
        $array3= $this->ParametrosAction() + $array2 + $array4;
        return $this->render('IndicadoresBundle:FichaTecnicaAdmin:print.html.twig', $array3);
    }

	public function tableroAction()
    {
		$array=$this->ParametrosAction();
		return $this->render('IndicadoresBundle:FichaTecnicaAdmin:tablero.html.twig', $array );
	}
	public function tableroPublicoAction()
    {
		$array=$this->ParametrosAction();
		return $this->render('IndicadoresBundle:FichaTecnicaAdmin:tablero_public.html.twig', $array );
	}
    public function ParametrosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $clasificacionUso = $em->getRepository("IndicadoresBundle:ClasificacionUso")->findAll();
        //$Establecimiento = $em->getRepository("IndicadoresBundle:Establecimiento")->findAll();
        //Luego agregar un método para obtener la clasificacion de uso por defecto del usuario
        $usuario = $this->getUser();
        $idUsuario=$usuario->getId();
        $role= $usuario->getRoles();
        $usuarioPermitido= false;
        //in_array("ROLE_USER_TABLERO", $role)
	if ($idUsuario==8) {
          $usuarioPermitido=true;
        }
        //$Establecimientos = $em->getRepository("IndicadoresBundle:UsuarioEstablecimiento")->findBy(array('idUsuario' => $idUsuario));
        $usuarios=$em->getRepository("IndicadoresBundle:User")->findAll();
        $Departamentos = $em->getRepository("IndicadoresBundle:Departamento")->findAll();
	$sql='SELECT es.idEstablecimiento, es.descripcion
                  FROM IndicadoresBundle:Establecimiento es 
                  LEFT JOIN IndicadoresBundle:UsuarioEstablecimiento ues WITH (ues.idEstablecimiento= es.idEstablecimiento) 
                  WHERE  ues.idUsuario = :us';

	//$sql='SELECT es.idEstablecimiento, es.descripcion
         //         FROM IndicadoresBundle:Establecimiento es ';




            $query= $em->createQuery($sql);
            $query ->setParameter('us', $idUsuario);
            //$res= $query->getResult();
            $Establecimiento=$query->getResult();
	     //Validar si tiene sucursal asignada
             //if (!empty($Establecimiento)) {
             //   $val = ($Establecimiento[0]['idEstablecimiento']);
             //} 

	  $sqlxx="SELECT f3.id, f3.username  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='GESTOR'";
            $usuariosf = $em->getConnection()->executeQuery($sqlxx)->fetchAll();


	    $sqlxx2="SELECT f3.id, f3.username  FROM fos_user_user_group f1
                 LEFT JOIN fos_user_group f2 on f1.group_id= f2.id
                 LEFT JOIN fos_user_user f3 on f1.user_id= f3.id
                 WHERE 
                 f2.name='HOSP'";
            $usuariosh = $em->getConnection()->executeQuery($sqlxx2)->fetchAll();
       
             if ($usuario->getClasificacionUso()) {
            $clasificacionUsoPorDefecto = $usuario->getClasificacionUso();
        } else {
            $clasificacionUsoPorDefecto = $clasificacionUso[0];
        }
        $categorias = $em->getRepository("IndicadoresBundle:ClasificacionTecnica")->findBy(array('clasificacionUso' => $clasificacionUsoPorDefecto));

        //Salas por usuario
        $usuarioSalas = array();
        if (($usuario->hasRole('ROLE_SUPER_ADMIN'))){
            foreach ($em->createQuery('SELECT g FROM IndicadoresBundle:GrupoIndicadores g ORDER BY g.nombre ASC')->getResult() as $sala) {
                $usuarioSalas[$sala->getId()] = $sala;
            } 
        }else{
           foreach ($usuario->getGruposIndicadores() as $sala) {
                $usuarioSalas[$sala->getGrupoIndicadores()->getId()] = $sala->getGrupoIndicadores();
            } 
        }
		
		$salasXusuario=array();
		$i=0;
		foreach ($usuarioSalas as $sala) {
            $salasXusuario[$i]['datos_sala'] = $sala;
            $salasXusuario[$i]['indicadores_sala'] = $em->getRepository('IndicadoresBundle:GrupoIndicadores')
                    ->getIndicadoresSala($sala);
            $i++;
        }
        //Salas asignadas al grupo al que pertenece el usuario
		$salasXgrupoTemp=array();
        foreach ($usuario->getGroups() as $grp){
            foreach ($grp->getSalas() as $sala){
                $usuarioSalas[$sala->getId()] = $sala;
				$salasXgrupoTemp[]=$sala;
            }
        }
        $salasXgrupo=array();
		$i=0;
		foreach ($salasXgrupo as $sala) {
            $salasXgrupo[$i]['datos_sala'] = $sala;
            $salasXgrupo[$i]['indicadores_sala'] = $em->getRepository('IndicadoresBundle:GrupoIndicadores')
                    ->getIndicadoresSala($sala);
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
                $this->get('doctrine')->getManager()->createQuery('SELECT c FROM IndicadoresBundle:FichaTecnica c ORDER BY c.nombre ASC')->getResult() :
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
		
		return array(
                    'usuario'=>$usuario,
		    'usuarios'=>$usuarios,
		    'usuariosf'=>$usuariosf,
		    'usuariosh'=>$usuariosh,
		    'usuarioPermitido'=>$usuarioPermitido, 
                    'departamento'=>$Departamentos, 
                    'establecimiento'=>$Establecimiento,
                    'categorias' => $categorias_indicador,
                    'clasificacionUso' => $clasificacionUso,
                    'salas' => $salas,
					'salasXusuario' => $salasXusuario,
					'salasXgrupo' => $salasXgrupo,
					'admin_pool' => $this->get('sonata.admin.pool'),
                    'indicadores_no_clasificados' => $indicadores_no_clasificados);		        
    }    
}
