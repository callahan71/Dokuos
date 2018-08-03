/**
 * Author:  Jaime Quirant
 * Created: 07-jul-2018
 */

CREATE DATABASE IF NOT EXISTS dokuos_config;
USE dokuos_config;

CREATE TABLE users(
id          int(255) auto_increment not null,
username    varchar(255),
password    varchar(255),
role        varchar(20),
email       varchar(255),
image       varchar(255),
CONSTRAINT pk_user PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE materials (
id                  int(255) auto_increment not null,
userID              int(255),
ref                 varchar(255) not null,
name                varchar(255),
image               varchar(255),
CONSTRAINT pk_material PRIMARY KEY (id),
CONSTRAINT fk_material_user FOREIGN KEY (userID)  REFERENCES users(id)
ON DELETE CASCADE,
CONSTRAINT uc_material UNIQUE (ref)
)ENGINE=InnoDb;

CREATE TABLE showcases (
id                  int(255) auto_increment not null,
userID              int(255),
token               varchar(255) not null,
name                varchar(255),
image               varchar(255),
catalogue           varchar(255),
CONSTRAINT pk_showcase PRIMARY KEY (id),
CONSTRAINT fk_showcase_user FOREIGN KEY (userID)  REFERENCES users(id)
ON DELETE CASCADE,
CONSTRAINT uc_showcase UNIQUE (token)
)ENGINE=InnoDb;

CREATE TABLE categories (
id          int(255) auto_increment not null,
name        varchar(255) not null,
CONSTRAINT pk_category PRIMARY KEY (id)
)ENGINE=InnoDb;

CREATE TABLE models (
id          int(255) auto_increment not null,
userID      int(255),
categoryID  int(255),
ref         varchar(255) not null,
image       varchar(255),
name        varchar(255),
CONSTRAINT pk_model PRIMARY KEY (id),
CONSTRAINT fk_model_user FOREIGN KEY (userID)  REFERENCES users(id)
ON DELETE CASCADE,
CONSTRAINT fk_model_category FOREIGN KEY (categoryID)  REFERENCES categories(id)
ON DELETE CASCADE,
CONSTRAINT uc_model UNIQUE (ref)
)ENGINE=InnoDb;

CREATE TABLE videos (
id          int(255) auto_increment not null,
userID      int(255),
video       varchar(255),
CONSTRAINT pk_video PRIMARY KEY (id),
CONSTRAINT fk_video_user FOREIGN KEY (userID)  REFERENCES users(id)
ON DELETE CASCADE
)ENGINE=InnoDb;

CREATE TABLE combinations (
id                  int(255) auto_increment not null,
keyCHAR             varchar(1) not null,
showcaseID          int(255),
materialID         int(255),
CONSTRAINT pk_combination PRIMARY KEY (id),
CONSTRAINT fk_combination_showcase FOREIGN KEY (showcaseID) REFERENCES showcases(id)
ON DELETE CASCADE,
CONSTRAINT fk_combination_material FOREIGN KEY (materialID) REFERENCES materials(id)
ON DELETE CASCADE,
CONSTRAINT uc_combination UNIQUE (keyCHAR, showcaseID)
)ENGINE=InnoDb;

CREATE TABLE active_zones (
id          int(255) auto_increment not null,
zoneREF     varchar(255),
modelID    int(255),
map         varchar(255),
CONSTRAINT pk_active_zone PRIMARY KEY (id),
CONSTRAINT fk_active_zone_model FOREIGN KEY (modelID) REFERENCES models(id)
ON DELETE CASCADE
)ENGINE=InnoDb;

CREATE TABLE renders (
id                  int(255) auto_increment not null,
active_zoneID       int(255),
materialID         int(255),
image               varchar(255),
CONSTRAINT pk_render PRIMARY KEY (id),
CONSTRAINT fk_render_active_zone FOREIGN KEY (active_zoneID)  REFERENCES active_zones(id)
ON DELETE CASCADE,
CONSTRAINT fk_render_material FOREIGN KEY (materialID)  REFERENCES materials(id)
ON DELETE CASCADE,
CONSTRAINT uc_render UNIQUE (active_zoneID, materialID)
)ENGINE=InnoDb;