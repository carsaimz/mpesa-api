<?php

/* Loading composer packages */
if(file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

spl_autoload_register(function ($class_name) {
    // Remove o namespace 'carsaimz\' do início
    $class_name = str_replace('carsaimz\\', '', $class_name);
    
    // Converte namespace para estrutura de diretórios
    $class_path = implode('/', explode('\\', $class_name));
    
    // Caminho para o arquivo da classe
    $file_path = __DIR__.'/'.$class_path.'.php';
    
    if(file_exists($file_path)) {
        require_once $file_path;
    }
});