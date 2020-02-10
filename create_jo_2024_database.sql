#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

DROP DATABASE IF EXISTS jo_2024;
CREATE DATABASE jo_2024;
USE jo_2024;

#------------------------------------------------------------
# Table: sports_practice
#------------------------------------------------------------

CREATE TABLE sports_practice(
        id        Int  Auto_increment  NOT NULL ,
        practice  Text NOT NULL ,
        image_name Text NOT NULL
	,CONSTRAINT sport_practise_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: sports_facility_type
#------------------------------------------------------------

CREATE TABLE sports_facility_type(
        id   Int  Auto_increment  NOT NULL ,
        type Text NOT NULL
	,CONSTRAINT sports_facility_type_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: sports_facility
#------------------------------------------------------------

CREATE TABLE sports_facility(
        id                                      Int  Auto_increment  NOT NULL ,
        practice_level                           Varchar (50) ,
        handicap_access_mobility_sport_area   Bool NOT NULL ,
        handicap_access_sensory_sport_area  Bool NOT NULL ,
        handicap_access_sensory_locker_room      Bool NOT NULL ,
        handicap_access_mobility_locker_room       Bool NOT NULL ,
        handicap_access_mobility_swimming_pool        Bool NOT NULL ,
        handicap_access_sensory_sanitary      Bool NOT NULL ,
        handicap_access_mobility_sanitary       Bool NOT NULL ,
        facility_name                                    Text ,
        address_number                             Int NOT NULL ,
        address_street                            Text NOT NULL ,
        arrondissement_insee_code                   Int NOT NULL ,
        id_sports_practice                      Int NOT NULL ,
        id_sports_facility_type                   Int NOT NULL
	,CONSTRAINT sports_facility_PK PRIMARY KEY (id)
	,CONSTRAINT sports_facility_sports_practice_FK FOREIGN KEY (id_sports_practice) REFERENCES sports_practice(id)
	,CONSTRAINT sports_facility_sports_facility_type_FK FOREIGN KEY (id_sports_facility_type) REFERENCES sports_facility_type(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: olympic_event
#------------------------------------------------------------

CREATE TABLE olympic_event(
        id                   Int  Auto_increment  NOT NULL ,
        event_name          Varchar (50) NOT NULL ,
        event_place     Varchar (50) NOT NULL ,
        arrondissement_insee_code Int NOT NULL ,
        date                 Date NOT NULL ,
        id_sports_practice Int NOT NULL
	,CONSTRAINT olympic_event_PK PRIMARY KEY (id)
	,CONSTRAINT olympic_event_sports_practice_FK FOREIGN KEY (id_sports_practice) REFERENCES sports_practice(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: sports_family
#------------------------------------------------------------

CREATE TABLE sports_family(
        id         Int  Auto_increment  NOT NULL ,
        sports_family_name Varchar (50) NOT NULL
	,CONSTRAINT sports_family_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: sports_family_practice_association
#------------------------------------------------------------

CREATE TABLE sports_family_practice_association(
        id_practice                 Int NOT NULL ,
        id_sports_family Int NOT NULL
	,CONSTRAINT sports_family_practice_association PRIMARY KEY (id_practice,id_sports_family)

	,CONSTRAINT sports_family_practice_association_sports_practice_FK FOREIGN KEY (id_practice) REFERENCES sports_practice(id)
	,CONSTRAINT sports_family_practice_association_sports_family_FK FOREIGN KEY (id_sports_family) REFERENCES sports_family(id)
)ENGINE=InnoDB;
