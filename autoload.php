<?php
/* Loading composer packages */
if(file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

spl_autoload_register(function ($class_name) {
    // Para o namespace carsaimz
    if (strpos($class_name, 'carsaimz\\') === 0) {
        // Remove o namespace 'carsaimz\'
        $relative_class = substr($class_name, strlen('carsaimz\\'));
        
        // Converte namespace para caminho de arquivo
        $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            // Log para debug
            error_log("Autoload: Arquivo não encontrado: $file para classe: $class_name");
        }
    }
});
?>