CREATE DATABASE `wallas` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;

-- creacion de usuario (dandole todos los privilegios)
/*GRANT USAGE ON *.* TO 'wallas'@'localhost';*/
DROP USER 'wallas'@'localhost';
CREATE USER 'wallas'@'localhost' IDENTIFIED BY 'wallaspass';
GRANT ALL PRIVILEGES ON `wallas`.* TO 'wallas'@'localhost' WITH GRANT OPTION;

USE `wallas`;

-- creacion de tabla USER
CREATE TABLE IF NOT EXISTS `USER` (
    `login` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Clave primaria que identifica a cada usuario.',
    `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Password que el usuario utiliza para iniciar sesión. No puede ser nula',
    `fullname` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre y apellidos del usuario. No puede ser nulo.',
    `email` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Email del usuario, no puede ser nulo',
    `phone` int(9) NOT NULL COMMENT 'Numero de telefono del usuario, no puede ser nulo.',
    `country` varchar(60) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Pais del usuario. No puede ser nulo.',
    PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de usuarios';

-- creacion de tabla SPENDING
CREATE TABLE IF NOT EXISTS `SPENDING` (
    `idSpending` int(9) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria que identifica a cada gasto',
    `dateSpending` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha y hora en la que es creado el gasto, no puede ser nulo',
    `quantity` int(8) NOT NULL COMMENT 'Cantidad del gasto, no puede ser nula',
    `name` varchar(40) NOT NULL COMMENT 'Nombre del gasto, no puede ser nulo',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del creador del gasto, no puede ser nulo, clave foranea a USER.login',
    PRIMARY KEY (`idSpending`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de gastos' AUTO_INCREMENT=1;

-- creacion de la tabla STOCK
CREATE TABLE IF NOT EXISTS `STOCK` (
    `idStock` int(9) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria que identifica a cada saldo',
    `dateStock` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha en la que se crea el saldo',
    `total` int(8) NOT NULL COMMENT 'cantidad total de presupuesto del cual dispone el usuario, no puede ser nula', 
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del creador del saldo, no puede ser nulo, clave foranea a USER.login',
    PRIMARY KEY (`idStock`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de saldos' AUTO_INCREMENT=1;

-- creacion de la tabla REVENUE
CREATE TABLE IF NOT EXISTS `REVENUE` (
    `idRevenue` int(9) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria que identifica a cada ingreso',
    `dateRevenue` timestamp COLLATE utf8_spanish_ci NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'El estado del capital del usuario en determinada fecha',
    `quantity` int(8) NOT NULL COMMENT 'Cantidad del ingreso, no puede ser nula',
    `name` varchar(40) NOT NULL COMMENT 'Nombre del ingreso, no puede ser nulo',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del creador del ingreso, no puede ser nulo, clave foranea a USER.login',
    PRIMARY KEY (`idRevenue`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de ingresos' AUTO_INCREMENT=1;


-- creacion de la tabla TYPE
CREATE TABLE IF NOT EXISTS `TYPE` (
    `idType` int(9) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria del tipo de gasto, unico y auto incremental',
    `name` varchar(40) NOT NULL COMMENT 'Nombre del tipo de gasto, no puede ser nulo',
    `owner` varchar(40) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Login del creador del tipo, no puede ser nulo, clave foranea a USER.login',
    UNIQUE(name),
    PRIMARY KEY (`idType`),
    FOREIGN KEY (`owner`) REFERENCES `USER` (`login`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla para almacenamiento de los tipos de gastos' AUTO_INCREMENT=1;

-- creacion de la tabla TYPE
CREATE TABLE IF NOT EXISTS `TYPE_SPENDING` (
    `idTypeSpending` int(9) NOT NULL AUTO_INCREMENT COMMENT 'Clave primaria que identifica a la relación tipo-gasto',
    `type` int(9) NOT NULL COMMENT 'Clave primaria del tipo de gasto, no puede ser nula',
    `spending` int(9) NOT NULL COMMENT 'Clave primaria del gasto, no puede ser nula',
    PRIMARY KEY (`idTypeSpending`), 
    FOREIGN KEY (`type`) REFERENCES `TYPE` (`idType`) ON DELETE CASCADE, 
    FOREIGN KEY (`spending`) REFERENCES `SPENDING` (`idSpending`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Tabla que relaciona los gastos con su tipo de gasto' AUTO_INCREMENT=1;
  
INSERT INTO `USER` (`login`,`password`,`fullname`,`email`,`phone`, `country`) VALUES
('adri229','adri229','adrian gonzalez','adri229@gmailcom','988102030','Spain'),
('adri339','adri339','adrian dominguez','adri339@gmailcom','988102030','Spain'),
('adri449','adri449','adrian perez','adri449@gmailcom','988102030','Spain'),
('adri559','adri559','adrian martinez','adri559@gmailcom','988102030','Spain'),
('adri669','adri669','adrian vazquez','adri669@gmailcom','988102030','Spain');    


INSERT INTO `SPENDING` (`dateSpending`,`quantity`,`name`,`owner`) VALUES 
('2016-02-10 22:00:00', '5','desayuno','adri229'),
('2016-02-10 22:00:00', '10','domingo','adri229'),
('2016-02-13 22:00:00', '10','sabado tarde','adri229'),
('2016-02-15 22:00:00', '50','sabado noche','adri229'),
('2016-03-15 22:00:00', '300','vacaciones','adri229'),
('2016-03-16 22:00:00', '20','cine','adri229'),
('2016-03-20 22:00:00', '45','gasolina','adri229'),
('2016-03-20 22:00:00', '15','netflix','adri229'),
('2016-03-20 22:00:00', '60','ropa','adri229'),
('2016-03-20 22:00:00', '50','adsl','adri229'),
('2016-03-21 22:00:00', '100','electricidad','adri229'),
('2016-04-21 22:00:00', '25', 'concierto','adri229'),
('2016-04-22 22:00:00', '5', 'desayuno','adri229'),
('2016-04-22 22:00:00', '5', 'desayuno','adri229'),
('2016-04-22 22:00:00', '10','cena','adri229'),
('2016-05-22 22:00:00', '200','consola','adri229'),
('2016-05-24 22:00:00', '5','cafe','adri229'),
('2016-05-25 22:00:00', '25','comida','adri229'),
('2016-05-25 22:00:00', '60','ropa','adri229'),
('2016-05-27 22:00:00', '20','cena','adri229'),
('2016-05-28 22:00:00', '100','alquiler','adri229');



INSERT INTO `REVENUE` (`dateRevenue`,`quantity`,`name`,`owner`) VALUES
('2016-01-10 22:00:00', '400','nomina','adri229'),
('2016-02-10 22:00:00', '50','extra','adri229'),
('2016-02-13 22:00:00', '400','nomina','adri229'),
('2016-03-15 22:00:00', '400','nomina','adri229'),
('2016-04-15 22:00:00', '20','extra','adri229'),
('2016-05-28 22:00:00', '400','nomina','adri229');



INSERT INTO `STOCK` (`dateStock`,`total`,`owner`) VALUES
('2015-01-10 22:00:00', '500', 'adri229'),
('2016-02-10 22:00:00', '390', 'adri229'),
('2016-02-13 22:00:00', '300', 'adri229'),
('2016-02-15 22:00:00', '400', 'adri229'),
('2016-04-15 22:00:00', '1000', 'adri229');


INSERT INTO `TYPE` (`name`,`owner`) VALUES
('coffe','adri229'),
('leisure','adri229'),
('cinema','adri229'),
('restaurant','adri229'),
('computer games','adri229'),
('underground','adri229'),
('bus','adri229'),
('taxi','adri229'),
('travel','adri229');


INSERT INTO `TYPE_SPENDING` (`type`,`spending`) VALUES 
('1','1'),
('2','2'),
('1','3'),
('2','3'),
('2','4'),
('3','4'),
('4','4'),
('6','5'),
('9','5');