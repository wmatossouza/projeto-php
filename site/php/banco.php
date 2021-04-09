<?php

class Banco
{
    // private static $dbNome = 'phprs';
    // private static $dbHost = 'db';
    // private static $dbUsuario = 'root';
    // private static $dbSenha = 'phprs';

    private static $dbNome = '3491651_jogobd';
    private static $dbHost = 'fdb22.awardspace.net;port=3306';
    private static $dbUsuario = '3491651_jogobd';
    private static $dbSenha = 'b^__!%^A8-J8]MoQ';

    private static $cont = null;
    
    public function __construct() 
    {
        die('A função Init nao é permitido!');
    }
    
    public static function conectar()
    {
        if(null == self::$cont)
        {
 
            try
            {
                self::$cont =  new PDO("mysql:host=".self::$dbHost.";"."dbname=".self::$dbNome, self::$dbUsuario, self::$dbSenha); 
              
            }
            catch(PDOException $exception)
            {
                die($exception->getMessage());
            }
        }
        return self::$cont;
    }
    
    public static function desconectar()
    {
        self::$cont = null;
    }
}

?>
