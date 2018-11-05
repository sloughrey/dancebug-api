# dancebug-api

Main entry point to application is the public/index.php file.

### Install steps:

1) git clone https://github.com/sloughrey/drcvideo-api.git

2) composer install

3) Create a database called 'dancebug' as well as a user called 'dancebug' with a password of 'dancebug' and give the user permission to the dancebug database:
	- `CREATE DATABASE dancebug;`
	- `CREATE USER 'dancebug'@'localhost' IDENTIFIED BY 'dancebug';`
	- `GRANT ALL PRIVILEGES ON dancebug . * TO 'dancebug'@'localhost';`
	- `FLUSH PRIVILEGES;`
##### Note: If you are not using the same database credentials as below, you will need to update the App/Database.php file with the new credentials.
	
4) Create the tblstudios table and populate with some data:

`CREATE TABLE tblStudios (
	DancerID INT(11) AUTO_INCREMENT,
	StudioName VARCHAR(100) NOT NULL,
	StudioID INT(11) UNSIGNED NOT NULL, 
	FirstName VARCHAR(100) NOT NULL,
	LastName VARCHAR(100) NOT NULL,
	Gender VARCHAR(20) NOT NULL,
	DOB DATE NOT NULL,
	DateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (DancerID)
);`

`INSERT INTO tblStudios (StudioName, StudioID, FirstName, LastName, Gender, DOB)
VALUES 
('Stars Dance Academy', 5112, 'Alexis', 'Stephens', 'Female', '2012/03/10'),
('Stars Dance Academy', 5112, 'John', 'Snow', 'Male', '2011/7/11'),
('Edge Dance', 3215, 'Riley', 'O\'Shaughnes', 'Female', '2007/12/10');`

5) Within the root project directory run: php -S localhost:8000 -t public/

6) Run the site at localhost:8000
