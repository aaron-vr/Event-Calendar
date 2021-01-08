SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION; /*START-END transaction as advised in the MySql documentation*/
SET time_zone = "+00:00"; /*makes life easier, but probably better to record the designated time zone for each event*/

/*Data definition language*/

/*the table for predefined sport categories, to ensure complete referential integrity with events, inaccessible to the user*/
CREATE TABLE IF NOT EXISTS sports (
	ID INT(12) AUTO_INCREMENT,
	sport_name varchar(255) UNIQUE NOT NULL,
    sport_desc VARCHAR(255) DEFAULT 'N/A',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT sports_PK PRIMARY KEY (ID) /*naming constraints for easier troubleshooting*/
);
/*the table for the currently available teams representing each sport category; later fetched with PHP to populate dropdowns for event scheduling.
although it can be seen by the user through the dropdowns, the table cannot be modified by the user*/
CREATE TABLE IF NOT EXISTS teams (
	ID INT(12) AUTO_INCREMENT,
    team_name VARCHAR(255) UNIQUE NOT NULL,
    team_desc VARCHAR(255) DEFAULT 'N/A',
	country VARCHAR(255) NOT NULL,
    sport_id int(12) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT teams_PK PRIMARY KEY (ID), /*naming constraints for easier troubleshooting*/
    CONSTRAINT teams_FK FOREIGN KEY (sport_id) REFERENCES sports (id) ON DELETE CASCADE
);
/*the primary table which allows the user to insert events - therefore lots of integrity constraints*/
CREATE TABLE IF NOT EXISTS sport_events (
	ID INT(12) NOT NULL AUTO_INCREMENT,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    home_team INT(12) NOT NULL,
    visiting_team INT(12) NOT NULL, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,/*naming constraints for easier troubleshooting*/
    CONSTRAINT two_different_teams CHECK (home_team <> visiting_team), /*prevents the user from pitting a team against itself in an event*/
    CONSTRAINT event_start_end_check CHECK (end_time > start_time), /*prevents the user from inserting events that end before they begin, potentially problematic if event starts before but ends after midnight, 
    could be adjusted by adding a timezone column and preserving the timezone of the location where the event is taking place*/
    CONSTRAINT events_PK PRIMARY KEY (ID), /*adding primary key, a strict formality*/
	CONSTRAINT events_CK1 UNIQUE (event_date, home_team), 
    CONSTRAINT events_CK2 UNIQUE (event_date, visiting_team), /*candidate keys, restricting a team from playing more than one game on the same date - highly uncommon these days, at least with the same players*/
    CONSTRAINT events_FK1 FOREIGN KEY (home_team) REFERENCES teams (id) ON DELETE CASCADE,
    CONSTRAINT events_FK2 FOREIGN KEY (visiting_team) REFERENCES teams (id) ON DELETE CASCADE
);
/*Data manipulation language*/

/*the different sport categories available*/
insert into sports (sport_name, sport_desc) values 
('Football', 'Involves kicking a ball with the foot'),
('Hockey', 'Involves kicking a puck with a hockey stick'),
('Basketball', 'Involves throwing a ball into a hoop'),
('Rugby', 'Players pass the ball until one reaches the goal-line'),
('Volleyball', 'Involves htting a ball over a central net'),
('Handball', 'Players pass the ball using their hands and score goals');

/*the currently available teams for match-making*/
insert into teams (team_name, country, sport_id) values 
('Manchester United', 'United Kingdom', 1),
('Dortmund', 'Germany', 1),
('Arsenal', 'United Kingdom', 1),
('Real Madrid', 'Spain', 1),
('Barcelona', 'Spain', 1),
('Bayern', 'Germany', 1),
('Los Angeles Lakers', 'California, US', 3),
('Boston Celtics', 'Massachusetts, US', 3),
('Chicago Bulls', 'Illinois, US', 3),
('Miami Heat', 'Florida, US', 3),
('New York Knicks', 'New York, US', 3),
('THW Kiel', 'Germany', 6),
('Zamalek', 'Egypt', 6),
('Ciudad Real', 'Spain', 6),
('Paris Saint-Germain', 'France', 6);

/*for testing purposes; events are otherwise to be inserted by the user on the web*/
/*insert into sport_events (event_date, start_time, end_time, home_team, visiting_team) values 
('2021-02-16', '16:00:00', '15:30:00', 1,2),
('2021-02-17', '10:00:00', '12:00:00', 1,1),
('2001-02-20', '11:30:00', '13:00:00', 7,8),
('2021-02-03', '20:00:00', '22:30:00', 8,9),
('2021-02-05', '12:00:00', '13:30:00', 13,15);*/

/*Table view of user-made events, first drawn up using relational algebra then transposed to SQL - used in fetch_events.php*/
/*SELECT
  date_format(event_date, '%a') AS 'Day of week',
  date_format(event_date, '%d %M %Y') AS 'Date',
  start_time AS 'Beginning at',
  end_time AS 'Ending at',
  sport_name AS 'Sport',
  teams.team_name AS 'Home Team',
  teams1.team_name AS 'Visiting Team' from
  sports
  INNER JOIN teams ON sports.id = teams.sport_id
  INNER JOIN teams AS teams1 ON teams.sport_id = teams1.sport_id
  INNER JOIN sport_events AS events ON
    (teams.id = events.home_team AND teams1.id = events.visiting_team);*/
    

COMMIT;
