<?php
//#region Information de Connexion
    $g_hostname               = 'localhost';
    $g_db_type                = 'mysqli';
    $g_database_name          = 'new_mantis';
    $g_db_username            = 'root';
    $g_db_password            = '';
    $g_default_timezone       = 'UTC';
    $g_crypto_master_salt     = 'MTDB6MKij6FmCHqbH3lV/GyNgKFbsc8RMKQ4SMeQvPM=';
    $g_path                   = 'http://localhost/mantisbt-2.27.3/';
//#endregion Information de Connexion


//#region CONFIGURATION E-MAIL
    
    # Méthode d'envoi : 1 pour la fonction mail() de PHP, 2 pour SMTP.
    # Nous utilisons SMTP pour plus de fiabilité.
    $g_phpmailer_method = PHPMAILER_METHOD_SMTP;
    
    # Adresse de votre serveur SMTP.
    $g_smtp_host = 'votre_serveur_smtp.com';
    
    # Port de votre serveur SMTP (587 pour TLS, 465 pour SSL, 25 pour non chiffré).
    $g_smtp_port = 587;
    
    # Nom d'utilisateur pour la connexion SMTP.
    $g_smtp_username = 'votre_email@domaine.com';
    
    # Mot de passe pour la connexion SMTP (ou mot de passe d'application).
    $g_smtp_password = 'votre_mot_de_passe';
    
    # Type de chiffrement : 'tls', 'ssl' ou laissez vide si pas de chiffrement.
    $g_smtp_connection_mode = 'tls';
    
    # --- INFORMATIONS DE L'EXPÉDITEUR ---
    
    # L'adresse e-mail qui apparaîtra dans le champ "De".
    $g_from_email = 'noreply.mantis-BT@votre-mantis.com';
    
    # Le nom qui apparaîtra dans le champ "De".
    $g_from_name = 'Mantis Bug Tracker';
    
    # L'adresse e-mail de l'administrateur du système.
    $g_administrator_email = 'admin@votre-domaine.com';
    
    # --- Envoi d'e-mails asynchrone (via Cronjob) ---
    # Activez cette option (ON) pour que Mantis n'envoie pas les e-mails instantanément.
    # À la place, les e-mails sont mis en file d'attente dans la base de données.
    # Cela rend l'interface utilisateur beaucoup plus rapide.
    # IMPORTANT : Vous DEVEZ configurer une tâche planifiée (cronjob) sur votre serveur
    # pour exécuter périodiquement le script 'scripts/send_emails.php'.
    # Sans cela, aucun e-mail ne sera envoyé.
    $g_email_send_using_cronjob = ON;
//#endregion


//#region Customizing Status Values
    // OLD: $g_status_enum_string = '100:noveaux, 101:pris en charge, 110:qualifie, 120:analyse, 133:affecte_DEV, 140:traite, 151:a_deploye, 160:a_tester, 161:valide_usine, 162:resolu, 180:demande_precision, 181:suspendu, 182:reouvert, 190:ferme, 191:annule, 192:rejete';
    $g_status_enum_string = '
        100:nouveau,
        101:pris en charge,
        110:qualifié,
        120:analysé,
        131:affecté_RRP,
        132:affecté_IS,
        133:affecté_DEV,
        140:traité,
        151:à_déployer,
        160:à_tester,
        161:validé_usine,
        165:test_client,
        180:demande_précision,
        181:suspendu,
        182:réouvert,
        190:fermé,
        191:annulé,
        192:rejeté
    ';
//#endregion  Customizing Status Values


//#region Customizing Status Colors
    # Couleurs (La clé doit être le nom du statut, pas son numéro)
    // OLD: $g_status_colors = array( 'noveaux' => '#FF0000', ... );
    $g_status_colors = array(
        'nouveau'           => '#FF0000',
        'pris en charge'    => '#ffcd85',
        'qualifié'          => '#e3b7eb',
        'analysé'           => '#fff494',
        'affecté_RRP'       => '#FF00FF',
        'affecté_IS'        => '#FF00FF',
        'affecté_DEV'       => '#FF00FF',
        'traité'            => '#c2dfff',
        'à_déployer'         => '#ADFF2F',
        'à_tester'          => '#32CD32',
        'validé_usine'      => '#00FF7F',
        'test_client'       => '#00FF7F',
        'resolu'            => '#d2f5b0',
        'demande_précision' => '#C0C0C0',
        'suspendu'          => '#800080',
        'réouvert'          => '#FF1493',
        'fermé'             => '#c9ccc4',
        'annulé'            => '#696969',
        'rejeté'            => '#000000'
    );
//#endregion Customizing Status Colors


//#region Niveau d'acces
    # Niveaux d'accès
    // OLD: $g_access_levels_enum_string = '110:USER, 120:TESTEUR, 130:SOUTIEN, 140:DEVELOPEUR, 150:RRP, 160:RCP, 190:ADMIN, 999:SUP_ADMIN';
    $g_access_levels_enum_string = '
        110:Utilisateur,
        120:Testeur,
        130:Soutien,
        140:Développeur,
        150:RRP,
        160:RCP,
        190:Admin,
        999:SUPER_Admin
    ';
//#endregion Niveau d'acces


//#region Severitée
    // OLD: $g_severity_enum_string = '10:MINEUR, 20:MAJEUR, 30:CRITIQUE, 40:BLOQUANT';
    $g_severity_enum_string = '
        10:Mineur,
        20:Majeur,
        30:Critique,
        40:Bloquant'
    ;
//#endregion Severitée


//#region Bug Report Page Fields
    # Champs à afficher sur la page de création de ticket
    $g_bug_report_page_fields = array(
        'category_id',
        'severity',
        'summary',
        'description',
        'additional_information',
        'view_state',
        'handler',
        'tags',
        'attachments',
    );
//#endregion


//#region gestion droit
    # Autoriser l'admin (190) à gérer vos nouveaux niveaux
    $g_manage_user_threshold = 190;

    # Seuil pour voir les autres utilisateurs
    $g_view_user_threshold = 110; // Niveau minimum pour voir la liste des membres

    # Niveau par défaut lors de la création d'un compte
    $g_default_new_account_access_level = 110;
//#endregion