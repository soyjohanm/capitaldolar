<?php
  //CONECTO A LA BASE DE DATOS
  require_once 'bd.php';

  $ayer = date("Y-m-d",strtotime(date('Y-m-d')."-1 days"));
  $fecha = date('Y-m-d');

  //OBTENGO EL PROMEDIO DE AYER Y HOY
  $sqlHoy = $conexion->prepare("SELECT (sum(akbfintech+dolartoday+localbitcoin+theairtm+yadio+rya)/6) AS promedio FROM valores WHERE fecha='$fecha'");
  $sqlHoy->execute();
  $promedioHoy = $sqlHoy->fetch(PDO::FETCH_OBJ);

  $sqlAyer = $conexion->prepare("SELECT (sum(akbfintech+dolartoday+localbitcoin+theairtm+yadio+rya)/6) AS promedio FROM valores WHERE fecha='$ayer'");
  $sqlAyer->execute();
  $promedioAyer = $sqlAyer->fetch(PDO::FETCH_OBJ);

  //ALMACENO VALOR DEL PROMEDIO Y PORCENTAJE
  $valorPromedioHoy = number_format(($promedioHoy->promedio),'2',',','.');

  //ALMACENO LOS PROMEDIO DEL VALOR Y PORCENTAJE
  $porcentajePromedio = ((($promedioHoy->promedio)-($promedioAyer->promedio))/($promedioAyer->promedio)*100);

  //OBTENGO LOS PRECIOS DE HOY Y AYER
  $queryHoy = $conexion->prepare("SELECT * FROM valores WHERE fecha='$fecha'");
  $queryHoy->execute();
  $rowHoy = $queryHoy->fetch(PDO::FETCH_OBJ);

  $queryAyer = $conexion->prepare("SELECT * FROM valores WHERE fecha='$ayer'");
  $queryAyer->execute();
  $rowAyer = $queryAyer->fetch(PDO::FETCH_OBJ);

  //OBTENGO LOS PRECIOS DE HOY Y AYER
  $queryHoyMonedero = $conexion->prepare("SELECT * FROM monederos WHERE fecha='$fecha'");
  $queryHoyMonedero->execute();
  $rowHoyMonedero = $queryHoyMonedero->fetch(PDO::FETCH_OBJ);

  /* ACTUALIZO LOS PRECIOS DE LA BD CON LOS DATOS RECIBIDOS */
  if (isset($_POST['guardar'])) {
    $query = $conexion->prepare("UPDATE valores SET akbfintech=".$_POST['akbfintech'].",rya=".$_POST['rya'].",dolartoday=".$_POST['dolartoday'].",localbitcoin=".$_POST['localbitcoin'].",theairtm=".$_POST['airtm'].",yadio=".$_POST['yadio']." WHERE fecha='$fecha'");
	  $query->execute();

	$query = $conexion->prepare(
      "UPDATE monederos
          SET amazon=".$_POST['amazon'].",paypal=".$_POST['paypal'].",skrill=".$_POST['skrill'].",uphold=".$_POST['uphold']."
        WHERE fecha='$fecha'");
	  $query->execute();
	  header('location: ./');
  }

  /* AÑADO EL DÍA DE MAÑANA A LA BD CON VALORES POR DEFECTO 0 */
  if (isset($_POST['nuevo'])) {
	  $manana = date("Y-m-d", (time() + (24 * 60 * 60)));
	  try {
		$conexion->beginTransaction();
		$conexion->exec("INSERT INTO valores(fecha) VALUES ('".$manana."')");
		$conexion->exec("INSERT INTO monederos(fecha) VALUES ('".$manana."')");
		$conexion->commit();
	  } catch( Exception $e) {
		$conexion->rollBack();
	  }
  }

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>CapitalDolar | <?php echo $valorPromedioHoy; ?></title>
    <link rel="stylesheet" href="./estilos.css">
  </head>
  <body>
    <main>
      <div id="contenedor_completo">

        <div id="contenedor_imagen">
          <span id="fechaNombre">fecha</span>
          <span id="fechaDatos">
            <?php
              echo date('d m Y');
            ?>
          </span>
          <div class="promedio">
            <span id="tituloPromedio">promedio</span>
            <span id="valorPromedio"><?php echo $valorPromedioHoy; ?></span>
            <span id="porcentajePromedio"><?php echo $valor = round($porcentajePromedio,2); ?>%</span>
            <span id="flechita"><?php echo iconos($valor); ?></span>
          </div>
        </div>

        <table class="valores" width="750px">
  		    <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align: left;">@AIRTMINC</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->theairtm,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->theairtm-$rowAyer->theairtm)/($rowAyer->theairtm)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
          <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align:left;">@AKBFINTECH</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->akbfintech,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->akbfintech-$rowAyer->akbfintech)/($rowAyer->akbfintech)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
		  <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align: left;">@CAMBIOSRYA</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->rya,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->rya-$rowAyer->rya)/($rowAyer->rya)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
          <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align: left;">@DOLARTODAY</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->dolartoday,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->dolartoday-$rowAyer->dolartoday)/($rowAyer->dolartoday)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
          <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align: left;">@LOCALBITCOIN</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->localbitcoin,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->localbitcoin-$rowAyer->localbitcoin)/($rowAyer->localbitcoin)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
          <tbody>
            <td width="9%"></td>
            <td width="35%" style="text-align: left;">@YADIO_IO</td>
            <td width="18%" style="text-align: right;"><?php echo number_format($rowHoy->yadio,2,',','.'); ?></td>
            <td width="28%" style="text-align: right;"><?php echo $valor = round(($rowHoy->yadio-$rowAyer->yadio)/($rowAyer->yadio)*100,2); ?>%</td>
            <td width="10%"><?php echo iconos($valor); ?></td>
          </tbody>
        </table>

        <div id="instagram">
          <svg xmlns="http://www.w3.org/2000/svg" width="2.5vh" height="2.5vh" viewBox="0 0 32 32">
            <title>Intagram</title>
            <g>
              <path d="M16 2.881c4.275 0 4.781 0.019 6.462 0.094 1.563 0.069 2.406 0.331 2.969 0.55 0.744 0.288 1.281 0.638 1.837 1.194 0.563 0.563 0.906 1.094 1.2 1.838 0.219 0.563 0.481 1.412 0.55 2.969 0.075 1.688 0.094 2.194 0.094 6.463s-0.019 4.781-0.094 6.463c-0.069 1.563-0.331 2.406-0.55 2.969-0.288 0.744-0.637 1.281-1.194 1.837-0.563 0.563-1.094 0.906-1.837 1.2-0.563 0.219-1.413 0.481-2.969 0.55-1.688 0.075-2.194 0.094-6.463 0.094s-4.781-0.019-6.463-0.094c-1.563-0.069-2.406-0.331-2.969-0.55-0.744-0.288-1.281-0.637-1.838-1.194-0.563-0.563-0.906-1.094-1.2-1.837-0.219-0.563-0.481-1.413-0.55-2.969-0.075-1.688-0.094-2.194-0.094-6.463s0.019-4.781 0.094-6.463c0.069-1.563 0.331-2.406 0.55-2.969 0.288-0.744 0.638-1.281 1.194-1.838 0.563-0.563 1.094-0.906 1.838-1.2 0.563-0.219 1.412-0.481 2.969-0.55 1.681-0.075 2.188-0.094 6.463-0.094zM16 0c-4.344 0-4.887 0.019-6.594 0.094-1.7 0.075-2.869 0.35-3.881 0.744-1.056 0.412-1.95 0.956-2.837 1.85-0.894 0.888-1.438 1.781-1.85 2.831-0.394 1.019-0.669 2.181-0.744 3.881-0.075 1.713-0.094 2.256-0.094 6.6s0.019 4.887 0.094 6.594c0.075 1.7 0.35 2.869 0.744 3.881 0.413 1.056 0.956 1.95 1.85 2.837 0.887 0.887 1.781 1.438 2.831 1.844 1.019 0.394 2.181 0.669 3.881 0.744 1.706 0.075 2.25 0.094 6.594 0.094s4.888-0.019 6.594-0.094c1.7-0.075 2.869-0.35 3.881-0.744 1.050-0.406 1.944-0.956 2.831-1.844s1.438-1.781 1.844-2.831c0.394-1.019 0.669-2.181 0.744-3.881 0.075-1.706 0.094-2.25 0.094-6.594s-0.019-4.887-0.094-6.594c-0.075-1.7-0.35-2.869-0.744-3.881-0.394-1.063-0.938-1.956-1.831-2.844-0.887-0.887-1.781-1.438-2.831-1.844-1.019-0.394-2.181-0.669-3.881-0.744-1.712-0.081-2.256-0.1-6.6-0.1v0z"></path>
              <path d="M16 7.781c-4.537 0-8.219 3.681-8.219 8.219s3.681 8.219 8.219 8.219 8.219-3.681 8.219-8.219c0-4.537-3.681-8.219-8.219-8.219zM16 21.331c-2.944 0-5.331-2.387-5.331-5.331s2.387-5.331 5.331-5.331c2.944 0 5.331 2.387 5.331 5.331s-2.387 5.331-5.331 5.331z"></path>
              <path d="M26.462 7.456c0 1.060-0.859 1.919-1.919 1.919s-1.919-0.859-1.919-1.919c0-1.060 0.859-1.919 1.919-1.919s1.919 0.859 1.919 1.919z"></path>
            </g>
          </svg>
          <span>capitaldolar</span>
        </div>

        <div id="contenedor_informacion">
          <p>Actualización del día <?php saber_dia(date('Y-m-d')); echo date('d/m/Y'); ?> <br> BS <?php echo $valorPromedioHoy; ?> precio promedio por dólar hubo un <?php echo $valor = ($porcentajePromedio < 0) ? 'descenso' : 'ascenso'; ?> del <?php echo round($porcentajePromedio,2); ?>% con respecto a la publicación anterior. <br> --- <br> @capitaldolar tiene exclusivamente la responsabilidad de informar el precio del dólar según diferentes entidades y medios electrónicos. La decisión que tomen nuestros usuarios sobre la información aquí suministrada, no tiene ninguna relación con nosotros. <br> --- <br> #dolartoday #akbfintech #cambiosrya #airtm #yadio #uphold #localbitcoin #skrill #paypal #amazon #noticiasvenezuela #Venezuela #venezolanos #dolar #mercadocambiario #promediodolar #remesas #noticias #dolares #dolarparalelo #promediodeldolar</p>
        </div>

  	    <div id="contenedor_formulario">
  	      <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
      		  <span>THEAIRTM: </span><input type="number" name="airtm" id="airtm" min="0" step="0.01" value="<?php echo ( $rowHoy->theairtm == 0 ) ? valor( "airtm" ) : $rowHoy->theairtm ?>">
      		  <span>AKBFINTECH: </span><input type="number" name="akbfintech" id="akbfintech" min="0" step="0.01" value="<?php echo ( $rowHoy->akbfintech == 0 ) ? valor( "akb-fintech" ) : $rowHoy->akbfintech ?>"><br>
      		  <span>CAMBIOSRYA: </span><input type="number" name="rya" id="rya" min="0" step="0.01" value="<?php echo ( $rowHoy->rya == 0 ) ? valor( "cambios-rya" ) : $rowHoy->rya ?>"><br>
      		  <span>DOLARTODAY: </span><input type="number" name="dolartoday" id="dolartoday" min="0" step="0.01" value="<?php echo ( $rowHoy->dolartoday == 0 ) ? valor( "dolartoday" ) : $rowHoy->dolartoday ?>"><br>
      		  <span>LOCALBITCOIN: </span><input type="number" name="localbitcoin" id="localbitcoin" min="0" step="0.01" value="<?php echo ( $rowHoy->localbitcoin == 0 ) ? valor( "localbitcoins" ) : $rowHoy->localbitcoin ?>"><br>
      		  <span>YADIO_IO: </span><input type="number" name="yadio" id="yadio" min="0" step="0.01" value="<?php echo ( $rowHoy->yadio == 0 ) ? valor( "yadio" ) : $rowHoy->yadio ?>">
			  <span></span><br>
      		  <span>AMAZON: </span><input type="number" name="amazon" id="amazon" min="0" step="0.01" value="<?php echo ( $rowHoyMonedero->amazon == 0 ) ? valor( "monedero-amazon" ) : $rowHoyMonedero->amazon ?>">
      		  <span>PAYPAL: </span><input type="number" name="paypal" id="paypal" min="0" step="0.01" value="<?php echo ( $rowHoyMonedero->paypal == 0 ) ? valor( "monedero-paypal" ) : $rowHoyMonedero->paypal ?>"><br>
      		  <span>SKRILL: </span><input type="number" name="skrill" id="skrill" min="0" step="0.01" value="<?php echo ( $rowHoyMonedero->skrill == 0 ) ? valor( "monedero-skrill" ) : $rowHoyMonedero->skrill ?>"><br>
      		  <span>UPHOLDINC: </span><input type="number" name="uphold" id="uphold" min="0" step="0.01" value="<?php echo ( $rowHoyMonedero->uphold == 0 ) ? valor( "monedero-uphold" ) : $rowHoyMonedero->uphold ?>">
      		  <button type="submit" name="guardar" id="guardar" class="btn btn-flat right">Guardar</button>
      		</form>
  	    </div>

    	  <div id="contenedor_nueva">
    	    <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
      		  <button type="submit" name="nuevo" id="nuevo" class="btn btn-large btn-flat">Nuevo día</button>
      		</form>
    	  </div>

      </div>
    </main>
    <footer>
      <a class="left" href="./monederos.php">Monederos</a>
      <a class="right">Aviso</a>
    </footer>
  </body>
</html>

<?php
  function saber_dia($nombredia) {
    $dia = array('', 'lunes ','martes ','miércoles ','jueves ','viernes ','sábado ', 'domingo ');
    $fecha = $dia[date('N', strtotime($nombredia))];
    echo $fecha;
  }
  function valor ( $dominio ) {
	$dataJSON = file_get_contents( "https://exchangemonitor.net/ajax/widget-unique?type=$dominio" );
	if( $dataJSON === FALSE ) { return 0; }
	else {
      $dataOBJECT = json_decode( $dataJSON );
      $precio = explode( ".", $dataOBJECT->price );
      $precio = implode( "", $precio );
      return $precio;
	}
  }
  function iconos($valor) {
    if($valor < 0) {
      $icon = "<svg xmlns='http://www.w3.org/2000/svg' width='2.5vh' height='2.5vh' viewBox='0 0 512 512'><path style='fill:red' d='m256 512c-68.378906 0-132.667969-26.628906-181.019531-74.980469-48.351563-48.351562-74.980469-112.640625-74.980469-181.019531s26.628906-132.667969 74.980469-181.019531c48.351562-48.351563 112.640625-74.980469 181.019531-74.980469s132.667969 26.628906 181.019531 74.980469c48.351563 48.351562 74.980469 112.640625 74.980469 181.019531s-26.628906 132.667969-74.980469 181.019531c-48.351562 48.351563-112.640625 74.980469-181.019531 74.980469zm0-472c-119.101562 0-216 96.898438-216 216s96.898438 216 216 216 216-96.898438 216-216-96.898438-216-216-216zm138.285156 182-28.285156-28.285156-110 110-110-110-28.285156 28.285156 138.285156 138.285156zm0 0'/></svg>";
    }
    if($valor == 0) {
      $icon = "<svg xmlns='http://www.w3.org/2000/svg' width='2.5vh' height='2.5vh' viewBox='0 0 512 512'><path d='M491.841,156.427c-19.471-45.946-51.936-85.013-92.786-112.637C358.217,16.166,308.893-0.007,256,0
			c-35.254-0.002-68.946,7.18-99.571,20.158C110.484,39.63,71.416,72.093,43.791,112.943C16.167,153.779-0.007,203.104,0,256
			c-0.002,35.255,7.181,68.948,20.159,99.573c19.471,45.946,51.937,85.013,92.786,112.637C153.783,495.834,203.107,512.007,256,512
			c35.253,0.002,68.946-7.18,99.571-20.158c45.945-19.471,85.013-51.935,112.638-92.785C495.834,358.22,512.007,308.894,512,256
			C512.002,220.744,504.819,187.052,491.841,156.427z M460.413,342.257c-16.851,39.781-45.045,73.723-80.476,97.676
			c-35.443,23.953-78.02,37.926-123.936,37.933c-30.619-0.002-59.729-6.218-86.255-17.454
			c-39.781-16.851-73.724-45.044-97.677-80.475C48.114,344.495,34.14,301.917,34.133,256c0.002-30.62,6.219-59.731,17.454-86.257
			c16.851-39.781,45.045-73.723,80.476-97.676C167.506,48.113,210.084,34.14,256,34.133c30.619,0.002,59.729,6.218,86.255,17.454
			c39.781,16.85,73.724,45.044,97.677,80.475c23.953,35.443,37.927,78.02,37.934,123.939
			C477.864,286.62,471.648,315.731,460.413,342.257z'/><path d='M389.594,283.832H122.406c-9.222,0-16.699,7.477-16.699,16.699s7.477,16.699,16.699,16.699h267.189
			c9.222,0,16.699-7.477,16.699-16.699S398.817,283.832,389.594,283.832z'/><path d='M389.594,183.636H122.406c-9.222,0-16.699,7.477-16.699,16.699c0,9.222,7.477,16.699,16.699,16.699h267.189
			c9.222,0,16.699-7.477,16.699-16.699C406.294,191.113,398.817,183.636,389.594,183.636z'/></svg>";
    }
    if($valor > 0) {
      $icon = "<svg xmlns='http://www.w3.org/2000/svg' width='2.5vh' height='2.5vh' viewBox='0 0 512 512'><path style='fill:green' d='m256 512c-68.378906 0-132.667969-26.628906-181.019531-74.980469-48.351563-48.351562-74.980469-112.640625-74.980469-181.019531s26.628906-132.667969 74.980469-181.019531c48.351562-48.351563 112.640625-74.980469 181.019531-74.980469s132.667969 26.628906 181.019531 74.980469c48.351563 48.351562 74.980469 112.640625 74.980469 181.019531s-26.628906 132.667969-74.980469 181.019531c-48.351562 48.351563-112.640625 74.980469-181.019531 74.980469zm0-472c-119.101562 0-216 96.898438-216 216s96.898438 216 216 216 216-96.898438 216-216-96.898438-216-216-216zm138.285156 250-138.285156-138.285156-138.285156 138.285156 28.285156 28.285156 110-110 110 110zm0 0'/></svg>";
    }
    return $icon;
  }
?>
