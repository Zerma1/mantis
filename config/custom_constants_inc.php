<?php

# Custom status code
	define('NOUVEAU',100);
	define('PRIS_EN_CHARGE',101);
	define('QUALIFIER',110);
	define('ANALYSER',120);
	define('AFFECT_DEV',133);
	define('TRAITER',140);
	define('A_DEPLOYER',151);
	define('A_TESTER',160);
	define('VALIDE_USINE',161);
	define('RESOLU',162);
	define('DEMANDE_PRECISION',180);
	define('SUSPENDU',181);
	define('REOUVERT',182);
	define('FERME',190);
	define('ANNULE',191);
	define('REJETE',192);


# Custom Acces Level
	//
	define('SUP_ADMIN',999);

    define('ADMIN', 190);
	define('RCP', 160);
	define('RRP', 150);
	define('DEVELOPEUR', 140);
	define('SOUTIEN', 130);
	define('TESTEUR', 120);

	define('USER', 110);

# Custom sévéritée
    define("MINEUR", 10);
    define("MAJEUR",110);
    define("CRITIQUE",12);
    define("BLOQUANT",13);
