--
-- Base: e_agenda_DEMO
--
CREATE DATABASE `e_agenda_DEMO` CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
USE e_agenda_DEMO;
--
-- Table: adresses
--
CREATE TABLE `adresses` (`id` int(11) NOT NULL ,
`pays` varchar(89) ,
`ville` varchar(89) ,
`codePostal` varchar(89) ,
`adresse` varchar(256) ,
`complementAdresse` text ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
--
-- Index pour la table: adresses
--
ALTER TABLE `adresses` ADD PRIMARY KEY(`id`) ;
--
-- Auto-increment pour la table: adresses
--
ALTER TABLE `adresses` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT; 
--
-- Table: contacts
--
CREATE TABLE `contacts` (`id` int(11) NOT NULL ,
`email` varchar(89) ,
`nom` varchar(89) ,
`prenom` varchar(89) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
--
-- Index pour la table: contacts
--
ALTER TABLE `contacts` ADD PRIMARY KEY(`id`) ;
--
-- Auto-increment pour la table: contacts
--
ALTER TABLE `contacts` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT; 
--
-- Table: utilisateurs
--
CREATE TABLE `utilisateurs` (`id` int(11) NOT NULL ,
`login` varchar(89) ,
`password` varchar(128) ,
`nom` varchar(89) ,
`prenom` varchar(89) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
--
-- Index pour la table: utilisateurs
--
ALTER TABLE `utilisateurs` ADD PRIMARY KEY(`id`) ;
ALTER TABLE `utilisateurs` ADD UNIQUE(`login`) ;
--
-- Auto-increment pour la table: utilisateurs
--
ALTER TABLE `utilisateurs` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT; 
ALTER TABLE `adresses` ADD COLUMN `contacts_id` INT UNSIGNED NOT NULL;
ALTER TABLE `adresses` ADD CONSTRAINT `FK_contacts_adresses` FOREIGN KEY (`contacts_id`) REFERENCES `contacts`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `contacts` ADD COLUMN `utilisateurs_id` INT UNSIGNED NOT NULL;
ALTER TABLE `contacts` ADD CONSTRAINT `FK_utilisateurs_contacts` FOREIGN KEY (`utilisateurs_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
