CREATE DATABASE `WALLAS` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

-- creacion de usuario (dandole todos los privilegios)
GRANT USAGE ON *.* TO 'wallas'@'localhost';
DROP USER 'wallas'@'localhost';
CREATE USER 'wallas'@'localhost' IDENTIFIED BY 'wallas';
GRANT ALL PRIVILEGES ON `wallas`.* TO 'wallas'@'localhost' WITH GRANT OPTION;

-- todas las consultas posteriores pertenecen a la base de datos wallas
USE `WALLAS`;

-- creacion de tabla USER
CREATE TABLE IF NOT EXISTS `USER` (
    `login` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'login del usuario, unico (ie, no puede haber dos usuarios con el mismo email)',
    `password` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Password del usuario. No puede ser nula',
    `fullname` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre y apellidos del usuario. No puede ser nulo.',
    `email` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Email del usuario',
    `numberPhone` int(9) NOT NULL COMMENT 'Numero de telefono del usuario.',
    `address` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Direccion del usuario. No puede ser nula.',
    `country` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Pais del usuario. No puede ser nulo.',
    PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de usuarios';

-- creacion de tabla SPENDING
CREATE TABLE IF NOT EXISTS `SPENDING` (
    `idSpending` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id del gasto, unico y auto incremental',
    `dateSpending` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'fecha y hora en la que es creado el gasto, no puede ser nulo',
    `quantity` int(8) NOT NULL COMMENT 'cantidad del gasto',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Email del autor del gasto, no puede ser nulo, clave foranea a USER.email',
    PRIMARY KEY (`idSpending`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de posts' AUTO_INCREMENT=1;

-- creacion de la tabla STOCK
CREATE TABLE IF NOT EXISTS `STOCK` (
    `idStock` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id del stock, unico y auto incremental',
    `revenue` int(8) NOT NULL COMMENT 'ingresos del usuario',  
    `dateStock` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'El estado del capital del usuario en determinada fecha',
    `total` int(8) NOT NULL COMMENT 'cantidad total de presupuesto del cual dispone el usuario', 
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del usuario, unico (ie, no puede haber dos usuarios con el mismo login)',
    PRIMARY KEY (`idStock`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de stock' AUTO_INCREMENT=1;

-- creacion de la tabla TYPE
CREATE TABLE IF NOT EXISTS `TYPE` (
    `idType` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id del tipo de gasto, unico y auto incremental',
    `name` int(8) NOT NULL COMMENT 'nombre del gasto',  
    PRIMARY KEY (`idType`) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de los tipos de gastos' AUTO_INCREMENT=1;

-- creacion de la tabla TYPE
CREATE TABLE IF NOT EXISTS `TYPE_SPENDING` (
    `idTypeSpending` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id de la relacion entre el tipo de gasto y el gasto, unico y auto incremental',
    `type` int(9) NOT NULL COMMENT 'id del tipo de gasto, unico y auto incremental',
    `spending` int(9) NOT NULL COMMENT 'id del gasto, unico y auto incremental',
    PRIMARY KEY (`idTypeSpending`), 
    FOREIGN KEY (`type`) REFERENCES `TYPE` (`idType`),
    FOREIGN KEY (`spending`) REFERENCES SPENDING (`idSpending`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla que relaciona los gastos con su tipo de gasto' AUTO_INCREMENT=1;
   
    








