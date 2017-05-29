
CREATE DATABASE IF NOT EXISTS `domino` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `domino`;

CREATE TABLE IF NOT EXISTS `logarse` (
  `idJugador` int(4) NOT NULL AUTO_INCREMENT,
  `DNI` varchar(9) NOT NULL,
  `Nom` varchar(40) NOT NULL,
  `Cognom` varchar(40) NOT NULL,
  `User` varchar(30) NOT NULL,
  `Password` varchar(30) NOT NULL,
  `Foto` varchar(40) NOT NULL,
  `online` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idJugador`),
  UNIQUE KEY `DNI` (`DNI`,`User`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `logarse` (`idJugador`, `DNI`, `Nom`, `Cognom`, `User`, `Password`, `Foto`, `online`) VALUES
('', '123', 'qwe', 'qwe', 'qew', '123', 'Penguins.jpg', 0),
('', '1231', 'wer', 'wer', 'wer', 'wer', 'Jellyfish.jpg', 0);

CREATE TABLE IF NOT EXISTS `partidas` (
  `arrayFichas` varchar(200) NOT NULL,
  `idPArtidas` int(10) NOT NULL AUTO_INCREMENT,
  `player1` int(6) NOT NULL,
  `player2` int(11) NOT NULL,
  `jugando` int(11) NOT NULL,
  PRIMARY KEY (`idPArtidas`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `puntuacion` (
  `idJugador` int(11) NOT NULL,
  `numPartidasGanadas` int(6) NOT NULL DEFAULT '0',
  `numPartidasPerdidas` int(6) NOT NULL DEFAULT '0',
  `puntos` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idJugador`),
  FOREIGN KEY (idJugador) REFERENCES logarse(idJugador)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
