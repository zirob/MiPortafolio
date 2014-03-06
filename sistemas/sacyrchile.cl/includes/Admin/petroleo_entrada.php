<script type="text/javascript">
function ValidaSoloNumeros() {
 if ((event.keyCode < 48) || (event.keyCode > 57)) 
  event.returnValue = false;
}
</script>

<?php


if(empty($_POST["filtrar"])){
  $_POST['anio'] = date("Y");
  $_POST['mes'] = date("m");
}

$sql ="SELECT * FROM petroleo WHERE rut_empresa='".$_SESSION['empresa']."'";

if(isset($_POST['mes']) && $_POST['mes']!="" ){
    if($_POST['mes']==13)  $MesEsp = $_POST['mes'] - 5;//Agosto
    if($_POST['mes']==14) $MesEsp = $_POST['mes'] - 5;//Septiembre
    if(!empty($MesEsp)){
      $sql.=" and mes='".$MesEsp."'"; 
    }else{
      $sql.=" and mes='".$_POST['mes']."'"; 
    }
  }else{
   $sql.=" and mes='".date('m')."'";
 }

 if(isset($_POST['anio']) && $_POST['anio']!=""){
   $sql.=" and agno='".$_POST['anio']."'"; 
 }else{
   $sql.=" and agno='".date('Y')."'";
 }

 $sql.="  ORDER BY agno,mes,dia";
 $sql.= " ".$limit;


// var_dump($_POST);
 $res = mysql_query($sql,$con);

 if(isset($error) && !empty($error)){
  ?>
  <div id="main-error"><? echo $error;?></div>
  <?
}elseif($msg){
  ?>
  <div id="main-ok"><? echo $msg;?></div>
  <?
}
// echo "<br>mes =".$_POST['mes'];
?>


</style>

<table id="list_registros" >
  <tr>
   <td id="list_link" colspan="9"><a href="?cat=3&sec=10&action=1"><img src="img/add1.png" width="36px" height="36px" border="0" class="toolTIP" title="Agregar Factura Petroleo"></a></td></tr>
 </tr>
</table>

<style>
.fo
{
  border:1px solid #09F;
  background-color:#FFFFFF;
  color:#000066;
  font-size:11px;
  font-family:Tahoma, Geneva, sans-serif;
  width:40%;
  text-align:center;
}
/* -------------------------------------------- */
/* ----------- Pagination: Digg Style --------- */
/* -------------------------------------------- */
ul    { border:0; margin:0; padding:0; }
#pagination-digg li          { border:0; margin:0; padding:0; font-size:11px; list-style:none; /* savers */ float:left; }
#pagination-digg a           { border:solid 1px #9aafe5; margin-right:2px; }
#pagination-digg .previous-off,
#pagination-digg .next-off   { border:solid 1px #DEDEDE; color:#888888; display:block; float:left; font-weight:bold; margin-right:2px; padding:3px 4px; }
#pagination-digg .next a,
#pagination-digg .previous a { font-weight:bold; }
#pagination-digg .active     { background:#2e6ab1; color:#FFFFFF; font-weight:bold; display:block; float:left; padding:4px 6px; /* savers */ margin-right:2px; }
#pagination-digg a:link,
#pagination-digg a:visited   { color:#0e509e; display:block; float:left; padding:3px 6px; text-decoration:none; }
#pagination-digg a:hover     { border:solid 1px #0e509e; }
</style>

