<?php
$g_hostname               = 'localhost';
$g_db_type                = 'mysqli';
$g_database_name          = 'bugtracker';
$g_db_username            = 'root';
$g_db_password            = '';
$g_default_timezone       = 'UTC';
$g_crypto_master_salt     = 'MTDB6MKij6FmCHqbH3lV/GyNgKFbsc8RMKQ4SMeQvPM=';
$g_path                   = 'http://localhost/mantisbt-2.27.3/';

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
        10:Mineur,
        11:Majeur,
        12:Critique,
        13:Bloquant'
    ;

//#endregion Severitée

//#region
    # Autoriser l'administrateur (190) à gérer vos nouveaux niveaux
    $g_manage_user_threshold = ADMIN;

    # Seuil pour voir les autres utilisateurs
    $g_view_user_threshold = 110; // Niveau minimum pour voir la liste des membres

    # Niveau par défaut lors de la création d'un compte
    $g_default_new_account_access_level = 110;
//#endregion

//#region Bug Report Page Fields
    # Champs à afficher sur la page de création de ticket
    $g_bug_report_page_fields = array(
        'category_id',
        'severity',
        'priority',
        'summary',
        'description',
        'additional_information',
        'view_state',
        'handler',
        'tags',
        'attachments',
    );
//#endregion

//#region Bug Update Page Fields
    # Champs à afficher sur la page de mise à jour du ticket
    $g_bug_update_page_fields = array(
        'category_id',
        'view_state',
        'handler',
        'priority',
        'severity',
        'status',
        'summary',
        'description',
        'additional_information',
        'tags',
    );
//#endregion