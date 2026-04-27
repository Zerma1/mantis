<?php
require_once('config_connexion_email-dev.php');

//#region Customizing Status Values

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


//#region Project Status
    /**
     * Project statuses enumeration.
     */

    $g_project_status_enum_string = '
        10:développement,
        20:test,
        30:release,
        40:fermé,
        50:stable,
        60:obsolète
    ';
//#endregion


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
