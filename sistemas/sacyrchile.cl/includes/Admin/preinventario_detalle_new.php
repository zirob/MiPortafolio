<script>
// function Desactivar(){
//         document.getElementById('codbarra_1').disabled = true;
// }

function ValidaSoloNumeros() {
   if ((event.keyCode < 48) || (event.keyCode > 57)) 
      event.returnValue = false;
}

function validar(codbar_productonew,rut,fila,id_pi,user,cant,id_detpi)
{   
    //alert(codbar_productonew);
    result=$("#resultado");
    result.html("cargando...")
    $.get("buscador2.php",{ codbar_productonew:codbar_productonew,rut:rut,fila:fila,id_pi:id_pi,user:user,cant:cant,id_detpi:id_detpi})
    .success(function(data){ result.html(data)})
    .error(function(a,e){ result.html(e)});
}
</script>

<script language="JavaScript">
function A(e,t)
{
var k=null;
(e.keyCode) ? k=e.keyCode : k=e.which;
if(k==13) (!t) ? B() : t.focus();
}
function B()
{
document.forms[0].submit();
return true;
}
</script>

<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Productos"></a></td></tr>
    
    <tr>
</table>

  <style>
    a:hover{
        text-decoration:none;
    }

    .fo
    {
        border:1px solid #09F;
        background-color:#FFFFFF;
        color:#000066;
        font-size:12px;
        font-family:Tahoma, Geneva, sans-serif;
        width:80%;
        text-align:center;
    }
</style> 

<form action="?cat=3&sec=40&action=<?=$_GET["action"]; ?>&id_pi=<?=$_GET["id_pi"];?>" method="POST" id="f1" name="f1">
	<div id='resultado' style="display:none">
    </div>
        <table style="width:800px; border-collapse:collapse; font-size:12px; text-align:center;" align='center'  border="1" cellpadding="3" cellspacing="3" >

     	<tr style="background-color:rgb(0,0,255); color:rgb(255,255,255); font-family:Tahoma; font-size:13px;">
                    
            <td width="5%"><b>#</b></td>
            <td width="70%"><b>Descripci&oacute;n</b></td>
            <td width="15%"><b>Codigo Barras</b></td>
            <td width="10%"><b>Cantidad</b></td>
            <!-- <td width="5%"><b>Eliminar</b></td> -->
            <input  type='hidden' name="cantidad" value='<? echo $_POST['cantidad'];?>'/>
        </tr>
                
<style>
        .detalle
        {
            text-align:center; 
            background-color:#EBEBEB;
            border:1px solid #666;
        }
</style>


<?
//var_dump($_POST);
if (empty($_POST['num_prd'])) {
        $_POST['num_prd'] = 30;
        $i = $_POST['num_prd'];
        $_POST["EstadoItem_".$i] = 1;

}else{
    $_POST['num_prd'] = $_POST['num_prd']+30;
}


for ($f = 1; $f <= $_POST["num_prd"]; $f++) {
        if (!empty($_POST["Eliminar_".$f])) {
            $_POST["EstadoItem_".$f] = 29;
            // $_POST["subtotal"] = $_POST["subtotal"] - $_POST["total_".$f];
        }
}

if (!empty($_POST['Agregar'])) {
       $_POST['num_prd']++;
       $i = $_POST['num_prd'];
       $_POST["EstadoItem_".$i] = 1;
}

