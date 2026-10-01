<?php

return [
    'enabled' => true,
    'shortcuts' => [
        // Formato: 'tecla' => ['route' => 'ruta', 'action' => 'función']
        
        // Funciones globales
        'f1' => [
            'action' => 'toggleHelp',
            'description' => 'Mostrar/ocultar ayuda',
        ],
        'tab' => [
            'action' => 'focusNextInput',
            'description' => 'Siguiente input (ya tiene comportamiento por defecto)',
        ],
        'shift+tab' => [
            'action' => 'focusPrevInput',
            'description' => 'Input anterior',
        ],
        'escape' => [
            'action' => 'closeModals',
            'description' => 'Cerrar modales',
        ],
        
        // Navegación
        'ctrl+h' => [
            'route' => 'dashboard',
            'description' => 'Ir a inicio',
        ],
        'ctrl+p' => [
            'route' => 'products.index',
            'description' => 'Ir a productos',
        ],
        'ctrl+v' => [
            'route' => 'sales.index',
            'description' => 'Ir a ventas',
        ],
        
        // Formularios
        'ctrl+s' => [
            'action' => 'submitForm',
            'description' => 'Guardar/Enviar formulario',
        ],
        'ctrl+n' => [
            'route' => 'products.create',
            'description' => 'Nuevo producto (contexto específico)',
        ],
        
        // Búsqueda
        'ctrl+f' => [
            'action' => 'focusSearch',
            'description' => 'Ir a barra de búsqueda',
        ],
        'ctrl+l' => [
            'action' => 'clearSearch',
            'description' => 'Limpiar búsqueda',
        ],
    ],
    
    // Teclas que no deben interceptarse (reservadas para texto)
    'reserved_in_inputs' => ['ctrl+c', 'ctrl+x', 'ctrl+v', 'ctrl+z', 'ctrl+a'],
];
