create table if not exists wtc_edirect_metod
(
	ID int not null AUTO_INCREMENT,
	NAME varchar(255) not null,
	FNAME varchar(255) not null,
	TYPE char(10) NOT NULL DEFAULT 'UNI',
	IS_IMPORTANT char(1) NOT NULL DEFAULT 'N',
	IS_USER char(1) NOT NULL DEFAULT 'Y',
	DESCRIPTION text,
	SORT int NOT NULL DEFAULT '500',
	ACTIVE char(1) NOT NULL DEFAULT 'Y',
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_mcondition
(
	ID int not null AUTO_INCREMENT,
	ID_COMPANY bigint,
	ID_BANNER_GROUP bigint,
	FROM_HOUR int(2),
	TO_HOUR int(2),
	MAX_PRICE float,
	ID_METOD int not null,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_catalog_items
(
	ID bigint not null AUTO_INCREMENT,
	IBLOCK_ELEMENT_ID bigint not null,
	IBLOCK_ID bigint not null,
	PARENT_SECTION_ID bigint,
	IS_SECTION char(1) NOT NULL DEFAULT 'N',
	NAME varchar(255) not null,
	PRICE float NOT NULL DEFAULT '0',
	IS_AVAILABLE char(1) NOT NULL DEFAULT 'Y',
	UPDATE_DATE datetime not null,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_company
(
	ID bigint not null,
	ID_CATALOG_ITEM bigint,
	NAME varchar(255) not null,
	IS_RSYA char(1) NOT NULL DEFAULT 'N',
	STRATEGY_TYPE varchar(255) not null,	
	STATUS varchar(255),
	ACTIVE char(1) NOT NULL DEFAULT 'Y',
	BET_DATE datetime not null,
	CHECK_MAIN_PARAMS_DATE datetime not null,
	IMPORT_DATE datetime not null,
	FULL_UPDATE_DATE datetime not null,
	IN_GOOGLE char(1) NOT NULL DEFAULT 'N',
	NOT_CHECK_SEO char(1) NOT NULL DEFAULT 'N',
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_banner_groups
(
	ID bigint not null,
	ID_COMPANY bigint not null,
	NAME varchar(255) not null,
	SERVING_STATUS varchar(255),
	REGIONIDS text,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_banners
(
	ID bigint not null,
	ID_BANNER_GROUP bigint not null,
	ID_CATALOG_ITEM bigint,
	TITLE varchar(255) not null,
	TITLE2 varchar(255) not null,
	TEXT varchar(255) not null,
	HREF varchar(255) not null,
	DISPLAY_URL varchar(255) not null,
	PRICE float NOT NULL DEFAULT '0',	
	SITELINKS text,
	IMAGE text,
	ACTIVE char(1) NOT NULL DEFAULT 'Y',
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_phrases
(
	ID bigint not null,
	ID_BANNER_GROUP bigint not null,
	NAME varchar(255) not null,
	PRICE float,
	PRICE_ON_SEARCH float,
	SHOWS int,
	CLICKS int,
	CONTEXTSHOWS int,
	CONTEXTCLICKS int,
	CONTEXTCOVERAGE text,
	CONTEXTPRICE float,
	MAXBET float,
	MINBET float,
	PREMIUMMAX float,
	PREMIUMMIN float,
	PRICES text,
	MESTO_SEO int(2) not null DEFAULT 0,
	CHECK_MESTO_DATE datetime not null,
	UPDATE_BIDS_DATE datetime not null,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_phrases_log
(
	ID bigint not null AUTO_INCREMENT,
	ID_PHRASE bigint not null,
	ID_BANNER_GROUP bigint not null,
	ID_COMPANY bigint not null,
	MAX_PRICE float,
	PRICE float,
	PRICE_ON_SEARCH float,
	SHOWS int,
	CLICKS int,
	SEARCHCTR float,
	SEARCHPLACE int,
	SEARCHPRICES text,
	CONTEXTSHOWS int,
	CONTEXTCLICKS int,
	CONTEXTCTR float,	
	CONTEXTPRICE float,
	CONTEXTCOVERAGE int,
	CONTEXTPRICES text,
	CHECK_DATE datetime,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_sys_line
(
	ID int not null AUTO_INCREMENT,
	ID_COMPANY bigint not null,
	IS_LOCK char(1) NOT NULL DEFAULT 'N',
	INSERT_DATE datetime not null,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_sys_exchangestat
(
	ID int not null AUTO_INCREMENT,
	NAME varchar(255) not null,
	CALL_CNT int(6)  not null DEFAULT 1,
	UNITS_COST int(6) not null DEFAULT 0,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_sys_log
(
	ID int not null AUTO_INCREMENT,
	MESSAGE text,
	TYPE char(1) not null DEFAULT 'M',
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);

create table if not exists wtc_edirect_sys_cron
(
	ID int not null auto_increment,
	FNAME varchar(255) not null,
	EXEC_DATE datetime not null,
	primary key (ID)
);




create table if not exists wtc_edirect_podbor_reports
(
	ID int not null AUTO_INCREMENT,
	PHRASES text,
	YAREPORT_ID bigint,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,	
	primary key (ID)
);

create table if not exists wtc_edirect_podbor_phrases
(
	ID int not null AUTO_INCREMENT,
	NAME varchar(255) not null,
	SHOWS int,
	SHOWS_QUOTES int,	
	TYPE char(1) not null DEFAULT 'S',
	SORT bigint NOT NULL DEFAULT '500',
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,	
	primary key (ID)
);


create table if not exists wtc_edirect_templates
(
	ID int not null AUTO_INCREMENT,
	IBLOCK_ID bigint,
	FOR_SECTIONS char(1) not null DEFAULT 'N',
	NAME varchar(255) not null,
	TITLE varchar(255) not null,
	TITLE2 varchar(255) not null,
	TEXT varchar(255) not null,
	HREF varchar(255) not null,
	DISPLAY_URL varchar(255) not null,
	PRICE varchar(255) not null,
	SITELINKS text,
	PHRASES text,
	MINUS_WORDS text,
	MODIFIED_DATE datetime,
	MODIFIED_IDUSER int,
	primary key (ID)
);