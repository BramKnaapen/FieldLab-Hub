DROP DATABASE IF EXISTS fieldlabhub;
CREATE DATABASE fieldlabhub;

USE fieldlabhub;

CREATE TABLE users (
    userID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(40) NOT NULL,
    email VARCHAR(254) NOT NULL,
    rol VARCHAR(14) NOT NULL DEFAULT 'Opdrachtnemer',
    token VARCHAR(9) NOT NULL UNIQUE,
    expirationdate TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 5 MINUTE)
);

CREATE TABLE projects (
    projectID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    projectname VARCHAR(40) NOT NULL,
    nameprojectOwner VARCHAR(40) NOT NULL,
    summary TEXT  NOT NULL,
    avaliblePlaces INT NOT NULL,
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID)
);

CREATE TABLE requirements (
    requirementID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    projectID INT NOT NULL,
    requirement VARCHAR(255) NOT NULL,
    FOREIGN KEY (projectID) REFERENCES projects(projectID)
);

CREATE TABLE registration (
    registrationID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    motive TEXT NOT NULL,
    projectID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (projectID) REFERENCES projects(projectID)
);

INSERT INTO users (username, email, rol, token) 
VALUES
('admin', 'admin@fieldlabhub.nl', 'Admin', 'K1&4VqeCK'),
('Jeffry Visser', 'jeffryvisser@gmail.com', 'Opdrachtgever', 'K2&4VqeCK'),
('Lukas Wenen', 'lukaswenen@gmail.com', 'Opdrachtgever', 'K3&4VqeCK'),
('Jeroen de Vries', 'jeroendev@gmail.com', 'Opdrachtgever', 'K%44VqeCK'),
('Onno Timmermans', 'onno.t@gmail.com', 'Opdrachtgever', 'K%54VqeCK'),

('Jasper van der Boom', 'jaspervd@gmail.com', 'Opdrachtnemer', 'K%&6VqeCK'),
('Piet-Jan Veldhuis', 'pietjanveldhuis@gmail.com', 'Opdrachtnemer', 'K%74VqeCK'),
('Tijmen Visser', 'tijmenvisser@gmail.com', 'Opdrachtnemer', 'K%&8VqeCK'),
('Vincent Janssens', 'vincentjanssens@gmail.com', 'Opdrachtnemer', 'K%&9VqeCK'),
('Wouter Vermeulen', 'woutervermeulen@gmail.com', 'Opdrachtnemer', 'K%10VqeCK');

INSERT INTO projects (projectname, nameprojectOwner, summary, avaliblePlaces, userID)
VALUES
('FieldLab Hub', 'Jeffry Visser', 'Een plek waar opdrachten toegevoegd kunnen worden en waar opdrachtnemers op opdracht kunnen inschrijven. Dit moet er een beetje uitzien als Werkspot.', 3, 2),
('Webshop', 'Lukas Wenen', 'Webshop voor een bedrijf. Dit moet een mooi modern bedrijf zijn. Het moet eruit zien als een soort winkel webshop die modern is.', 5, 3), 
('Slagerij Website', 'Jeroen de Vries', 'Ik heb sinds kort een website, maar ik krijg amper klanten. Om dit op te lossen wil ik graag een hele mooie website maken. Ik kan dit niet dus ik heb een leuke project groep nodig die dit voor mij kan doen!', 1, 4),
('Appeltaart Review Site', 'Onno Timmermans', 'Ik wil een review site voor appeltaarten. Als ik met mijn maten aan het fietsen ben dan wil ik daarna lekker een appeltaart eten, maar elke keer ben ik weer bij een restaurant en dan smaakt de appeltaart niet. Ik wil daarom graag per restaurant kunnen invullen of het een goede appeltaart was.', 1, 5);

INSERT INTO requirements (projectID, requirement) VALUES
(1, 'Opdracht moeten toegevoegd worden'),
(1, 'Opdracht moeten verwijderd worden'),
(1, 'Opdracht kunnen wijzigen'),
(1, 'Er moet een originele manier voor inloggen komen'),
(1, 'Er moet geen inlog systeem komen met wachtwoord'),

(2, 'Modern'),
(2, 'Winkel'),
(2, 'Inlog systeem'),
(2, 'Shopping Cart'),

(3, 'Geen inlog systeem'),
(3, 'Producten moeten gezien worden'),
(3, 'Halal sectie'),
(3, 'Vegan sectie'),

(4, 'Een review site voor appeltaarten'),
(4, 'Koffie review'),
(4, 'Locatie via google maps'),
(4, 'Review per restaurant');

INSERT INTO registration (userID, motive, projectID) VALUES
(6, 'Wij zijn TechTitans en wij zijn heel gemotiveerd om dit project te maken. Wij willen daarom ook een afspraak willen maken met u om meer details te bespreken. Met vriendelijk groet, TechTitans', 1),
(7, 'Wij willen opdracht aannemen. Wij zijn IT expers.', 1),
(7, 'Hola Senior! Wij willen graag een review maken. Wij zijn een gemotiveerde en willen graag u helpen.', 4),
(8, 'Beste Lukas Wenen, Wij zijn de Baltazars en wij zijn een goeie groep die de mogelijkheid hebben om de opdracht goed uit te voeren en wij hebben heel veel ervaring. Wij willen graag een afspraak maken. Met vriendelijk groet, de Baltazars', 2),
(9, 'Hoi, wij willen graag u helpen met uw slagerij website. Wij zijn heel gemotiveerd en willen jou daarom ook heel graag helpen! Groetjes het ProjectTeam Alkmaar', 3),
(10, 'Beste meneer Timmermans, Wij willen graag een review maken. Wij zijn een gemotiveerde en willen graag u helpen. Met vriendelijk groetjes.', 4);
