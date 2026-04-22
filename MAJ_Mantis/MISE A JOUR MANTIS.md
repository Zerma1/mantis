# MISE À JOUR MANTIS

Mantis ancienne version : **MantisBT**-2.5.0  
Mantis nouvelle version : **MantisBT**-2.27.3  

## Documentation

- [Documentation officielle de Mantis](https://**MantisBT**.org/support.php)
- Rapport de stage de Vianney TOUCHARD, supervisé par José REYES :  
  [mantis-cepn-rapport-vianney-touchard.pdf](O:\...\mantis-cepn-rapport-vianney-touchard.pdf)  

Forum et guides pour modifier les états :

- [Forum Mantis : Creating custom statuses](https://**MantisBT**.org/forums/viewtopic.php?t=6351)
- [Admin guide](https://**MantisBT**.org/manual/)
- [guide de customisation de mantis](https://**MantisBT**.org/manual/#admin.customize)
- [Guide Customizing Status Values](https://**MantisBT**.org/manual/#admin.customize.status)  

## Ajout de l’extension CEPN

- ajout du dossier ``_mcosio`` dans le dossier racine de **MantisBT**-2.27.3  
- ajout du dossier ``OutdatedBrowser`` dans le dossier ``./plugins``
- importer l’extension dans la page d’administration de **MantisBT**  

## Tutoriel

- Etape 1 : dans le fichier ``./config/custom_constants_inc.php``

	- définir le nouveau statut ou niveau d'accès comme suit  
	  ``` php
	    <?php
	    # Custom status code
		    define( '[nom du statut]', [niveau du statut (int)] );
	    # Custom Acces Level  
		    define('[NOM_ACCES]',[niveau d acces (int)]); 
		    // X+1 pocède les acces de X
	
	  ```

- Etape 2 : dans le fichier ``./config/config_inc.php``

	- redéfinir l'ordre des statuts
	  ```php 
		# Correction de l'espace dans 'to_be_tested'
		$g_status_enum_string = '  
			[niveau du statut (int)]:[nom du statut],
			[niveau du statut (int)]:[nom du statut],
			[niveau du statut (int)]:[nom du statut],
			[niveau du statut (int)]:[nom du statut],
			[niveau du statut (int)]:[nom du statut]  
		';
	  ```
	  
	- definir la couleur 
	  ``` php
		  $s_status_colors = array(  
		    [niveau du statut (int)] => '[Couleur en hexa decimal]',
		    [niveau du statut (int)] => '[Couleur en hexa decimal]',
		    [niveau du statut (int)] => '[Couleur en hexa decimal]',
		    [niveau du statut (int)] => '[Couleur en hexa decimal]',
		    [niveau du statut (int)] => '[Couleur en hexa decimal]',
		    [niveau du statut (int)] => '[Couleur en hexa decimal]'
		  );
	  ```

	- les niveau d'acces
	  ```php
		//#region Niveau d'acces  
			# Niveaux d'accès    
			$g_access_levels_enum_string = '  
				[niveau d acces (int)]:[NOM_ACCES],    
				[niveau d acces (int)]:[NOM_ACCES],    
				[niveau d acces (int)]:[NOM_ACCES]
			';  
			
		//#endregion Niveau d'acces
	  ```
	  
	- définir le ``workFlow`` se fait dans l'application dans l'onglet Manage (ecrouts)/configuration/workflow Transitions

- Etape 3 : dans le fichier ``./config/custom_strings_inc.php``
	- inclure les traduction
		``` php
			<?php  
			switch( $g_active_language ) {  
			    case 'french':  
					# TRADUCTION STATUTS (Lien avec config_inc.php)  
					$s_status_enum_string = '  
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction]';  
					  
					 # TRADUCTION ACCÈS  
					$s_access_levels_enum_string = 
					'[niveau d acces (int)]:[traduction],
					[niveau d acces (int)]:[traduction],
					[niveau d acces (int)]:[traduction]';  
					  
				 break;  
			  
				default: # English  
					# TRADUCTION STATUTS (Lien avec config_inc.php)  
					$s_status_enum_string = '  
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction],
					[niveau du statut (int)]:[traduction]';  
					
					# TRADUCTION ACCÈS  
					$s_access_levels_enum_string = 
					'[niveau d acces (int)]:[traduction],
					[niveau d acces (int)]:[traduction],
					[niveau d acces (int)]:[traduction]';  
					
				 break;  
				}  
			?>
		```

## Personnalisation des Niveaux de Sévérité

	Etape 2 : dans le fichier ./config/config_inc.php
•
◦
définir l'ordre et les libellés des niveaux de sévérité
•
 ```php
•
   //#region Severitée
•
       $g_severity_enum_string = '
•
           [niveau de sévérité (int)]:[Libellé],
•
           [niveau de sévérité (int)]:[Libellé],
•
           [niveau de sévérité (int)]:[Libellé]'
•
       ;
•
   //#endregion Severitée
•
PHP
+        # Custom sévéritée
+            define("[NOM_SEVERITE]", [niveau de sévérité (int)]);
+      
+- Dans ./config/config_inc.php:
•
PHP
+        //#region Severitée
+            $g_severity_enum_string = '
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé]'
+            ;
+        //#endregion Severitée
+      
•
$g_severity_enum_string = '
•
   ...
•
   11:Majeur,
•
   ...';
•
PHP
+        //#region Severitée
+            $g_severity_enum_string = '
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé]'
+            ;
+        //#endregion Severitée
+      
•
La constante MAJEUR est définie avec la valeur 110, tandis que le libellé Majeur dans $g_severity_enum_string est associé à la valeur 11. Il est recommandé d'harmoniser ces valeurs pour éviter toute confusion ou comportement inattendu.
•
+- De plus, la valeur 110 utilisée pour la constante MAJEUR dans ./config/custom_constants_inc.php est également utilisée pour d'autres définitions :
•
◦
Statut QUALIFIER (110)
•
◦
Niveau d'accès USER (110)
•
Bien que MantisBT gère ces énumérations séparément, l'utilisation de valeurs numériques identiques pour des concepts différents peut prêter à confusion. Il est préférable d'utiliser des plages de valeurs distinctes pour chaque type d'énumération (statuts, niveaux d'accès, sévérités) afin d'améliorer la clarté et la maintenabilité du code.
Exemples expliquer
@@ -134,6 +163,12 @@ define('ADMIN', 190);
		define('USER', 110);
•
•
       # Custom sévéritée
•
       define("MINEUR", 10);
•
       define("MAJEUR",110);
•
       define("CRITIQUE",12);
•
       define("BLOQUANT",13);
```
◦
fichier ./config/config_inc.php
PHP
+        //#region Severitée
+            $g_severity_enum_string = '
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé],
+                [niveau de sévérité (int)]:[Libellé]'
+            ;
+        //#endregion Severitée
+
@@ -190,6 +225,14 @@ ';
//#endregion Niveau d'acces  
•
•
//#region Severitée
•
•
$g_severity_enum_string = '
•
10:Mineur,
•
11:Majeur,
•
12:Critique,
•
13:Bloquant';
•
•
//#endregion Severitée

//#region  
# Autoriser l'administrateur (190) à gérer vos nouveaux niveaux

## Point important

### problème des @xx@

lorsqu'un statu ou un niveau d'accès est noté @xx@ `exemple : @100@` cela indique un oublie, soit dans la traduction soit dans la redéfinition dans `./config/config_inc.php`.
vérifier que le niveau est bien présent partout, si ce n'est pas sa, se référencer a la doc officiel

## Exemples expliquer

- extrait des paramètre **MantisBT-2.27.3 01.04.2026**
	- fichier ``./config/custom_constants_inc.php``
	  ```php
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
			//ces niveau son definis en plus des niveau par défaut a un niveau supperieur 
			define('SUP_ADMIN',999);  
			  
			define('RCP', 160);  
			define('RRP', 150);  
			define('DEVELOPEUR', 140);  
			define('SOUTIEN', 130);  
			define('TESTEUR', 120);  
			define('ADMIN', 190);  
			  
			define('USER', 110);
	  ```
	- fichier ``./config/config_inc.php``
	  ```php
	  <?php  
		$g_hostname               = 'localhost';  
		$g_db_type                = 'mysqli';  
		$g_database_name          = 'bugtracker';  
		$g_db_username            = 'root';  
		$g_db_password            = '';  
		$g_default_timezone       = 'UTC';  
		$g_crypto_master_salt     = 'MTDB6MKij6FmCHqbH3lV/GyNgKFbsc8RMKQ4SMeQvPM=';  
		$g_path                   = 'http://localhost/**MantisBT**-2.27.3/';  
		  
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
		  
		    # Couleurs  
		        $s_status_colors = array(  
		            100 => '#FF0000', // Nouveaux (Rouge pur)  
		            101 => '#FF6600', // Pris en charge (Orange vif)  
		            110 => '#00FFFF', // Qualifié (Cyan électrique)  
		            120 => '#007BFF', // Analysé (Bleu royal)  
		            133 => '#FF00FF', // Affecté DEV (Magenta)  
		            140 => '#FFFF00', // Traité (Jaune vif)  
		            151 => '#ADFF2F', // À déployer (Vert acide)  
		            160 => '#32CD32', // À tester (Vert lime)  
		            161 => '#00FF7F', // Validé Usine (Vert printemps)  
		            162 => '#00FF00', // Résolu (Vert pur)  
		            170 => '#FF4500', // Rejeté (Orange brûlé)  
		            180 => '#C0C0C0', // Demande précision (Gris clair)  
		            181 => '#800080', // Suspendu (Violet)  
		            182 => '#FF1493', // Réouvert (Rose profond)  
		            190 => '#4B0082', // Fermé (Indigo)  
		            191 => '#696969', // Annulé (Gris foncé)  
		            192 => '#000000'  // Rejeté final (Noir)  
		        );  
		  
		//#endregion Customizing Status Values  
		  
		//#region Niveau d'acces  
		    # Niveaux d'accès    
			    $g_access_levels_enum_string = '  
					110:USER,    
					120:RCP,    
					130:RF,    
					140:RRP,    
					150:DEVELOPPEUR,    
					160:TESTEUR,    
					170:SOUTIEN,    
					190:Admin,    
					999:SUP_ADMIN
				';  
		  
		//#endregion Niveau d'acces  
		  
		//#region  
		    # Autoriser l'administrateur (190) à gérer vos nouveaux niveaux    
		    $g_manage_user_threshold = ADMIN;   
		  
		    # Seuil pour voir les autres utilisateurs  
		    $g_view_user_threshold = 110; // Niveau minimum pour voir la liste des membres  
		  
		    # Niveau par défaut lors de la création d'un compte    $g_default_new_account_access_level = 110;  
		//#endregion
	  ```
	- fichier ``./config/custom_strings_inc.php``
	  ```php
		<?php  
			switch( $g_active_language ) {  
				case 'french':  
				# TRADUCTION STATUTS (Lien avec config_inc.php)  
				$s_status_enum_string = '  
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
				  
				# TRADUCTION ACCÈS  
				$s_access_levels_enum_string = '
					999:SUPER_Admin,
					190:Admin,
					160:RCP,
					150:RRP,
					140:Développeur,
					130:Soutien,
					120:Testeur,  
					110:utilisateur
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
					$s_status_enum_string =  
						'100:new,110:analyse,  
						120:qualify,  
						131:assign_RRP,  
						132:assign_IS,  
						133:assign_DEV,  
						140:process,  
						151:to_deploy,  
						160:factory_test,  
						165:client_test,  
						170:validate,  
						180:closed,  
						181:suspended,  
						182:cancelled,  
						183:rejected
					';  
					$s_access_levels_enum_string =  
						'110:user,  
						120:RCP,  
						130:RF,  
						140:RRP,  
						150:Developer,  
						160:Tester,  
						170:Support,  
						190:Admin,  
						999:SUP_ADMIN
					';  
				break;  
			}  
		?>
	  ```


## Notes de l'auteur
Auteur : Baptiste Zermani
Dernière édition : 01/04/2026 - 16h13
Fichier : Markdown