<?php

require_once (__DIR__)."/../config.php";

class BBDD
{
   private static $instancia;

   private function __construct()
   {
   }

   public static function instancia()
   {
       //Si da fallo al conectar retornar error (mysqli_connect_errno)
       return self::$instancia ? self::$instancia : self::$instancia = mysqli_connect(host, usuario, password, bbdd);
   }
}
