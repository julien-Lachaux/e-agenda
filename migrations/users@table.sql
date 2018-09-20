-- 
-- Table: users 
-- 
CREATE TABLE `users` (`id` int(11) NOT NULL ,
`login` varchar(89) ,
`password` varchar(256) ,
`email` varchar(89) ,
`nom` varchar(89) ,
`prenom` varchar(89) ) ENGINE=InnoDB DEFAULT CHARSET=utf8; 

-- 
-- Index 
-- 
ALTER TABLE `users` ADD PRIMARY KEY(`id`); 
ALTER TABLE `users` ADD UNIQUE(`login`); 

-- 
-- AutoIncremen 
-- 
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT; 

