-- 
-- Table: contacts 
-- 
CREATE TABLE `contacts` (`id` int(11) NOT NULL ,
`email` varchar(89) ,
`nom` varchar(89) ,
`prenom` varchar(89) ,
`user_id` relationnel() ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

-- 
-- Index 
-- 
ALTER TABLE `contacts` ADD PRIMARY KEY(`id`); 

-- 
-- AutoIncremen 
-- 
ALTER TABLE `contacts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; 

