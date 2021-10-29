<?php
  //CONECTO A LA BASE DE DATOS
  require_once 'bd.php';

  $fecha = date('Y-m-d');

  //OBTENGO LOS PRECIOS DE HOY Y AYER
  $queryHoyMonedero = $conexion->prepare("SELECT * FROM monederos WHERE fecha='$fecha'");
  $queryHoyMonedero->execute();
  $rowHoyMonedero = $queryHoyMonedero->fetch(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Monederos electrónicos</title>
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
            <span id="titulo">Monederos Electrónicos</span>
          </div>
        </div>

        <table class="valoresElectronicos" width="750px">
  		    <tbody>
            <td width="8%"></td>
            <td width="20%" style="text-align: left;">@AMAZON</td>
            <td width="30%" style="text-align: right;">Bs. <?php echo number_format($rowHoyMonedero->amazon,2,',','.'); ?></td>
            <td width="8%"></td>
          </tbody>
          <tbody>
            <td width="8%"></td>
            <td width="20%" style="text-align:left;">@PAYPAL</td>
            <td width="30%" style="text-align: right;">Bs. <?php echo number_format($rowHoyMonedero->paypal,2,',','.'); ?></td>
            <td width="8%"></td>
          </tbody>
          <tbody>
            <td width="8%"></td>
            <td width="20%" style="text-align: left;">@SKRILL</td>
            <td width="30%" style="text-align: right;">Bs. <?php echo number_format($rowHoyMonedero->skrill,2,',','.'); ?></td>
            <td width="8%"></td>
          </tbody>
          <tbody>
            <td width="8%"></td>
            <td width="20%" style="text-align: left;">@UPHOLDINC</td>
            <td width="30%" style="text-align: right;">Bs. <?php echo number_format($rowHoyMonedero->uphold,2,',','.'); ?></td>
            <td width="8%"></td>
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

      </div>
    </main>
    <footer>
      <a class="left" href="./">Inicio</a>
      <a class="right">Aviso</a>
    </footer>
  </body>
</html>