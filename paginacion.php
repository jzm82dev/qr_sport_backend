
	<table width="95%" border="0" cellspacing="0" cellpadding="1" align="center" >
		<tr > 
			<td width="30%" align="center" valign="middle" colspan="5" height="20px;" style="text-align:right; ">
	<?php		$cadena_paginacion = '';
				if ($_SESSION["limit"] > 0) { ?>		
				<A href="fcontenido.php?limite=0&pagina=<?php echo $_SESSION["sesion_pagina"];?>"><img src="imagenes/primero.png" style="vertical-align:middle; border:0; " alt="Primero" title="Primero"></A>		
				<A href="fcontenido.php?limite=<?php echo ($_SESSION["limit"]-$_REGISTROS_POR_PAGINA);?>&pagina=<?php echo $_SESSION["sesion_pagina"];?>"><img src="imagenes/anterior.png" style="vertical-align:middle; border:0; " alt="Anterior" title="Anterior"></A>&nbsp;
	<?php		} else {?>			
				<img src="imagenes/primero_back.png" style="vertical-align:middle; border:0; " alt="Primero" title="Primero">	
				<img src="imagenes/anterior_back.png" style="vertical-align:middle; border:0; " alt="Anterior" title="Anterior">&nbsp;
		<?php	}	?>
			</td>			
			<td width="40%" align="center" valign="middle" colspan="5" height="20px;">								
		<?php				
			$pagina_actual=($_SESSION["limit"]/$_REGISTROS_POR_PAGINA)+1;
			
			if(($num_registros%$_REGISTROS_POR_PAGINA)!=0)			
				$num_total_paginas = floor($num_registros/$_REGISTROS_POR_PAGINA)+1;
			else
				$num_total_paginas = floor($num_registros/$_REGISTROS_POR_PAGINA);
							
			if($num_total_paginas<10)
				$fin_paginacion = $num_total_paginas;
			elseif($pagina_actual>6)
			{
				if(($pagina_actual+5)>$num_total_paginas)
					$fin_paginacion = $num_total_paginas;				
				else
					$fin_paginacion = ($pagina_actual+5);
			}
			else
				$fin_paginacion = 10;				

			if($pagina_actual<7)
				$inicio_paginacion=1;
			elseif($pagina_actual>6)				
				$inicio_paginacion = $fin_paginacion-9;
			
			if($inicio_paginacion<=0)
				$inicio_paginacion=1;
				
			if($inicio_paginacion!=1)
				$cadena_paginacion.="... ";

			$num_min = ($_SESSION["limit"]+1);
			$num_max = ($_SESSION["limit"]+$_REGISTROS_POR_PAGINA);
						
			for($z=$inicio_paginacion;$z<($fin_paginacion+1);$z++)
			{
				$limite_tmp = ($z*$_REGISTROS_POR_PAGINA)-$_REGISTROS_POR_PAGINA;			
							
				$cadena_paginacion.='<A href="fcontenido.php?limite='.$limite_tmp.'&pagina='.$_SESSION["sesion_pagina"].'"';

				if(($limite_tmp+1)>=$num_min && ($limite_tmp+1)<=$num_max)
					 $cadena_paginacion.='style="border:2px solid #cccccc;"';
					 
				$cadena_paginacion.='><font color="#000000">'.$z.'</font></A>';						
				if($z!=$fin_paginacion)
					$cadena_paginacion.="-";
			} 
			
			if($fin_paginacion<$num_total_paginas)
					$cadena_paginacion.=" ...";					
?>
			<?php echo $cadena_paginacion;?><br>
			<font style="font-size:9px; color:#000000; ">(<?php  
							
				if($num_max>$num_registros)
					$num_max = $num_registros;
					
				echo $num_min.'-'.$num_max.' de '.$num_registros; 
			?>)</font>
			</td>								
			<td width="30%" align="center" valign="middle" colspan="5" height="20px;" style="text-align:left; ">					
		<?php					
			if ($num_registros > ($_SESSION["limit"]+$_REGISTROS_POR_PAGINA))	{ ?>
				&nbsp;<A href="fcontenido.php?limite=<?php echo ($_SESSION["limit"]+$_REGISTROS_POR_PAGINA);?>&pagina=<?php echo $_SESSION["sesion_pagina"];?>"><img src="imagenes/siguiente.png" style="vertical-align:middle; border:0; " alt="Siguiente" title="Siguiente"></a>
				<A href="fcontenido.php?limite=<?php echo ($num_registros-$_REGISTROS_POR_PAGINA);?>&pagina=<?php echo $_SESSION["sesion_pagina"];?>"><img src="imagenes/ultimo.png" style="vertical-align:middle; border:0; " alt="�ltimo" title="�ltimo"></a>
	<?php		} else {?>			
				&nbsp;<img src="imagenes/siguiente_back.png" style="vertical-align:middle; border:0; " alt="Siguiente" title="Siguiente">	
				<img src="imagenes/ultimo_back.png" style="vertical-align:middle; border:0; " alt="�ltimo" title="�ltimo">
		<?php	}	?>
			</td>		
		</tr>
	</table>	