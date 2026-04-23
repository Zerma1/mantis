<?php
$g_hostname               = 'localhost';
$g_db_type                = 'mysqli';
$g_database_name          = 'new_mantis';
$g_db_username            = 'root';
$g_db_password            = '';
$g_default_timezone       = 'UTC';
$g_crypto_master_salt     = 'MTDB6MKij6FmCHqbH3lV/GyNgKFbsc8RMKQ4SMeQvPM=';
$g_path                   = 'https://pprod-portail-cepn.intradef.gouv.fr/new_mantis/';

//#region Customizing Status Values
    # Correction de l'espace dans 'to_be_tested'
        $g_status_enum_string = '
            100:noveaux,
            101:pris en charge,
            110:qualifie,
            120:analyse,
            133:affecte_DEV,
            140:traite,
            151:a_deploye,
            160:a_tester,
            161:valide_usine,
            162:resolu,
            180:demande_precision,
            181:suspendu,
            182:reouvert,
            190:ferme,
            191:annule,
            192:rejete
        ';

    # Couleurs (La clé doit être 'to_be_tested' sans espace)
        $s_status_colors = array(

            100 => '#FA5858', // Nouveaux (Ancienne couleur: new)
            101 => '#ffcd85', // Pris en charge (Ancienne couleur: assigned)
            110 => '#e3b7eb', // Qualifié (Ancienne couleur: confirmed/plum)
            120 => '#fff494', // Analysé (Ancienne couleur: analysee/butter)
            133 => '#FF00FF', // Affecté DEV (Nouvelle couleur: Magenta)
            140 => '#c2dfff', // Traité (Ancienne couleur: traitee/sky blue)
            151 => '#ADFF2F', // À déployer (Nouvelle couleur: Vert acide)
            160 => '#32CD32', // À tester (Nouvelle couleur: Vert lime)
            161 => '#00FF7F', // Validé Usine (Nouvelle couleur: Vert printemps)
            162 => '#d2f5b0', // Résolu (Ancienne couleur: resolved/chameleon)
            180 => '#C0C0C0', // Demande précision (Nouvelle couleur: Gris clair)
            181 => '#800080', // Suspendu (Nouvelle couleur: Violet)
            182 => '#FF1493', // Réouvert (Nouvelle couleur: Rose profond)
            190 => '#c9ccc4', // Fermé (Ancienne couleur: closed/aluminum)
            191 => '#696969', // Annulé (Nouvelle couleur: Gris foncé)
            192 => '#000000'  // Rejeté (Nouvelle couleur: Noir)

        );

//#endregion Customizing Status Values

//#region Niveau d'acces
    # Niveaux d'accès
    $g_access_levels_enum_string = '
        110:USER,
        
        120:TESTEUR,
        130:SOUTIEN,
        140:DEVELOPEUR,
        150:RRP,
        160:RCP,
        190:ADMIN,
        
        999:SUP_ADMIN
    ';

//#endregion Niveau d'acces

//#region Severitée

    $g_severity_enum_string = '
        10:MINEUR,
        20:MAJEUR,
        30:CRITIQUE,
        40:BLOQUANT'
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

//#region
    # Autoriser l'admin (190) à gérer vos nouveaux niveaux
    $g_manage_user_threshold = 190;

    # Seuil pour voir les autres utilisateurs
    $g_view_user_threshold = 110; // Niveau minimum pour voir la liste des membres

    # Niveau par défaut lors de la création d'un compte
    $g_default_new_account_access_level = 110;
//#endregion

//#region --- CONFIGURATION E-MAIL ---

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