$j=0;
$k=1;
for ($i=1; $i <= $_POST["num_prd"]; $i++) 
{
                   // echo "<br>i : ".$i;
                   // echo "<br>num_prd : ".$_POST["num_prd"];
                   // echo "<br>EstadoItem_".$i." : ".$_POST["EstadoItem_".$i];
        if(empty($_POST["cantidad_".$i])) $_POST["cantidad_".$i]=1;
            $_POST["EstadoItem_".$i]=1;

        if ($_POST["EstadoItem_".$i] == 1) {

            if(!empty($_POST["codbarra_".$i])){
                $readonly = "readonly";
            }else{
                $readonly = "";
            }
            
            $j++;
            $k++;
            echo "<tr>";
            echo "<td>".$j."</td>";
            echo "<td><input type='text' name='descripcion_".$i."' value='".$_POST['descripcion_'.$i]."' class='fo' style='text-align:left;width:95%;'  readonly></td>";
            echo "<td><input type='text' id='codbarra_".$i."' name='codbarra_".$i."'    value='".$_POST['codbarra_'.$i]."' onKeyPress='ValidaSoloNumeros()' onKeyDown='A(event,document.f1.codbarra_".$k.");'  class='fo' style='text-align:center'  onchange='validar(document.f1.codbarra_".$i.".value,\"".$_SESSION['empresa']."\",".$i.", \"".$_GET['id_pi']."\", \"".$_SESSION["user"]."\", \"\")'  ".$readonly."></td>";
            echo "<input type='hidden' name='id_det_pi_".$i."' value='".$_POST['id_det_pi_'.$i]."'>";
            echo "<td><input type='text' id='cantidad_".$i."' name='cantidad_".$i."' value='".$_POST['cantidad_'.$i]."' onKeyPress='ValidaSoloNumeros()' onKeyDown='A(event,document.f1.codbarra_".$k.");' class='fo' style='text-align:left;' onchange='validar(document.f1.codbarra_".$i.".value,\"".$_SESSION['empresa']."\",".$i.", \"".$_GET['id_pi']."\", \"".$_SESSION["user"]."\", document.f1.cantidad_".$i.".value, document.f1.id_det_pi_".$i.".value)' ".$readonly."></td>";
            // echo "<td><button name='Eliminar_".$i."' value='Eliminar_".$i."' ";if($estado==3)echo "Disabled";echo "><img src='img/borrar.png' width='16' height='16' /> </button></td>";
                    
            echo "<input type='hidden' name='LineaVisible_".$i."' value='".$j."'>";
            echo "<input type='hidden' name='EstadoItem_".$i."' value='".$_POST["EstadoItem_".$i]."'>";
            echo "</tr>";
                        
        }else{

            // echo "<input type='hidden' name='id_det_sc_".$i."' value='".$_POST['id_det_sc_'.$i]."' >";
            echo "<input type='hidden' name='EstadoItem_".$i."' value='0'>";
            // echo "<input type='hidden' name='total_".$i."'  value='0'>";
            // echo "<input type='hidden' name='unidad_".$i."'  value='0'>";
            // echo "<input type='hidden' name='cantiddad_".$i."'  value='0'>";
        }
                
        $_POST["subtotal"] += $_POST["total_".$i];                       
                        
}
echo "<tr>";
echo "<td colspan='4' align='right'>";
// echo "<button type='submit' name='AgregarFilas' value='".$_POST["AgregarFilas"]."'><img src='img/add1.png' width='20' height='20' class='toolTIP' title='Agregar Filas al Pre-Inventario' /></button>";
echo "</td>";
echo "</tr>";

$_POST["valor_neto"] = $_POST["subtotal"] - $_POST["descuento"];
$_POST["iva"] = ($_POST["valor_neto"] * 0.19);
$_POST["total_doc"] = ($_POST["valor_neto"] + $_POST["iva"]);


?>
       <input type='hidden' name='num_prd' value='<?=$_POST["num_prd"];?>'>
        
       	<!-- <tr>
	        <td  colspan="5" style="text-align: right;">
	            <div style="width:100%; height:auto; text-align:right;">
	                        <button style="background-color:#006; color:#fff; font-size:12px; font-family:Tahoma, Geneva, sans-serif; margin-right:5px; width:100px; height:25px; border-radius:0.5em;"  type="submit" name='accion'
	                        <?/*

	                        if($_GET["action"]==1)
	                        {
	                            echo  " value='guardar' >Guardar</button>";
	                        }
	                        if($_GET["action"]==2)
	                        {
	                            echo " value='editar' >Actualizar</button>";
	                        }*/
	                        ?>

	                    </div>
	                    <input  type="hidden" name="primera" value="1"/>


	        </td>
    	</tr> -->
    	<!-- <tr>
                <td colspan="6" style='text-align:Center;text-align:center;font-family:tahoma;;color:red;font-size:15px;font-weight:bold;' >
                  (*) Campos de Ingreso Obligatorio.
            </td>
    </tr> -->
 	</table>
 </form>    
<table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="2">   <a href="?cat=3&sec=39&id_pi=<?=$_GET['id_pi'];?>"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Productos"></a></td></tr>
    
    <tr>
</table>
 