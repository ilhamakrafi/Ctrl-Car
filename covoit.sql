CREATE TABLE IF NOT EXISTS internautes (
  email VARCHAR(50) NOT NULL PRIMARY KEY,
  motDePasse VARCHAR(50) NOT NULL DEFAULT '',
  nom VARCHAR(50) NOT NULL DEFAULT '',
  prenom VARCHAR(50) NOT NULL DEFAULT '',
  telephone VARCHAR(15) NOT NULL DEFAULT ''
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS propositions (
  idProposition INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  emailConducteur VARCHAR(50) NOT NULL,
  villeDepart VARCHAR(50) NOT NULL, 
  villeArrivee VARCHAR(50) NOT NULL,
  date INT(6) NOT NULL,
  nbPlace INT NOT NULL,
  prix FLOAT NOT NULL,
  CONSTRAINT propositions_email_fk FOREIGN KEY (emailConducteur) REFERENCES internautes(email)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS transports (
  idTransport INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  idProposition INT NOT NULL,
  emailPassager VARCHAR(50) NOT NULL,
  CONSTRAINT transports_propositions_fk FOREIGN KEY (idProposition) REFERENCES propositions(idProposition)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT transports_internautes_fk FOREIGN KEY (emailPassager) REFERENCES internautes(email)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

/*cours SIBD*/
INSERT INTO internautes (email, motDePasse, nom, prenom, telephone) VALUES
('elliot.alderson@gmail.com', 'fSocietyR3v!', 'Alderson', 'Elliot', '0701020304'),
('jean.bon@hotmail.com', 'Jambon1', 'Bon', 'Jean', '0605060708'),
('claire.voyante@outlook.com', 'Obscure01', 'Voyante', 'Claire', '0607080910'),
('sarah.croche@gmail.com', 'SecretS', 'Croche', 'Sarah', '0611121314'),
('martin.matin@gmail.com', 'Morning05', 'Matin ', 'Martin', '0722334455'),
('kim.possible@yahoo.com', 'MissionPossible42!', 'Possible', 'Kim', '0633445566');

INSERT INTO propositions (emailConducteur, villeDepart, villeArrivee, date, nbPlace, prix) VALUES
('elliot.alderson@gmail.com','Paris','Lyon', 260307, 4, 20), 
('claire.voyante@outlook.com', 'Montpellier', 'Marseille', 251208, 2, 10),
('jean.bon@hotmail.com', 'Lille', 'Rennes', 260101, 3, 15),
('martin.matin@gmail.com', 'Toulouse', 'Bordeaux',251231, 3, 18);

INSERT INTO transports (idProposition, emailPassager) VALUES
(1, 'jean.bon@hotmail.com'),
(1, 'sarah.croche@gmail.com'),
(2, 'elliot.alderson@gmail.com'),
(3, 'kim.possible@yahoo.com'),
(4, 'claire.voyante@outlook.com');