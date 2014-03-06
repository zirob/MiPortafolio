    <style>
	.foo
	{
		border:1px solid #09F;
		background-color:#FFFFFF;
		color:#000066;
		font-size:11px;
		font-family:Tahoma, Geneva, sans-serif;
		text-align:center;
	}
	
	</style>
    
<form action="?cat=3&sec=10&action=<?=$action; ?>" method="POST">
     <table  id="detalle-prov"  cellpadding="5" cellspacing="6" border="0" width="90%" align="center">
    <tr >
      <td style="text-align:center;" colspan="3">  <a href="?cat=3&sec=7"><img src="img/view_previous.png" width="36px" height="36px" border="0" style="float:right;" class="toolTIP" title="Volver al Listado de Facturas de Petroleo"></</a></td></tr>
    <tr>
    </tr>
    <tr>
        <td colspan="3"><label>Fecha Fac. Petroleo:</label><label style="color:red;">(*)</label>
        <input type="date" name="fecha" value='<? echo $_POST['fecha']; ?>' class="foo" /></td>
    </tr>   
    </table>
    <table border="0"  width="90%" align="center">
    <tr>
        <td style="text-align:right"><label>Num. Factura:</label></td><td><input  class="foo" type="text" name="num_factura" value="<?=$_POST['num_factura'];?>"></td>
        
        <td style="text-align:right"><label>Valor Factura:</label></td><td><input class="foo" type="text" name="valor_factura" value="<?=$_POST['valor_factura'];?>"></td>
        
        <td style="text-align:right"><label>Litros:</label></td><td><input    class="foo" type="text" name="litros" value="<?=$_POST['litros'];?>"></td>
    </tr>
    <tr>
        <td style="text-align:right"><label>Valor IEV:   </label></td><td><input class="foo"  type="text" id="valor_iev" name="valor_iev"  value='<? echo $_POST['valor_iev']; ?>' /></td>
        
        <td style="text-align:right"><label>Valor IEF:   </label><label style="color:red;">(*)</label></td><td><input class="foo"  type="text" id="valor_IEV" name="valor_IEV"  value='<? echo $_POST['valor_IEV']; ?>'> </td>
        
        <td style="text-align:right"><label>Total IEF:</label></td><td><input class="foo"  type="text" id="total_IEF" name="total_IEF"  value='<? echo $_POST['total_IEF'];?>'></td>
    </tr>
    <tr>
        <td style="text-align:right"><label>Litros Utilizados:</label></td><td><input type="text" name="litros_utilizados" class="foo" value="<?=$_POST['litros_utilizados'];?>" readonly="readonly"></td>
        
        <td style="text-align:right"><label>Total IE Utilizado:</label></td><td><input type="text" name="total_ie_util"  class="foo" readonly="readonly" value="<?=$_POST['total_ie_util'];?>"></td>
        <td></td>
    </tr>
    </table>
    <table border="0"  width="100%">
    <tr>
        <td colspan="3"><br />
             <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr >
                    <td id="titulo_tabla" style="text-align:center;" colspan="2">  Destinacion Procesos Productivos</td>
                    <td id="titulo_tabla" style="text-align:center;" colspan="2">  Destinacion Vehiculos Transporte</td>
                </tr>
                <tr>
                    <td><label>Litros:</label><br/><input type="text" size="4" name="dest_pp_litros" readonly="readonly" value="<?=$_POST['dest_pp_litros']; ?>" class="foo"></td>
                    
                    <td><label>IE Recuperable:</label><br/><input size="20" type="text" name="ief_pp_recuperable" readonly="readonly" value="<?=$_POST['ief_pp_recuperable'];?>" class="foo"></td>
                    
                    <td><label>Litros:</label><br/><input type="text" size="4" name="dest_vt_litros" readonly="readonly" value="<?=$_POST['dest_vt_litros'];?>" class="foo"></td>
                    
                    <td><label>IE No Recuperable:</label><br/><input size="20" type="text" name="ief_no_recuperable" readonly="readonly" value="<?=$_POST['ief_no_recuperable'];?>" class="foo"></td>
                </tr>   
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table style="width:900px;" id="detalle-prov" border="0" cellpadding="3" cellspacing="4">
                <tr>
                    <td width="130px;"></td>
                    <td><label>Saldo Litros:</label><br/><input type="text" name="saldo_litros" readonly="readonly" value="<?=$_POST['saldo_litros'];?>"  class="foo"></td>
                    <td><label>Saldo IE:</label><br/><input type="text" name="saldo_ief" readonly="readonly" value="<?=$_POST['saldo_ief'];?>"  class="foo"></td>
                    <td width="130px;"></td>
                </tr>
            </table>   
        </td>
        
        
    </tr>
    <tr>
        <td style="text-align: right;" colspan="3"><input type="submit" value="Grabar"></td>
    </tr>
</table>
</form>
