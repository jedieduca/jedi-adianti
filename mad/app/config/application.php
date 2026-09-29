<?php
return [
    'general' =>  [
        'timezone' => 'America/Sao_Paulo',
        'language' => 'auto,pt',
        'application' => 'jedi-mad',
        'title' => 'MAD do JEDi - 1.0.0',
        'theme' => 'adminbs5',
        'seed' => 'odfu6asnodf8as',
        'rest_key' => '',
        'multiunit' => '0',
        'public_view' => '0',
        'public_entry' => '',
        'debug' => '0',
        'strict_request' => '0',
        'multi_lang' => '1',
        'require_terms' => '0',
        'concurrent_sessions' => '1',
        'lang_options' => [
          'pt' => 'Português',
          'en' => 'English',
          'es' => 'Español',
        ],
        'multi_database' => '0',
        'validate_strong_pass' => '1',
        'notification_login' => '0',
        'welcome_message' => 'Have a great jorney!',
        'request_log_service' => 'SystemRequestLogService',
        'request_log' => '0',
        'request_log_types' => 'cli,web,rest',
        /* Serviço 1: Autenticação Inicial */
        'api_auth_url' => "http://python_service:8001/v1",
        /* Dados do formulário */
        'client_id' => "admin@jedieduca.com.br",
        'client_pass' => "JediEduc@2026",
        'client_secret' => "pZ51QzZaNystmR1-DG37rFzrpsGkU75gAHrdkDmXAZ8",
        /*'password_renewal_interval' => '',*/
    ],
    'mail' => [
        'host'   => 'smtp.hostinger.com',           // Ex: smtp.gmail.com ou smtp.office365.com
        'port'   => '465',                          // Porta do SMTP (geralmente 587 para TLS ou 465 para SSL)
        'auth'   => '1',                            // 1 para requerer autenticação, 0 se não requerer
        'user'   => 'contato@jedieduca.com.br',     // E-mail ou usuário de autenticação do SMTP
        'pass'   => 'J3d1@3duc@',                   // Senha da conta ou senha de aplicativo
        'secure' => 'ssl',                          // 'ssl' para porta 465, 'tls' para porta 587
        'from'   => 'contato@jedieduca.com.br',     // E-mail remetente padrão
        'name'   => 'JEDi Educa - Contato',         // Nome do remetente padrão
    ],                   
    'recaptcha' => [
        'enabled' => '0',
        'key' => '...',
        'secret' => '...'
    ],
    'permission' =>  [
        'public_classes' => [
          'SystemRequestPasswordResetForm',
          'SystemPasswordResetForm',
          'SystemRegistrationForm',
          'SystemPasswordRenewalForm',
          'SystemConcurrentAccessView'
        ],
        'user_register' => '0',
        'reset_password' => '1',
        'default_groups' => '2',
        'default_screen' => '30',
        'default_units' => '1',
    ],
    'highlight' => [
        'comment' => '#808080',
        'default' => '#FFFFFF',
        'html' => '#C0C0C0',
        'keyword' => '#62d3ea',
        'string' => '#FFC472',
    ],
    'login' => [
        'logo' => '../images/Logo_JEDi_1.png',
        'background' => '../images/Logo_JEDi_1.png'
    ],
    'template' => [
        'navbar' => [
            'has_program_search' => '1',
            'has_notifications' => '0',
            'has_messages' => '0',
            'has_docs' => '0',
            'has_contacts' => '0',
            'has_support_form' => '1',
            'has_wiki' => '0',
            'has_news' => '0',
            'has_menu_mode_switch' => '1',
            'has_main_mode_switch' => '1',
            'has_master_menu' => '1',
            'always_collapse' => '0',
            'allow_page_tabs' => '0'
        ],
        'dialogs' => [
            'use_swal' => '1'
        ],
        'theme' => [
//            'menu_dark_color' => 'rgb(29 45 83)',
            'menu_dark_color' => '#222d32',
            'menu_mode'  => 'dark',
            'main_mode'  => 'light'
        ]
    ]
];