<table id="list_registros" border="1" style="border-collapse:collapse;" >
  <form action="?cat=3&sec=13" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
      <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>Filtro:</label></td>
      <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>Mes:&nbsp;&nbsp;</label>
        <!-- <input type="text" size="2" name="mes" class="fo"> -->
        <select name="mes" <? if($estado==3){ echo "Disabled"; }?> class='fo' >
          <option value="0" <? if($_POST['mes']==0) echo " selected "; ?> >---</option>  
          <option value="01" <? if($_POST['mes']==01){ echo " selected ";} ?> >Enero</option>
          <option value="02" <? if($_POST['mes']==02){ echo " selected ";} ?> >Febrero</option>
          <option value="03" <? if($_POST['mes']==03){ echo " selected ";} ?> >Marzo</option>
          <option value="04" <? if($_POST['mes']==04){ echo " selected ";} ?> >Abril</option>
          <option value="05" <? if($_POST['mes']==05){ echo " selected ";} ?> >Mayo</option>
          <option value="06" <? if($_POST['mes']==06){ echo " selected ";} ?> >Junio</option>
          <option value="07" <? if($_POST['mes']==07){ echo " selected ";} ?> >Julio</option>

          <option value="13" <?  if($_POST['mes'] == 13){ echo " selected ";} ?> >Agosto</option>
          <option value="14" <? if($_POST['mes']==14){ echo " selected ";} ?> >Septiembre</option>

          <option value="10" <? if($_POST['mes']==10){ echo " selected ";} ?> >Octubre</option>
          <option value="11" <? if($_POST['mes']==11){ echo " selected ";} ?> >Noviembre</option>
          <option value="12" <? if($_POST['mes']==12){ echo " selected ";} ?> >Diciembre</option>
        </select>
      </td>

      <td colspan="2" style="font-family:Tahoma; font-size:12px;text-align:center;"><label>A&ntilde;o:&nbsp;&nbsp;</label>
        <input type="text" size="2" name="anio" value='<?=$_POST["anio"];?>' class="fo" onKeyPress="ValidaSoloNumeros()">
      </td>
      <td></td>
      <td colspan="2" style="font-family:Tahoma; font-size:12px; text-align:right;">
        <input type="submit" value="Filtrar" style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:10px; width:90px; height:20px; border-radius:0.5em;"><input type='hidden' name='filtrar' value='filtrar' >
      </td>

    </tr>
  </form>

  <tr id="titulo_reg" style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:12px;">
    <td  style="text-align:center;">#</td>
    <td  style="text-align:center;">Dia</td>
    <td  style="text-align:center;">N° Factura</td>
    <td  style="text-align:center;">Litros</td>
    <td  style="text-align:center;">Valor IEF.</td>
    <td  style="text-align:center;">Valor IEV.</td>
    <td  style="text-align:center;">Total IEF.</td>
    <td width="60px"  style="text-align:center;">Ver</td>
    <td width="60px"  style="text-align:center;">Editar</td>
  </tr>    

  <?
  if(mysql_num_rows($res)!=null){
    $i=1;
    while($row = mysql_fetch_assoc($res)){
      ?>
      <tr  style="font-size:11px; font-family:Tahoma, Geneva, sans-serif; background-color:rgb(243,243,243);" id="row">
        <td style="text-align: center;"><?echo $i;$i++;?></td>
        <td style="text-align: center;"><?=$row['dia'];?></td>
        <td style="text-align: center;"><?=$row['num_factura']; ?></td>
        <td style="text-align: center;"><?=number_format($row['litros'],0,'','.'); ?></td>
        <td style="text-align: right;"><?=number_format($row['valor_IEF'],4,',','.'); ?></td>
        <td style="text-align: right;"><?=number_format($row['valor_IEV'],4,',','.'); ?></td>
        <td style="text-align: right;"><?=number_format($row['total_IEF'],0,',','.'); ?></td>
        <td style="text-align: center;"><a href="?cat=3&sec=11&id_petroleo=<?=$row['dia']."-".$row['mes']."-".$row['agno'];?>"><img src="img/view.png" width="24px" height="24px" class="toolTIP" title="Ver salidas de petroleo"></a></td>
        <td style="text-align: center;"><a href="?cat=3&sec=10&action=2&id_petroleo=<?=$row['dia']."-".$row['mes']."-".$row['agno'];?>"><img src="img/edit.png" width="24px" height="24px" class="toolTIP" title="Editar factura de Petroleo"></a></td>
      </tr>


      <?php 
    }

  }else{
    ?>
    <tr  id="mensaje-sin-reg" style="color:rgb(255,255,255); font-family:Tahoma, Geneva, sans-serif; ; font-size:12px;">
      <td colspan="9">No existen Facturas de Petroleo para ser Desplegadas</td>
    </tr>
    <? 
  }
  ?>
</table>


