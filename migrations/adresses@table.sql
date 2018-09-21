-- 
-- Table: adresses 
-- 
CREATE TABLE `adresses` (`id` int(11) NOT NULL ,
`ville` varchar(89) ,
`code_postal` varchar(89) ,
`adresses` text() ,
`complement` text() ,
`contact_id` relationnel() ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

-- 
-- Index 
-- 
ALTER TABLE `adresses` ADD PRIMARY KEY(`id`); 

-- 
-- AutoIncremen 
-- 
ALTER TABLE `adresses` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; 

