CREATE DATABASE `wallas` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

-- creacion de usuario (dandole todos los privilegios)
GRANT USAGE ON *.* TO 'wallas'@'localhost';
DROP USER 'wallas'@'localhost';
CREATE USER 'wallas'@'localhost' IDENTIFIED BY 'wallaspass';
GRANT ALL PRIVILEGES ON `wallas`.* TO 'wallas'@'localhost' WITH GRANT OPTION;

-- todas las consultas posteriores pertenecen a la base de datos wallas
USE `wallas`;

-- creacion de tabla USER
CREATE TABLE IF NOT EXISTS `USER` (
    `login` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'login del usuario, unico (ie, no puede haber dos usuarios con el mismo email)',
    `password` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Password del usuario. No puede ser nula',
    `fullname` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre y apellidos del usuario. No puede ser nulo.',
    `email` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Email del usuario',
    `phone` int(9) NOT NULL COMMENT 'Numero de telefono del usuario.',
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
    `dateStock` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'El estado del capital del usuario en determinada fecha',
    `total` int(8) NOT NULL COMMENT 'cantidad total de presupuesto del cual dispone el usuario', 
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del usuario, unico (ie, no puede haber dos usuarios con el mismo login)',
    PRIMARY KEY (`idStock`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de stock' AUTO_INCREMENT=1;

-- creacion de la tabla REVENUE
CREATE TABLE IF NOT EXISTS `REVENUE` (
    `idRevenue` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id del ingreso, unico y auto incremental',
    `dateRevenue` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'El estado del capital del usuario en determinada fecha',
    `quantity` int(8) NOT NULL COMMENT 'cantidad del gasto',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del usuario, unico (ie, no puede haber dos usuarios con el mismo login)',
    PRIMARY KEY (`idRevenue`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de ingresos' AUTO_INCREMENT=1;


-- creacion de la tabla TYPE
CREATE TABLE IF NOT EXISTS `TYPE` (
    `idType` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id del tipo de gasto, unico y auto incremental',
    `dateType` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'El estado del capital del usuario en determinada fecha',
    `name` varchar(40) NOT NULL COMMENT 'nombre del gasto',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del usuario, unico (ie, no puede haber dos usuarios con el mismo login)',
    PRIMARY KEY (`idType`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de los tipos de gastos' AUTO_INCREMENT=1;

-- creacion de la tabla TYPE

CREATE TABLE IF NOT EXISTS `TYPE_SPENDING` (
    `idTypeSpending` int(9) NOT NULL AUTO_INCREMENT COMMENT 'id de la relacion entre el tipo de gasto y el gasto, unico y auto incremental',
    `type` int(9) NOT NULL COMMENT 'id del tipo de gasto, unico y auto incremental',
    `spending` int(9) NOT NULL COMMENT 'id del gasto, unico y auto incremental',
    PRIMARY KEY (`idTypeSpending`), 
    FOREIGN KEY (`type`) REFERENCES `TYPE` (`idType`),
    FOREIGN KEY (`spending`) REFERENCES `SPENDING` (`idSpending`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla que relaciona los gastos con su tipo de gasto' AUTO_INCREMENT=1;
  
INSERT INTO `USER` (`login`,`password`,`fullname`,`email`,`phone`,`address`,`country`) VALUES
('adri229','adri229','adrian gonzalez','adri229@gmailcom','988102030','Ourense','Spain'),
('adri339','adri339','adrian dominguez','adri339@gmailcom','988102030','Ourense','Spain'),
('adri449','adri449','adrian perez','adri449@gmailcom','988102030','Ourense','Spain'),
('adri559','adri559','adrian martinez','adri559@gmailcom','988102030','Ourense','Spain'),
('adri669','adri669','adrian vazquez','adri669@gmailcom','988102030','Ourense','Spain');    


INSERT INTO `SPENDING` (`dateSpending`,`quantity`,`owner`) VALUES 
('2016-02-10 23:00:00', '20', 'adri229'),
('2016-02-10 00:00:00', '40', 'adri229'),
('2016-02-13 23:00:00', '25', 'adri229'),
('2016-02-15 23:00:00', '10', 'adri229'),
('2016-02-15 00:00:00', '50', 'adri229'),
('2016-02-16 03:00:00', '20', 'adri229'),
('2016-02-20 23:00:00', '45', 'adri229'),
('2016-02-20 10:00:00', '15', 'adri229'),
('2016-02-20 18:00:00', '60', 'adri229'),
('2016-02-20 23:00:00', '50', 'adri229'),
('2016-02-21 13:00:00', '100', 'adri229'),
('2016-02-21 23:00:00', '25', 'adri229'),
('2016-02-22 03:00:00', '0', 'adri229'),
('2016-02-22 12:00:00', '0', 'adri229'),
('2016-02-22 15:00:00', '10', 'adri229'),
('2016-02-22 20:00:00', '200', 'adri229'),
('2016-02-24 20:00:00', '5', 'adri229'),
('2016-02-25 15:00:00', '25', 'adri229'),
('2016-02-25 23:00:00', '60', 'adri229'),
('2016-02-27 23:00:00', '20', 'adri229'),
('2016-02-28 12:00:00', '100', 'adri229');



INSERT INTO `REVENUE` (`dateRevenue`,`quantity`,`owner`) VALUES
('2016-02-10 23:00:00', '400', 'adri229'),
('2016-02-10 00:00:00', '50', 'adri229'),
('2016-02-13 23:00:00', '10', 'adri229'),
('2016-02-15 23:00:00', '40', 'adri229'),
('2016-02-15 00:00:00', '20', 'adri229'),
('2016-02-16 03:00:00', '30', 'adri229'),
('2016-02-20 23:00:00', '25', 'adri229'),
('2016-02-20 10:00:00', '15', 'adri229'),
('2016-02-20 18:00:00', '10', 'adri229'),
('2016-02-20 23:00:00', '30', 'adri229'),
('2016-02-21 13:00:00', '15', 'adri229'),
('2016-02-21 23:00:00', '5', 'adri229'),
('2016-02-22 03:00:00', '30', 'adri229'),
('2016-02-22 12:00:00', '600', 'adri229'),
('2016-02-22 15:00:00', '10', 'adri229'),
('2016-02-22 20:00:00', '30', 'adri229'),
('2016-02-24 20:00:00', '40', 'adri229'),
('2016-02-25 15:00:00', '0', 'adri229'),
('2016-02-25 23:00:00', '0', 'adri229'),
('2016-02-27 23:00:00', '20', 'adri229'),
('2016-02-28 12:00:00', '0', 'adri229');



INSERT INTO `STOCK` (`dateStock`,`total`,`owner`) VALUES
('2016-02-10 23:00:00', '380', 'adri229'),
('2016-02-10 00:00:00', '390', 'adri229'),
('2016-02-13 23:00:00', '375', 'adri229'),
('2016-02-15 23:00:00', '405', 'adri229'),
('2016-02-15 00:00:00', '375', 'adri229'),
('2016-02-16 03:00:00', '385', 'adri229'),
('2016-02-20 23:00:00', '365', 'adri229'),
('2016-02-20 10:00:00', '365', 'adri229'),
('2016-02-20 18:00:00', '315', 'adri229'),
('2016-02-20 23:00:00', '295', 'adri229'),
('2016-02-21 13:00:00', '210', 'adri229'),
('2016-02-21 23:00:00', '190', 'adri229'),
('2016-02-22 03:00:00', '220', 'adri229'),
('2016-02-22 12:00:00', '880', 'adri229'),
('2016-02-22 15:00:00', '880', 'adri229'),
('2016-02-22 20:00:00', '710', 'adri229'),
('2016-02-24 20:00:00', '745', 'adri229'),
('2016-02-25 15:00:00', '720', 'adri229'),
('2016-02-25 23:00:00', '660', 'adri229'),
('2016-02-27 23:00:00', '660', 'adri229'),
('2016-02-28 12:00:00', '660', 'adri229');



INSERT INTO `TYPE` (`dateType`,`name`,`owner`) VALUES
('2016-02-10 23:00:00','coffe','adri229'),
('2016-02-13 23:00:00','underground','adri229'),
('2016-02-15 23:00:00','bus','adri229'),
('2016-02-20 23:00:00','taxi','adri229'),
('2016-02-22 20:00:00','CocaCola','adri229'),
('2016-02-22 20:00:00','loaf','adri229');


INSERT INTO `TYPE_SPENDING` (`type`,`spending`) VALUES 
('1','1'),
('2','1'),
('5','1');
