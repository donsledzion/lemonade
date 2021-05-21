-- AT FIRST WE NEED TO CREATE SOME TABLES
-- ----------------------------------------------------------------------------------

-- TABLE THAT STORES TYPES OF PLANES ------------------------------------------------
-- ----------------------------------------------------------------------------------

CREATE TABLE typesofplane( 	IDPlaneType TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
							Name VARCHAR(50) NOT NULL,
							Deadweight INT UNSIGNED NOT NULL,
							Contact VARCHAR(50) NOT NULL,
							PRIMARY KEY (IDPlaneType)
							);

-- TABLE THAT STORES TYPES OF CARGOS ------------------------------------------------
-- ----------------------------------------------------------------------------------
							


-- TABLE THAT STORES SHIPPING -------------------------------------------------------
-- ----------------------------------------------------------------------------------

							
CREATE TABLE shippings(		IDShipping INT UNSIGNED NOT NULL AUTO_INCREMENT,
							IDPlaneType TINYINT UNSIGNED NOT NULL,
							Shipping_From VARCHAR(50) NOT NULL,
							Destination VARCHAR(50) NOT NULL,
							Shipping_Date DATE NOT NULL,
							PRIMARY KEY(IDShipping),
							FOREIGN KEY(IDPlaneType) REFERENCES typesofplane(IDPlaneType)
							);							
				

-- TABLE THAT STORES ALL CARGOS -----------------------------------------------------
-- TABLE IS RELATED TO "SHIPPINGS" TABLE --------------------------------------------
-- ----------------------------------------------------------------------------------
				
CREATE TABLE cargos(		IDCargo INT UNSIGNED NOT NULL AUTO_INCREMENT,
							IDShipping INT UNSIGNED NOT NULL,
							Name VARCHAR(80) NOT NULL,
							Weight SMALLINT UNSIGNED NOT NULL,
							CargoType VARCHAR(20) NOT NULL,
							PRIMARY KEY(IDCargo),
							FOREIGN KEY(IDShipping) REFERENCES shippings(IDShipping)							
							);	

-- TABLE THAT STORES ALL FILES ENTRIES ----------------------------------------------
-- TABLE IS RELATED TO "SHIPPINGS" TABLE --------------------------------------------
-- FILES ARE NOT STORED IN DATABASE => ONLY FILE AND HASH NAMES ---------------------

CREATE TABLE files(			IDFile INT UNSIGNED NOT NULL AUTO_INCREMENT,
							IDShipping INT UNSIGNED NOT NULL,
							FileName varchar(256) NOT NULL,
							FilenameHash varchar(256) NOT NULL,
							PRIMARY KEY(IDFile),
							FOREIGN KEY(IDShipping) REFERENCES shippings(IDShipping)
							);
							
							
							
INSERT INTO typesofplane VALUES( NULL, 'Airbus A380', '35000', 'airbus@lemonmind.com');						--  zmienić domenę na właściwą!!!
INSERT INTO typesofplane VALUES( NULL, 'Boeing 747', '38000', 'boeing@lemonmind.com');							--  zmienić domenę na właściwą!!!

