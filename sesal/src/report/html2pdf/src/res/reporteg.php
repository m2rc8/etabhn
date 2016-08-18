<style type="text/Css">
<!--
#body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	font-weight:400;
	color:#000;
        Text-transform: uppercase;
}
.small_font{
	font-size:8px;
	color:#666;
}
.bg_img{
	background-color:#F5ECCE;
	border-radius:5px;
	height:50px;
}
.linea{
	border-width:1px;
	border-bottom:solid;
	border-color:#666;
}
.txt_title{
	font-size:16px;
	font-weight:bold;
}
.txt_titleheader{
	font-size:13px;
	font-weight:bold;
	color:#333;
}
.txt_titleheader_cotenido{
	font-size:10px;
}
-->
</style>
<page backtop="30mm" backimg="" backbottom="10mm" backleft="0mm" backright="0mm" pagegroup="new" style="font-size: 11px; text-transform: uppercase;">
<page_header>
    <table style="width:100%;" border="0" align="center" cellpadding="10" cellspacing="0">
     <tr>
         
         <td align="center" class="txt_titleheader">Reporte Gestores - Variables</td>
  </tr>
  <tr>
      <td  align="center" class="txt_titleheader"><label></label>
          <label> </label></td>
  </tr>
 </table> 
</page_header>
    
<page_footer>
  
</page_footer>
    <div style="border-width:1px; border-style:solid; border-color:#CCC;width:95%;">
    <table align="center" border="0" cellpadding="2" cellspacing="1" style="width:100%;">
  <tr>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:16%;">GESTOR</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:17%;">UNID ASIG.</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:5%;">TOTAL RESP.</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:5%;">TOTAL VAR.</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">ENE</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">FEB</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">MAR</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">ABR</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">MAY</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">JUN</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">JUL</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">AGO</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">AG</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">SEPT</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">OCT</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">NOV</div></td>
      <td  bgcolor="#99E6FF" align="left" class="txt_titleheader"><div style="width:4%;">DIC</div></td>
  </tr>
<?php
   $matriz  = explode("*-*", $_REQUEST["entity"]);
   foreach ($matriz as $campo) {
            $matrizI  = explode(":-:", $campo);
             echo("<tr>".
                  "<td align='left'>". $matrizI[1] . "</td>".
                  "<td align='left'>". $matrizI[3] . "</td>".
                  "<td align='center'>". $matrizI[4] . "</td>".
                  "<td align='center'>". $matrizI[5] . "</td>".
                  "<td align='center'>". $matrizI[6] . "</td>".
                  "<td align='center'>". $matrizI[7] . "</td>".
                  "<td align='center'>". $matrizI[7] . "</td>".
                  "<td align='center'>". $matrizI[8] . "</td>".
                  "<td align='center'>". $matrizI[9] . "</td>".
                  "<td align='center'>". $matrizI[10] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
                  "<td align='center'>". $matrizI[11] . "</td>".
              "</tr>");
            
   }
   ?>
</table><br/>  
    </div><br/>
</page>
