<?php
switch( $g_active_language ) {
    case 'french':
        # TRADUCTION STATUTS (Lien avec config_inc.php)
        $s_status_enum_string = '
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

        # TRADUCTION ACCÈS
        $s_access_levels_enum_string = '
            999:SUPER_Admin,
            190:Admin,
            160:RCP,
            150:RRP,
            140:Développeur,
            130:Soutien,
            120:Testeur,
            110:Utilisateur
        ';

        $s_severity_enum_string = '
            10:Mineur,
            20:Majeur,
            30:Critique,
            40:Bloquant
        ';

        # BOUTONS
        //*
        $s_to_be_tested_bug_button = 'Prêt pour test';
        $s_to_be_tested_bug_title = 'Mettre en test';
        $s_suspended_bug_button = 'Suspendre';
        $s_suspended_bug_title = 'Mettre en attente';
        //*/
    break;

    default: # English
        # TRADUCTION STATUTS
        $s_status_enum_string = '
            100:new,
            101:assigned,
            110:qualified,
            120:analyzed,
            131:assign_RRP,
            132:assign_IS,
            133:assign_DEV,
            140:processed,
            151:to_deploy,
            160:to_test,
            161:factory_test,
            165:client_test,
            180:need_more_info,
            181:suspended,
            182:reopened,
            190:closed,
            191:cancelled,
            192:rejected
        ';

        # TRADUCTION ACCÈS
        $s_access_levels_enum_string = '
            999:SUPER_Admin,
            190:Admin,
            160:RCP,
            150:RRP,
            140:Developer,
            130:Support,
            120:Tester,
            110:User
        ';
        break;
}
?>