<?php
  $ar=fopen("datos.txt","a") or die("Problemas en la creacion");
  //fputs($ar,$_REQUEST['nombre']);
  fputs($ar,"\ndocumento de texto");
  //fputs($ar,$_REQUEST['comentarios']);
  fputs($ar,"\n");
  fputs($ar,"--------------------------------------------------------");
  fputs($ar,"\n");
  fclose($ar);
phpinfo();
