<?php

    // SI LA PÁGINA VIENE DE UN POSTBACK SE CARGAN LOS USUARIOS SEGÚN LOS CRITERIOS DE BÚSQUEDAS
    if(isset($_GET['filtro']) && $_GET['filtro']==1)
    {
        $qry = "SELECT usuario, nombre, cargo, depto, Jefatura, fecha_ingreso FROM usuarios u inner join empresa e on u.rut_empresa = e.rut_empresa WHERE u.rut_empresa = '".$_SESSION['empresa']."'";
    
	    // SI SE BUSCA POR EL CRITERIO DE USUARIO
	    if(!empty($_POST['usuario']) && $_POST['usuario']!="")
	    { 
			//echo " buscado por usuario  <p/>";               
            $qry .= " and usuario like '%".$_POST['usuario']."%'";
        }
	
	    // SI SE BUSCA OOR EL CRITERIO DE NOMBRE
        if(!empty($_POST['nombre']) && $_POST['nombre']!="")
	    {
		    //echo " buscado por nombre  <p/>";
            $qry .= " and nombre like '%".$_POST['nombre']."%'";
        }
    
	    // SI SE BUSCA POR EL CRITERIO DE CARGO
        if(!empty($_POST['cargo']) && $_POST['cargo']!="")
	    {
		    //echo " buscado por cargo <p/>";
            $qry .= " and cargo like '%".$_POST['cargo']."%'";
        }
	
	    // SI SE BUSCA POR EL CRITERIO DE DEPARTAMENTO
        if(!empty($_POST['departamento']) && $_POST['departamento']!="")
	    {
		    //echo " buscado por departamento:  ". $_POST['departamento']. "<p/>";
            $qry .= " and depto like '%".$_POST['departamento']."%'";
        }
		
		// SI SE BUSCA POR EL CRITERIO DE JEFATURA
        //if(!empty($_POST['Jefatura']) && $_POST['Jefatura']!="")
		if(!empty($_POST['jefatura']))
	    {
		     //echo " buscado por Jefatura:  '".$_POST['Jefatura']."' <p/>";
			 //echo " buscado por Jefatura:  <p/>";
			//$a = $_POST['Jefatura'].selected;
			//echo "el valor es: $a";
            $qry .= " and jefatura like '%".$_POST['Jefatura']."%'";
        }
       
        $qry.=" ORDER BY usuario";
    }

    // SI SE CARGA RECIEN LA PÁGINA SE LISTAN TODOS LOS USAURIOS
    else
    {
        $qry = "SELECT usuario, 
		               nombre, 
		               cargo, 
					   depto, 
					   Jefatura, 
					   fecha_ingreso 
			    FROM usuarios u 
				inner join empresa e on u.rut_empresa = e.rut_empresa 
				WHERE u.rut_empresa = '".$_SESSION['empresa']."' ORDER BY usuario";
    }

    $res = mysql_query($qry,$con);

?>

<table id="list_registros">
    
	<!-- AGREGAR USUARIO -->
    <tr>
        <td id="titulo_tabla" style="text-align:center;" colspan="6">  </td>
        <td id="list_link" >
		    <a href="?cat=2&sec=5">
			    <img src="img/user1_add.png" width="36px" height="36px" border="0" class="toolTIP" 
				     title="Agregar Usuario">
			</</a>
		</td>
    </tr>
	
	<!-- FILTRO DE BÚSQUEDA -->
    <form action="?cat=2&sec=4&filtro=1" method="POST">
    <tr id="titulo_reg" style="background-color: #fff;">
        <td width="20px">Filtro:</td>
        <td><input type="text" name="usuario" class="fo"></td>
        <td><input type="text" name="nombre" class="fo"></td>
        <td><input type="text" name="cargo" class="fo"></td>
        <td><input type="text" name="departamento" class="fo"></td>
		<td>
		    <select name="jefatura" class="foo">
			    <option value="0">Seleccione</option>
			    <option value="1">Depto. Compras</option>
				<option value="2">Jefe de Compras</option>
				<option value="3">Jefe Parque Maquinarias</option>
				<option value="4">Jefe Grupo Obras</option>
				<option value="5">Gerente General</option>
			</select>
		</td>
        <td colspan="2"><input type="Submit" value="Filtrar"></td>
    </tr>
    </form>
	
	<!-- ENCABEZADO DE LA TABLA DE RESULTADOS -->
    <tr id="titulo_reg">
        <td width="30px;" style="text-align: center;">#</td>
        <td style="text-align: center;">Usuario</td>
        <td style="text-align: center;">Nombre</td>
        <td width="150px;" style="text-align: center;">Cargo</td>
        <td width="150px;" style="text-align: center;">Departamento</td>
		<td width="100px;" style="text-align: center;">Jefatura</td>
        <td width="100px;" style="text-align: center;">Fec. Ingreso</td>
        <td width="50px;" style="text-align: center;">Editar</td>
    </tr>    
    
<?
    // LISTADO DE USUARIOS
    if(mysql_num_rows($res)!=null)
	{
        $i=1;
        while($row = mysql_fetch_assoc($res))
		{
?>
            <tr class="listado_datos">
                <td style="text-align: center;"><? echo $i; $i++; ?></td>
                <td style="text-align: center;"><?=utf8_encode($row['usuario']); ?></td>
                <td style="text-align: center;"><?=utf8_encode($row['nombre']); ?></td>
                <td style="text-align: center;"><?=$row['cargo']; ?></td>
                <td style="text-align: center;"><?=$row['depto']; ?></td>
<?	
				if ($row['Jefatura']=="")
				{
?>
                    <td style="text-align: center;"> - </td>
<?	
				}
				else
				{
?>
                    <td style="text-align: center;">
					    <? 
						    switch ($row['Jefatura'])
							{
							    case 1:
								    echo "Dpto. Compras";
								case 2:
								    echo "Jefe de Compras";
								case 3:
								    echo "Jefe Parque Maquinarias";
								case 4:
								    echo "Jefe Grupo Obras";
								case 5:
								    echo "Gerente General";
									break;
							}
						?> 
					</td>
<?
				}
?>
				
                <td width="100px" style="text-align: center;"><?=date("d-m-Y",strtotime($row['fecha_ingreso']));?></td>
                <td style="text-align: center;">
				    <a href="?cat=2&sec=5&action=2&user=<?=$row['usuario'];?>">
					    <img src="img/edit.png" width="24px" height="24px" border="0" class="toolTIP" title="Editar Usuario">
					</a>
				</td>
            </tr>


<?php 
        }
    }
	
	// MENSAJE INDICANDO QUE NO EXISTEN USUARIOS
	else
	{
?>
        <tr  id="mensaje-sin-reg">
		    <td colspan="7">No existen Usuarios para ser Desplegados</td>
		</tr>
<? 
    }
?>
</table>

    