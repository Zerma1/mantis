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
