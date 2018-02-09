SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `text_content_serge` (
	`index_name` text  COLLATE utf8mb4_bin NOT NULL,
	`EN` text COLLATE utf8mb4_bin DEFAULT NULL,
	`FR` text COLLATE utf8mb4_bin DEFAULT NULL,
	`ES` text COLLATE utf8mb4_bin DEFAULT NULL,
	`DE` text COLLATE utf8mb4_bin DEFAULT NULL,
	`CN` text COLLATE utf8mb4_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;
CREATE INDEX index_text ON text_content_serge (index_name(30));

INSERT INTO `text_content_serge` (`index_name`, `EN`, `FR`, `ES`, `DE`, `CN`) VALUES
('main_title_index', 'Stay always up to date with Serge', 'Restez toujours à jour avec Serge', NULL, NULL, NULL),
('sub_title_index', 'Improving performance through news monitoring can often takes time.<br>
	By researching current news, patents and scientific publications, Serge allows you to stay informed efficiently and gives you time to do something else', 'L\'amélioration des performances grâce à la veille peut souvent prendre du temps.
En faisant des recherches dans l\'actualité, les brevets et les publications scientifiques, Serge vous permet de rester informé efficacement et vous donne le temps de faire autre chose', NULL, NULL, NULL),
('try1_button_index', 'Try for free', 'Essayer gratuitement', NULL, NULL, NULL),
('try2_button_index', 'Let me try&nbsp;!', 'Laissez-moi essayer&nbsp;!', NULL, NULL, NULL),
('comingSoon_button_index', 'Coming soon', 'Fonctionnalités à venir', NULL, NULL, NULL),
('functionality1_title_index', 'Follow RSS feeds', 'Suivre les flux RSS', NULL, NULL, NULL),
('functionality2_title_index', 'Patents', 'Brevets', 'Patentes', NULL, NULL),
('functionality3_title_index', 'Scientifics publications', 'Publications scientifiques', NULL, NULL, NULL),
('functionality4_title_index', 'Newsletter', 'Newsletter', NULL, NULL, NULL),
('functionality5_title_index', 'Hight customization', 'Haute personnalisation', NULL, NULL, NULL),
('functionality6_title_index', 'Effective history', 'Historique efficace', NULL, NULL, NULL),
('functionality7_title_index', 'RSS feed', 'Flux RSS', NULL, NULL, NULL),
('functionality8_title_index', 'Track twitter', 'Suivi de Twitter', NULL, NULL, NULL),
('functionality9_title_index', 'Wiki', 'Wiki', NULL, NULL, NULL),
('functionality10_title_index', 'Alert by SMS', 'Alerte par SMS', NULL, NULL, NULL),
('functionality11_title_index', 'Statistics', 'Statistiques', NULL, NULL, NULL),
('functionality1_text_index', 'Follow the RSS feeds of your favorite newspapers. Only receive news that interests you, using keywords or combinations of keywords', 'Suivez les flux RSS, de vos journaux préférés. Ne recevez que les actualités qui vous intéressent, en utilisant des mots-clefs ou des combinaisons de mots-clefs', NULL, NULL, NULL),
('functionality2_text_index', 'Always stay informed about new patents. Sort the types of patents that interest you in very specific ways', 'Restez toujours informé des nouveaux brevets déposés. Triez les types de brevets qui vous intéressent de manière très précise', NULL, NULL, NULL),
('functionality3_text_index', 'Receive the scientific pre-publications of ArXiv, as well as the free scientific publications. Select precisely which fields of research are important to you', 'Recevez les pré-publications scientifiques d\'ArXiv, ainsi que les publications scientifiques libres. Sélectionnez précisément les champs de recherches importants pour vous', NULL, NULL, NULL),
('functionality4_text_index', 'Your news watch results can be communicated to you in different ways, directly from the web site of Serge, or via a newsletter, of which you can choose the criteria of sending', 'Les résultats de votre veille peuvent vous être communiqués de différente manière, directement depuis le site de Serge, ou par l\'intermédiaire d\'une newsletter, dont vous pouvez choisir les critères d\'envoi', NULL, NULL, NULL),
('functionality5_text_index', 'On Serge, you can customize everything, depending on your needs and the time you can devote to your news watch. At any time you can change your options', 'Sur Serge, vous pouvez tout personnaliser, en fonction de vos besoins et du temps que vous pouvez consacrer à votre veille. À tout moment, vous pouvez changer vos options', NULL, NULL, NULL),
('functionality6_text_index', 'You can navigate through your history, to find articles that you have already received, thanks to a very efficient search engine and convenient criterias of sorting', 'Vous pouvez naviguer dans votre historique, pour retrouver les articles que vous avez déjà reçus, grâce à un moteur de recherche très efficace et des critères de tri pratiques', NULL, NULL, NULL),
('functionality7_text_index', 'Receive the results of your news watch from your favorite RSS reader', 'Recevez les résultats de votre veille depuis votre liseuse de flux RSS préférée', NULL, NULL, NULL),
('functionality8_text_index', 'Make your news and branding watch on Twitter. Serge will search for all tweets that match your criteria and you will be able to see how your brand is perceived', 'Faites votre veille d\'actualité et d\'image de marque sur Twitter. Serge cherchera tous les tweets qui correspondent à vos critères, vous pourrez ainsi voir comment votre marque est perçue', NULL, NULL, NULL),
('functionality9_text_index', 'A wiki will be set up, to classify, annotate and share the news, with your colleagues or in a totally public way', 'Un wiki sera mis en place, afin de classer, annoter et partager les actualités, avec vos collègues ou de manière totalement publique', NULL, NULL, NULL),
('functionality10_text_index', 'A feature of Serge allows you to receive news as soon as it is found, in the future you will be alerted of a precise news directly by SMS', 'Une fonctionnalité de Serge vous permet de recevoir les actualités dès qu\'elles sont trouvées, à l\'avenir, vous pourrez être alerté au sujet d\'une actualité précise, directement par SMS', NULL, NULL, NULL),
('functionality11_text_index', 'In order to improve your search criteria, statistics about your news watch will be available on the option page', 'Afin d\'améliorer vos critères de recherches, des statistiques seront bientôt disponibles sur la page des options', NULL, NULL, NULL),
('signup_title_index', 'Sign up', 'Inscription', NULL, NULL, NULL),
('input1_signup_index', 'Username', 'Nom d\'utilisateur', NULL, NULL, NULL),
('input2_signup_index', 'Passphrase', 'Phrase de passe', NULL, NULL, NULL),
('input3_signup_index', 'Passphrase again', 'Phrase de passe à nouveau', NULL, NULL, NULL),
('input4_signup_index', 'Email', 'Email', NULL, NULL, NULL),
('input5_signup_index', 'Captcha', 'Captcha', NULL, NULL, NULL),
('submit_signup_index', 'Sign up', 'Inscription', NULL, NULL, NULL),
('name_title_nav', 'Serge by Cairn Devices', 'Serge par Cairn Devices', NULL, NULL, NULL),
('tab1_title_nav', 'Results', 'Résultats', NULL, NULL, NULL),
('tab2_title_nav', 'Wiki', 'Wiki', NULL, NULL, NULL),
('tab3_title_nav', 'Settings', 'Options', NULL, NULL, NULL),
('copyright_title_footer', 'Cairn Devices 2017 GPLv3', 'Cairn Devices 2017 GPLv3', 'Cairn Devices 2017 GPLv3', 'Cairn Devices 2017 GPLv3', 'Cairn Devices 2017 GPLv3'),
('link1_center_footer', 'Cairn Devices', 'Cairn Devices', NULL, NULL, NULL),
('link2_center_footer', 'Legal', 'Légal', NULL, NULL, NULL),
('link3_center_footer', 'Privacy', 'Vie privée', NULL, NULL, NULL),
('link4_center_footer', 'Sign up', 'Inscription', NULL, NULL, NULL),
('link5_center_footer', 'Sign in', 'Connexion', NULL, NULL, NULL),
('link6_center_footer', 'Support', 'Support', NULL, NULL, NULL),
('link7_center_footer', 'Logout', 'Déconnexion', NULL, NULL, NULL),
('link8_center_footer', 'Contact us', 'Contactez nous', NULL, NULL, NULL),
('link9_center_footer', 'Press', 'Presse', NULL, NULL, NULL),
('legal_text_footer', 'Use Serge as a Free Software GPLv3 2017, by Cairn Devices SAS, company with share capital of 10 005 € based in France SIRET : 822 125 183 00019', 'Utilisez Serge comme un logiciel libre sous GPLv3 2017, par Cairn Devices SAS, société avec capital social de 10 005 € basé en France SIRET: 822 125 183 00019', NULL, NULL, NULL),
('title_text_connection', 'Connection', 'Connexion', NULL, NULL, NULL),
('input1_signin_connection', 'Username', 'Nom d\'utilisateur', NULL, NULL, NULL),
('input2_signin_connection', 'Passphrase', 'Phrase de passe', NULL, NULL, NULL),
('forgotPass_link_connection', 'Forgot your passphrase ?', 'Vous avez oublié votre mot de passe ?', NULL, NULL, NULL),
('noAccount_link_connection', 'You don\'t have an account ? Sign Up', 'Vous n\'avez pas de compte ? Inscrivez vous', NULL, NULL, NULL),
('submit_signin_connection', 'Sign in', 'Connexion', NULL, NULL, NULL),
('Bad id', 'Bad id', 'Mauvais identifiant', NULL, NULL, NULL),
('Bad passphrase', 'Bad passphrase', 'Mauvais mot de passe', NULL, NULL, NULL),
('title1_type_results', 'News', 'Actualités', NULL, NULL, NULL),
('title2_type_results', 'Sciences', 'Sciences', NULL, NULL, NULL),
('title3_type_results', 'Patents', 'Brevets', NULL, NULL, NULL),
('title1_table_results', 'Title', 'Titre', NULL, NULL, NULL),
('title2News_table_results', 'Keyword', 'Mot-clef', NULL, NULL, NULL),
('title2Science_table_results', 'Query', 'Requête', NULL, NULL, NULL),
('Query', 'Query', 'Requête', NULL, NULL, NULL),
('title3_table_results', 'Source', 'Source', NULL, NULL, NULL),
('title4_table_results', 'Date', 'Date', NULL, NULL, NULL),
('title5Send_table_results', 'Send', 'Envoyé', NULL, NULL, NULL),
('title5NotSend_table_results', 'Not send', 'Non envoyé', NULL, NULL, NULL),
('title6Read_table_results', 'Read', 'Lu', NULL, NULL, NULL),
('title6Unread_table_results', 'Unread', 'Non lu', NULL, NULL, NULL),
('title7_table_results', 'Wiki', 'Wiki', NULL, NULL, NULL),
('all_specialKeyword_results', 'All', 'Tout', NULL, NULL, NULL),
('main_title_setting', 'Setting', 'Options', NULL, NULL, NULL),
('window1_title_setting', 'General options', 'Options générales', NULL, NULL, NULL),
('input1_window1_setting', 'Your email', 'Votre email', NULL, NULL, NULL),
('subtitle1_window1_setting', 'Results page', 'Page de résultats', NULL, NULL, NULL),
('subtitle2_window1_setting', 'Sending condition', 'Condition d\'envoi', NULL, NULL, NULL),
('premiumPart_window1_setting', 'Premium account', 'Compte premium', NULL, NULL, NULL),
('input2_window1_setting', 'by number of links :', 'par nombre de liens', NULL, NULL, NULL),
('input3_window1_setting', 'by frequency, every', 'par fréquence, toutes les', NULL, NULL, NULL),
('input4_window1_setting', 'hours', 'heures', NULL, NULL, NULL),
('input5_window1_setting', 'at', 'à', NULL, NULL, NULL),
('input6_window1_setting', ', every', ', tout', NULL, NULL, NULL),
('input6.1_window1_setting', 'and', 'et', NULL, NULL, NULL),
('select1_window1_setting', 'business day', 'les jours ouvrés', NULL, NULL, NULL),
('select2_window1_setting', 'second business day', 'les deux jours ouvrés', NULL, NULL, NULL),
('select3_window1_setting', 'day', 'jours', NULL, NULL, NULL),
('select4_window1_setting', 'monday', 'les lundis', NULL, NULL, NULL),
('select5_window1_setting', 'tuesday', 'les mardis', NULL, NULL, NULL),
('select6_window1_setting', 'wednesday', 'les mercredis', NULL, NULL, NULL),
('select7_window1_setting', 'thursday', 'les jeudis', NULL, NULL, NULL),
('select8_window1_setting', 'friday', 'les vendredis', NULL, NULL, NULL),
('select9_window1_setting', 'saturday', 'les samedis', NULL, NULL, NULL),
('select10_window1_setting', 'sunday', 'les dimanches', NULL, NULL, NULL),
('select10.1_window1_setting', 'that\'s all', 'c\'est tout', NULL, NULL, NULL),
('subtitle3_window1_setting', 'Sorting for links in email', 'Triage des liens dans l\'email', NULL, NULL, NULL),
('input7_window1_setting', 'by keyword', 'par mot-clef', NULL, NULL, NULL),
('input8_window1_setting', 'by source', 'par source', NULL, NULL, NULL),
('input9_window1_setting', 'by alphabetical order', 'par ordre alphabétique', NULL, NULL, NULL),
('subtitle4_window1_setting', 'Privacy', 'Vie privée', NULL, NULL, NULL),
('subtitle5_window1_setting', 'WatchPack used', 'Pack de veille utilisé', NULL, NULL, NULL),
('subtitle6_window1_setting', 'RSS links', 'Lien des flux RSS', NULL, NULL, NULL),
('subsubtitle1_window1_setting', 'RSS link for news', 'Lien RSS pour les actualités', NULL, NULL, NULL),
('subsubtitle2_window1_setting', 'RSS link for science', 'Lien RSS pour les articles scientifiques', NULL, NULL, NULL),
('subsubtitle3_window1_setting', 'RSS link for patents', 'Lien RSS pour les brevets', NULL, NULL, NULL),
('input10_window1_setting', 'Record when a link is clicked', 'Enregistrer quand un lien est cliqué', NULL, NULL, NULL),
('input11_window1_setting', 'History', 'Historique', NULL, NULL, NULL),
('input11.1_window1_setting', 'the last', 'les dernières', NULL, NULL, NULL),
('select11_window1_setting', 'Hours', 'Heures', NULL, NULL, NULL),
('select12_window1_setting', 'Days', 'Jours', NULL, NULL, NULL),
('select13_window1_setting', 'Week', 'Semaines', NULL, NULL, NULL),
('select14_window1_setting', 'Month', 'Mois', NULL, NULL, NULL),
('select15_window1_setting', 'Year', 'Années', NULL, NULL, NULL),
('window2_title_setting', 'News management', 'Gestion des actualités', NULL, NULL, NULL),
('select1_window2_setting', 'All sources', 'Toutes les sources', NULL, NULL, NULL),
('select2_window2_setting', 'Add my own source', 'Ajouter ma propre source', NULL, NULL, NULL),
('Button1_window2_setting', 'Create my own watch pack', 'Créer mon propre pack de veille', NULL, NULL, NULL),
('Button2_window2_setting', 'Add community watch pack', 'Ajouter un pack de veille communautaire', NULL, NULL, NULL),
('window3_title_setting', 'Science watch management', 'Gestion de la veille scientifique', NULL, NULL, NULL),
('window4_title_setting', 'Patent watch management', 'Gestion de la veille de brevet', NULL, NULL, NULL),
('title_window0_result', 'Watch result', 'Résultats de la veille', NULL, NULL, NULL),
('selectTitle_window1_setting', 'Background', 'Arrière plan', NULL, NULL, NULL),
('name1_type_watchpack', 'Search', 'Rechercher', NULL, NULL, NULL),
('name2_type_watchpack', 'Create', 'Créer', NULL, NULL, NULL),
('title0_window0_watchpack', 'Community watch packs', 'Pack de veille de la communauté', NULL, NULL, NULL),
('title1_window0_watchpack', 'Creation of watch packs', 'Création du pack de veille', NULL, NULL, NULL),
('input1_window0_watchpack', 'Name', 'Nom', NULL, NULL, NULL),
('select1_window0_watchpack', 'New watch Pack', 'Nouveau pack de veille', NULL, NULL, NULL),
('input2_window0_watchpack', 'Pack name', 'Nom du pack', NULL, NULL, NULL),
('input3_window0_watchpack', 'Category name', 'Nom de la catégorie', NULL, NULL, NULL),
('titleInput4_window0_watchpack', 'Description', 'Description', NULL, NULL, NULL),
('input4_window0_watchpack', 'Pack description', 'Description du pack', NULL, NULL, NULL),
('title_text_purchase', 'Purchase', 'Achat', NULL, NULL, NULL),
('submit_button_purchase', 'Access to payment', 'Accéder au paiement', NULL, NULL, NULL),
('input1_purchase_purchase', 'Premium subscription duration', 'Durée de l\'abonement premium', NULL, NULL, NULL),
('input1_text_purchase', 'months of premium subscription', 'mois d\'abonement premium', NULL, NULL, NULL),
('input2_purchase_purchase', 'Discount code', 'Code de réduction', NULL, NULL, NULL),
('input3_purchase_purchase', 'I have read the term of service.', 'J\'ai lu les conditions générales de service', NULL, NULL, NULL),
('errorMessageCaptcha', 'Bad captcha', 'Mauvais captcha', NULL, NULL, NULL),
('errorMessagePassphrase', 'Passphrase is too short or passphrase do not match', 'La phrase de passe est trop courte ou les phrases de passe ne correspondent pas', NULL, NULL, NULL),
('title0_forgotPass_connection', 'Forgot passphrase', 'Phrase de passe oubliée', NULL, NULL, NULL),
('submit0_forgotPass_connection', 'Submit', 'Envoyer', NULL, NULL, NULL),
('title1_forgotPass_connection', 'Reset passphrase', 'Changer la phrase de passe', NULL, NULL, NULL),
('checkMail_forgotPass_connection', 'Your request has been successfully sent, please check your emails to reset your password', 'Votre demande a été envoyée avec succès, veuillez vérifier vos emails, afin de réinitialiser votre mot de passe', NULL, NULL, NULL),
('invalidRequest_forgotPass_connection', 'Your request is invalid or too old', 'Votre demande est invalide ou trop ancienne', NULL, NULL, NULL),
('title0_premium_setting', 'Premium expiration date', 'Date d\'expiration de l\'abonnement premium', NULL, NULL, NULL),
('title1_premium_setting', 'Your payment history', 'Votre historique de paiement', NULL, NULL, NULL),
('title2_premium_setting', 'Your email need to be verify', 'Votre email doit être vérifé', NULL, NULL, NULL),
('button0_premium_setting', 'Extend your premium account duration', 'Étendre votre abonnement premium', NULL, NULL, NULL),
('button1_premium_setting', 'Upgrade your account to premium', 'Passez votre compte en premium', NULL, NULL, NULL),
('title0_title_purchase', 'Become premium', 'Devenez premium', NULL, NULL, NULL),
('title1_title_purchase', 'Access to all Serge\'s features', 'Accédez à toutes les fonctionnalités de Serge', NULL, NULL, NULL),
('title2_title_purchase', 'Successfully charged ', 'Facturation effectuée avec succès pour ', NULL, NULL, NULL),
('Unfold keyword list', 'Unfold the keyword list', 'Déplier les mots-clefs', NULL, NULL, NULL),
('Fold keyword list', 'Fold the keyword list', 'Replier les mots-clefs', NULL, NULL, NULL),
('Search', 'Search', 'Rechercher', NULL, NULL, NULL),
('Add', 'Add', '+', NULL, NULL, NULL),
('Name', 'Name', 'Nom', NULL, NULL, NULL),
('Author', 'Author', 'Auteur', NULL, NULL, NULL),
('Category', 'Category', 'Categorie', NULL, NULL, NULL),
('Date', 'Date', 'Date', NULL, NULL, NULL),
('Rate', 'Rate', 'Note', NULL, NULL, NULL),
('Create watchpack', 'Create watchpack', 'Créer le pack de veille', NULL, NULL, NULL),
('Save watchpack', 'Save watchpack', 'Sauvegarder le pack de veille', NULL, NULL, NULL),
('Sorry, the wiki has not yet been released', 'Sorry, the wiki has not yet been released', 'Désolé, le wiki n\'a pas encore été publié', NULL, NULL, NULL),
('Bug report', 'Bug report', 'Rapport de bug', NULL, NULL, NULL),
('I prefer open an issue on GitHub', 'I prefer open an issue on GitHub', 'Je préfère ouvrir un rapport de bug sur GitHub', NULL, NULL, NULL),
('Description of the problem', 'Description of the problem', 'Description du problème', NULL, NULL, NULL),
('Indicate the severity of the bug', 'Indicate the severity of the bug, what is the problem ? How to reproduce the problem ? What is the expected behaviour ? Be specific, remain factual, indicate all information (browser, OS, link) and finally, check the bug report', 'Indiquer la sévérité du bug, quel est le problème ? Comment reproduire le problème ? Quel est le comportement attendu ? Soyez précis, restez factuel, indiquez toutes les informations (navigateur, OS, lien) et vérifiez le rapport de bug', NULL, NULL, NULL),
('Submit bug report', 'Submit bug report', 'Envoyer le rapport de bogue', NULL, NULL, NULL),
('Bad email', 'Bad email', 'Mauvais email', NULL, NULL, NULL),
('Bad description', 'Bad description', 'Mauvais description', NULL, NULL, NULL),
('Your bug report has been sent successfull !', 'Your bug report has been sent successfully&nbsp;!', 'Votre rapport de bogue a été envoyé avec succès&nbsp;!', NULL, NULL, NULL),
('Bug ? Report here !', 'Bug ? Report here&nbsp;!', 'Bug ? Signalez le ici&nbsp;!', NULL, NULL, NULL),
('Delete selected links', 'Delete selected links', 'Supprimer les liens sélectionnés', NULL, NULL, NULL),
('Add in wiki', 'Add in wiki', 'Ajouter dans le wiki', NULL, NULL, NULL),
('Update email', 'Update email', 'Mise à jour de l\'email', NULL, NULL, NULL),
('Delete', 'Delete', 'Supprimer', NULL, NULL, NULL),
('Stay tuned', 'Stay tuned', 'Rester en contact', NULL, NULL, NULL),
('Remove selected watchPack', 'Remove selected watchPack', 'Retirer le pack de veille sélectionné', NULL, NULL, NULL),
('Copy RSS feed', 'Copy RSS feed', 'Copier flux RSS', NULL, NULL, NULL),
('Add new keyword', 'Add new keyword', 'Ajouter un nouveau mot-clef', NULL, NULL, NULL),
('Keyword, next keyword, ...', 'Keyword, next keyword, ...', 'Mot-clef, prochain mot-clef, ...', NULL, NULL, NULL),
('Add new source', 'Add new source', 'Ajouter une nouvelle source', NULL, NULL, NULL),
('Disable', 'Disable', 'Désactiver', NULL, NULL, NULL),
('Activate', 'Activate', 'Activer', NULL, NULL, NULL),
('Type', 'Type', 'Type', NULL, NULL, NULL),
('Extend', 'Extend', 'Étendre', NULL, NULL, NULL),
('Add new science query', 'Add new science query', 'Ajouter une nouvelle requête scientifique', NULL, NULL, NULL),
('Add new patents query', 'Add new patents query', 'Ajouter une nouvelle requête de brevets', NULL, NULL, NULL),
('Add watch pack', 'Add watch pack', 'Ajouter un pack de veille', NULL, NULL, NULL),
('title', 'Title', 'Titre', NULL, NULL, NULL),
('author', 'Author', 'Auteur', NULL, NULL, NULL),
('abstract', 'Abstract', 'Résumé', NULL, NULL, NULL),
('publisher', 'Publisher', 'Éditeur', NULL, NULL, NULL),
('category', 'Category', 'Catégorie', NULL, NULL, NULL),
('all', 'All', 'Tout', NULL, NULL, NULL),
('Edit query', 'Edit query', 'Éditer la requête', NULL, NULL, NULL),
('Keyword', 'Keyword', 'Mot-clef', NULL, NULL, NULL),
('Source', 'Source', 'Source', NULL, NULL, NULL),
('GitHub', 'GitHub', 'GitHub', NULL, NULL, NULL),
('User guide', 'User guide', 'Guide utilisateur', NULL, NULL, NULL),
('', '', '', NULL, NULL, NULL);
