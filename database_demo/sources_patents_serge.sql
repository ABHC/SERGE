SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `sources_patents_serge` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`apikey` int(11),
	`type` text COLLATE utf8mb4_bin NOT NULL,
	`basename` text COLLATE utf8mb4_bin NOT NULL,
	`name` text COLLATE utf8mb4_bin,
	`link` text COLLATE utf8mb4_bin,
	`prelink` text COLLATE utf8mb4_bin,
	`postlink` text COLLATE utf8mb4_bin,
	`AND` text COLLATE utf8mb4_bin DEFAULT NULL,
	`OR` text COLLATE utf8mb4_bin DEFAULT NULL,
	`NOT` text COLLATE utf8mb4_bin DEFAULT NULL,
	`(` text COLLATE utf8mb4_bin DEFAULT NULL,
	`)` text COLLATE utf8mb4_bin DEFAULT NULL,
	`quote` text COLLATE utf8mb4_bin DEFAULT NULL,
	`all_names` text COLLATE utf8mb4_bin DEFAULT NULL,
	`all_numbers` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_adr` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_adr_ctr` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_all_data` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_nat` text COLLATE utf8mb4_bin DEFAULT NULL,
	`app_res` text COLLATE utf8mb4_bin DEFAULT NULL,
	`apply_date` text COLLATE utf8mb4_bin DEFAULT NULL,
	`apply_number` text COLLATE utf8mb4_bin DEFAULT NULL,
	`chemical` text COLLATE utf8mb4_bin DEFAULT NULL,
	`country` text COLLATE utf8mb4_bin DEFAULT NULL,
	`designated_state` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_abstract` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_all` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_claims` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_description` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_all_txt` text COLLATE utf8mb4_bin DEFAULT NULL,
	`english_title` text COLLATE utf8mb4_bin DEFAULT NULL,
	`class_code` text COLLATE utf8mb4_bin DEFAULT NULL,
	`filling_lang` text COLLATE utf8mb4_bin DEFAULT NULL,
	`front_page` text COLLATE utf8mb4_bin DEFAULT NULL,
	`grant_number` text COLLATE utf8mb4_bin DEFAULT NULL,
	`class` text COLLATE utf8mb4_bin DEFAULT NULL,
	`class_crea` text COLLATE utf8mb4_bin DEFAULT NULL,
	`class_crea_n` text COLLATE utf8mb4_bin DEFAULT NULL,
	`exam_prime` text COLLATE utf8mb4_bin DEFAULT NULL,
	`int_research_aut` text COLLATE utf8mb4_bin DEFAULT NULL,
	`int_report` text COLLATE utf8mb4_bin DEFAULT NULL,
	`inv_all` text COLLATE utf8mb4_bin DEFAULT NULL,
	`inv_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`inv_nat` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_all` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_ctr` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_name` text COLLATE utf8mb4_bin DEFAULT NULL,
	`legal_adr` text COLLATE utf8mb4_bin DEFAULT NULL,
	`license` text COLLATE utf8mb4_bin DEFAULT NULL,
	`main_app` text COLLATE utf8mb4_bin DEFAULT NULL,
	`main_class` text COLLATE utf8mb4_bin DEFAULT NULL,
	`main_inv` text COLLATE utf8mb4_bin DEFAULT NULL,
	`main_legal` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_phase_data` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_phase_apply_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_phase_apply_date` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_phase_type` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_pub_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`office` text COLLATE utf8mb4_bin DEFAULT NULL,
	`nat_office` text COLLATE utf8mb4_bin DEFAULT NULL,
	`prior_apply_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`prior_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`priority` text COLLATE utf8mb4_bin DEFAULT NULL,
	`priority_ctr` text COLLATE utf8mb4_bin DEFAULT NULL,
	`priority_date` text COLLATE utf8mb4_bin DEFAULT NULL,
	`priority_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`pub_date` text COLLATE utf8mb4_bin DEFAULT NULL,
	`language` text COLLATE utf8mb4_bin DEFAULT NULL,
	`supplementary` text COLLATE utf8mb4_bin DEFAULT NULL,
	`third_party` text COLLATE utf8mb4_bin DEFAULT NULL,
	`wipo_num` text COLLATE utf8mb4_bin DEFAULT NULL,
	`french_abstract` text COLLATE utf8mb4_bin DEFAULT NULL,
	`french_all` text COLLATE utf8mb4_bin DEFAULT NULL,
	`french_claims` text COLLATE utf8mb4_bin DEFAULT NULL,
	`french_description` text COLLATE utf8mb4_bin DEFAULT NULL,
	`french_title` text COLLATE utf8mb4_bin DEFAULT NULL,
	`owners` VARCHAR(8000) COLLATE utf8mb4_bin DEFAULT ',',
	`active` int(11) NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

SET NAMES utf8mb4;

INSERT INTO `sources_patents_serge` (`id`, `apikey`, `type`, `basename`, `name`, `link`, `prelink`, `postlink`, `AND`, `OR`, `NOT`, `(`, `)`, `quote`, `all_names`, `all_numbers`, `app_adr`, `app_adr_ctr`, `app_all_data`, `app_name`, `app_nat`, 	`app_res`, `apply_date`, `apply_number`, `chemical`, `country`, `designated_state`, `english_abstract`, `english_all`, `english_claims`, `english_description`, `english_all_txt`, `english_title`, `class_code`, `filling_lang`, `front_page`, `grant_number`, `class`, `class_crea`, `class_crea_n`, `exam_prime`, `int_research_aut`, `int_report`, `inv_all`, `inv_name`, `inv_nat`, `legal_all`, `legal_ctr`, `legal_name`, `legal_adr`, `license`, `main_app`, `main_class`, `main_inv`, `main_legal`, `nat_phase_data`, `nat_phase_apply_num`, `nat_phase_apply_date`, `nat_phase_type`, `nat_pub_num`, `office`, `nat_office`, `prior_apply_num`, `prior_num`, `priority`, `priority_ctr`, `priority_date`, `priority_num`, `pub_date`, `language`, `supplementary`, `third_party`, `wipo_num`, `french_abstract`, `french_all`, `french_claims`, `french_description`, `french_title`, `active`) VALUES
(1, NULL, 'language', 'EN', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' NOT ', '(', ')', '"', 'All names like ', 'All numbers and ID like ', 'Applicant address like ', 'Applicant address country like ', 'Applicant all data like ', 'Applicant name like ', '"Applicant nationality : ', 'Applicant residence : ', 'Application date : ', 'Application number like ', 'Chemical : ', 'Country : ', 'Designated States : ', 'In english abstract : ', 'In all fields in english : ', 'In english claims : ', 'In english description : ', 'In all textes : ', 'In english title : ', 'Research with exact IPC Code : ', 'Research in filing Language : ', 'Research in front page : ', 'Grant number like : ', 'Patents class like : ', 'Patents class inventive like : ', 'Patents class N-inventive like : ', 'International Preliminary Examination like : ', 'International research authority like : ', 'International search report like like : ', 'Research in all data about the inventor : ', 'Inventor name like : ', 'Inventor nationality like : ', 'Research in all datas about the legal representative : ', 'Country of the legal representative : ', 'Name of the legal representative like : ', 'Adress of the legal representative like : ', 'Licensing availability like : ', 'Main applicant name like : ', 'Main patents class like : ', 'Main inventor name like : ', 'Main legal representative name like : ', 'National phase datas like : ', 'National phase application number like : ', 'National phase entry date like : ', 'National entry type like : ', 'National publication number like : ', 'Office code : ', 'National Phase Office Code like : ', 'PRIOR PCT application number : ', 'Prior PCT WO number : ', 'Priority data like : ', 'Priority country : ', 'Priority date like : ', 'Priority number like : ', 'Publication Date : ', 'Language publication : ', 'Supplementary International search : ', 'Third party observation : ', 'WIPO publication number : ', 'In french abstract : ', 'In all french : ', 'In french claims : ', 'In french description : ', 'In french title : ', 1),
(2, NULL, 'language', 'FR', NULL, NULL, NULL, NULL, ' ET ', ' OU ', ' EXCLUS ', '(', ')', '"', 'Tous les noms comme ', 'Tous les numéros et ID comme ', 'Addresse du déposant comme ', 'Pays de résidence du déposant : ', 'Données du déposant comme ', 'Nom du déposant comme ', 'Nationalité du Déposant : ', 'Domicile du déposant : ', 'Date de la demande : ', 'Numero de la Demande :', 'Chimie : ', 'Pays : ', 'Etats désignés : ', 'Dans l\'abrégé en anglais : ', 'Dans tous les champs en anglais : ', 'Dans les revendications en anglais : ', 'Dans la description en anglais : ', 'Dans tous les textes : ', 'Dans les titres en anglais : ', 'Rechercher avec la classe internationale exact : ', 'Langue dans laquelle la demande à été déposée : ', 'Rechercher dans la page de couverture : ', 'Numero de délivrance : ', 'Rechercher avec la classe internationale : ', 'Classe internationale inventive : ', 'Classe internationale non-inventive : ', 'Examen préliminaire internationale : ', 'Aurtorité de recherche internationale : ', 'Rapport de recherche internationale : ', 'Rechercher dans les donnée de l\'inventor : ', 'Nom de l\'inventeur : ', 'Nationalité de l\'inventeur : ', 'Rechercher dans toutes les données du représentant légal : ', 'Pays du représentant légal : ', 'Nom du représentant légal : ', 'Adresse du représentant légal : ', 'Demande de signalement aux fins de license : ', 'Nom du déposant principal : ', 'Classe internationale principale : ', 'Nom de l\'inventeur principal : ', 'Nom du représentant légal : ', 'Dans toutes les données dela phase nationale : ', 'Numéro de la demande en phase nationale : ', 'Date d\'entrée dans la phase nationale : ', 'Type d\'entrée en phase nationale : ', 'Numéro de publication national : ', 'Code de l\'office : ', 'Code de l\'office en phase nationale : ', 'Numero de la demande PCT antérieure : ', 'Numero PCT WO antérieur : ', 'Dans toutes les données de priorités : ', 'Pays de priorité : ', 'Date de priorité : ', 'Numero de priorité : ', 'Date de publication : ', 'Langue de publication : ', 'Recherche internationale supplémentaire : ', 'Observation formulée par un tiers : ', 'Numéro de publication OMPI : ', 'Dans l\'abrégé en français : ', 'Dans tous les champs en français : ', 'Dans les revendications en français : ', 'Dans la description en français : ', 'Dans les titres en français : ', 1),
(3, NULL, 'language', 'ES', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' NOT ', '(', ')', '"', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0),
(4, NULL, 'language', 'DE', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' NOT ', '(', ')', '"', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0),
(5, NULL, 'language', 'CN', NULL, NULL, NULL, NULL, ' AND ', ' OR ', ' NOT ', '(', ')', '"', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0),
(6, NULL, 'RSS', 'wipo', 'WIPO : World Intellectual Property Organization', 'http://www.wipo.int/','https://patentscope.wipo.int/search/rss.jsf?query=', '&office=&rss=true&sortOption=Pub+Date+Desc', '+AND+', '+OR+', NULL, NULL, NULL, NULL, 'ALLNAMES%3A', 'ALLNUM%3A', 'AAD%3A', 'AADC%3A', 'PAA%3A', 'PA%3A', 'ANA%3A', 'ARE%3A', 'AD%3A', 'AN%3A', 'CHEM%3A', 'CTR%3A', 'DS%3A', 'EN_AB%3A', 'EN_ALL%3A', 'EN_CL%3A', 'EN_DE%3A', 'ALLTXT%3A', 'EN_TI%3A', 'IC_EX%3A', 'LGF%3A', 'FP%3A', 'GN%3A', 'IC%3A', 'ICI%3A', 'ICN%3A', 'IPE%3A', 'ISA%3A', 'ISR%3A', 'INA%3A', 'IN%3A', 'IADC%3A', 'RPA%3A', 'RCN%3A', 'RP%3A', 'RAD%3A', 'LI%3A', 'PAF%3A', 'ICF%3A', 'INF%3A', 'RPF%3A', 'NPA%3A', 'NPAN%3A', 'NPED%3A', 'NPET%3A', 'PN%3A', 'OF%3A', 'NPCC%3A', 'PRIORPCTAN%3A', 'PRIORPCTWO%3A', 'PI%3A', 'PCN%3A', 'PD%3A', 'NP%3A', 'DP%3A', 'LGP%3A', 'SIS%3A', 'TPO%3A', 'WO%3A', 'FR_AB%3A', 'FR_ALL%3A', 'FR_CL%3A', 'FR_DE%3A','FR_TI%3A', 1);
