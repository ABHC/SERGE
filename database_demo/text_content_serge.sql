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
('main_title_index', 'Stay always update with Serge', 'Restez toujours à jour avec Serge', NULL, NULL, NULL),
('sub_title_index', 'Improving performance through news monitoring can often takes time.<br>
	By searching the news, patents and scientific publications, Serge allows you to stay update effectively and gives you time to do other thing', 'L\'amélioration des performances grâce à la veille peut souvent prendre du temps.
En recherchant les actualités, les brevets et les publications scientifiques, Serge vous permet de rester informé efficacement et vous donne le temps de faire autre chose', NULL, NULL, NULL),
('try1_button_index', 'Try for free', 'Essayer gratuitement', NULL, NULL, NULL),
('try2_button_index', 'Let me try !', 'Laissez moi essayer !', NULL, NULL, NULL),
('comingSoon_button_index', 'Coming soon', 'Fonctionnalités à venir', NULL, NULL, NULL),
('functionality1_title_index', 'Follow RSS feeds', 'Suivre les flux RSS', NULL, NULL, NULL),
('functionality2_title_index', 'Patents', 'Brevets', 'Patentes', NULL, NULL),
('functionality3_title_index', 'Scientifics publications', 'Publication scientifiques', NULL, NULL, NULL),
('functionality4_title_index', 'Newsletter', 'Newsletter', NULL, NULL, NULL),
('functionality5_title_index', 'Hight customization', 'Haute personalisation', NULL, NULL, NULL),
('functionality6_title_index', 'Effective history', 'Historique efficace', NULL, NULL, NULL),
('functionality7_title_index', 'RSS feed', 'Flux RSS', NULL, NULL, NULL),
('functionality8_title_index', 'Track twitter', 'Suivie de twitter', NULL, NULL, NULL),
('functionality9_title_index', 'Wiki', 'Wiki', NULL, NULL, NULL),
('functionality10_title_index', 'Alert by SMS', 'Alert par SMS', NULL, NULL, NULL),
('functionality11_title_index', 'Statistics', 'Statistique', NULL, NULL, NULL),
('functionality1_text_index', 'Follow the RSS feeds of your favorite newspapers. Only receive news that interests you, using keywords or combinations of keywords', 'Suivez les flux RSS, de vos journaux préférés. Ne recevez que les actualités qui vous intéresse, en utilisant des mots clefs ou des combinaisons de mots clefs', NULL, NULL, NULL),
('functionality2_text_index', 'Always stay informed about new patents. Sort the types of patents that interest you in very specific ways', 'Restez toujours informé des nouveaux brevets déposés. Triez les types de brevets qui vous intéressent de manières très précise', NULL, NULL, NULL),
('functionality3_text_index', 'Receive the scientific pre-publications of ArXiv, as well as the free scientific publications. Select precisely which fields of research are important to you', 'Recevez les pré-publications scientifiques d\'ArXiv, ainsi que les publications scientifiques libres. Sélectionnez précisément, les champs de recherches importants pour vous', NULL, NULL, NULL),
('functionality4_text_index', 'Your news watch results can be communicated to you in different ways, directly from the web site of Serge, or via a newsletter, of which you can choose the criteria of sending', 'Les résultats de votre veille peuvent vous être communiqué de différente manière, directement depuis le site de Serge, ou par l\'intermédiaire d\'une newsletter, dont vous pouvez choisir les critères d\'envoi', NULL, NULL, NULL),
('functionality5_text_index', 'On Serge, you can customize everything, depending on your needs and the time you can devote to your news watch. At any time you can change your options', 'Sur Serge, vous pouvez tout personnaliser, en fonction de vos besoins et du temps que vous pouvez consacrer à votre veille. À tout moment vous pouvez changer vos options', NULL, NULL, NULL),
('functionality6_text_index', 'You can navigate through your history, to find articles that you have already received, thanks to a very efficient search engine and convenient criterias of sorting', 'Vous pouvez naviguer dans votre historique, pour retrouver les articles que vous avez déjà reçu, grâce à un moteur de recherche très efficace et des critères de tri pratique', NULL, NULL, NULL),
('functionality7_text_index', 'Soon you will be able to receive the results of your news watch from your favorite RSS reader', 'Bientôt, vous pourrez recevoir les résultats de votre veille depuis votre liseuse de flux RSS préférée', NULL, NULL, NULL),
('functionality8_text_index', 'Make your news and branding watch on Twitter. Serge will search for all tweets that match your criteria and you will be able to see how your brand is perceived', 'Faite votre veille d\'actualité et d\'image de marque sur Twitter. Serge cherchera tous les tweets qui correspondent à vos critères et vous pourrez ainsi voir comment votre marque est perçue', NULL, NULL, NULL),
('functionality9_text_index', 'A wiki will be set up, to classify, annotate and share the news, with your colleagues or in a totally public way', 'Un wiki sera mis en place, afin de classer, annoter et partager les actualités, avec vos collègues ou de manière totalement public', NULL, NULL, NULL),
('functionality10_text_index', 'A feature of Serge allows you to receive news as soon as it is found, in the future you will be alerted of a precise news directly by SMS', 'Une fonctionnalité de Serge vous permet de recevoir les actualitées dès qu\'elles sont trouvées, à l\'avenir, vous pourrez être alerté au sujet d\'une actualité précise, directement par SMS', NULL, NULL, NULL),
('functionality11_text_index', 'In order to improve your search criteria, statistics about your news watch will be available on the option page', 'Afin d\'améliorer vos critères de recherche des statistiques seront bientôt disponible sur la page Option', NULL, NULL, NULL),
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
('tab3_title_nav', 'Settings', 'Options', NULL, NULL, NULL);
