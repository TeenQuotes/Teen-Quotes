create table "countries" ("id" integer not null primary key autoincrement, "name" varchar not null);
create unique index countries_name_unique on "countries" ("name");
drop table if exists "users";
create table "users" ("id" integer not null primary key autoincrement, "login" varchar not null, "password" varchar not null, "email" varchar not null, "security_level" integer not null default '0', "ip" varchar not null, "birthdate" date null, "gender" varchar null, "country" varchar null, "city" varchar null, "avatar" varchar null, "about_me" varchar null, "hide_profile" integer not null default '0', "notification_comment_quote" integer not null default '1', "last_visit" datetime null, "remember_token" varchar null, "created_at" datetime not null, "updated_at" datetime not null);
drop table if exists "quotes";
create table "quotes" ("id" integer not null primary key autoincrement, "content" varchar not null, "user_id" integer not null, "approved" integer not null default '0', "created_at" datetime not null, "updated_at" datetime not null, foreign key("user_id") references "users"("id") on delete cascade);
create index quotes_user_id_index on "quotes" ("user_id");
drop table if exists "comments";
create table "comments" ("id" integer not null primary key autoincrement, "content" varchar not null, "quote_id" integer not null, "user_id" integer not null, "created_at" datetime not null, "updated_at" datetime not null, foreign key("quote_id") references "quotes"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);
create index comments_quote_id_index on "comments" ("quote_id");
create index comments_user_id_index on "comments" ("user_id");
drop table if exists "profile_visitors";
create table "profile_visitors" ("id" integer not null primary key autoincrement, "user_id" integer not null, "visitor_id" integer not null, "created_at" datetime not null, "updated_at" datetime not null, foreign key("user_id") references "users"("id") on delete cascade, foreign key("visitor_id") references "users"("id") on delete cascade);
create index profile_visitors_user_id_index on "profile_visitors" ("user_id");
create index profile_visitors_visitor_id_index on "profile_visitors" ("visitor_id");
drop table if exists "favorite_quotes";
create table "favorite_quotes" ("id" integer not null primary key autoincrement, "quote_id" integer not null, "user_id" integer not null, "created_at" datetime not null, "updated_at" datetime not null, foreign key("quote_id") references "quotes"("id") on delete cascade, foreign key("user_id") references "users"("id") on delete cascade);
create index favorite_quotes_quote_id_index on "favorite_quotes" ("quote_id");
create index favorite_quotes_user_id_index on "favorite_quotes" ("user_id");
drop table if exists "newsletters";
create table "newsletters" ("id" integer not null primary key autoincrement, "user_id" integer not null, "type" varchar not null default 'weekly', "created_at" datetime not null, "updated_at" datetime not null, foreign key("user_id") references "users"("id") on delete cascade);
create index newsletters_user_id_index on "newsletters" ("user_id");
drop table if exists "stories";
create table "stories" ("id" integer not null primary key autoincrement, "represent_txt" text not null, "frequence_txt" text not null, "user_id" integer not null, "created_at" datetime not null, "updated_at" datetime not null, foreign key("user_id") references "users"("id") on delete cascade);
create index stories_user_id_index on "stories" ("user_id");
create table "password_reminders" ("email" varchar not null, "token" varchar not null, "created_at" datetime not null);
create index password_reminders_email_index on "password_reminders" ("email");
create index password_reminders_token_index on "password_reminders" ("token");
drop table if exists "settings";
create table "settings" ("id" integer not null primary key autoincrement, "user_id" integer not null, "key" varchar not null, "value" varchar not null, foreign key("user_id") references "users"("id") on delete cascade);
create index settings_user_id_index on "settings" ("user_id");

-- Seed countries table
INSERT INTO `countries` (`id`, `name`)
VALUES
	(1,'Afghanistan'),
	(2,'Albania'),
	(3,'Algeria'),
	(4,'American'),
	(5,'Andorra'),
	(6,'Angola'),
	(7,'Anguilla'),
	(8,'Antarctica'),
	(9,'Antigua and Barbuda'),
	(10,'Argentina'),
	(11,'Armenia'),
	(12,'Aruba'),
	(13,'Australia'),
	(14,'Austria'),
	(15,'Azerbaijan'),
	(16,'Bahamas'),
	(17,'Bahrain'),
	(18,'Bangladesh'),
	(19,'Barbados'),
	(20,'Belarus'),
	(21,'Belgium'),
	(22,'Belize'),
	(23,'Benin'),
	(24,'Bermuda'),
	(25,'Bhutan'),
	(26,'Bolivia'),
	(27,'Bosnia and Herzegovina'),
	(28,'Botswana'),
	(29,'Bouvet'),
	(30,'Brazil'),
	(31,'British Indian Ocean Territory'),
	(32,'Brunei Darussalam'),
	(33,'Bulgaria'),
	(34,'Burkina Faso'),
	(35,'Burundi'),
	(36,'Cambodia'),
	(37,'Cameroon'),
	(38,'Canada'),
	(39,'Cape Verde'),
	(40,'Cayman Islands'),
	(41,'Central African Republic'),
	(42,'Chad'),
	(43,'Chile'),
	(44,'China'),
	(45,'Christmas Island'),
	(46,'Cocos (Keeling) Islands'),
	(47,'Colombia'),
	(48,'Comoros'),
	(49,'Congo'),
	(50,'Congo The Democratic Republic of The Congo'),
	(51,'Cook Islands'),
	(52,'Costa Rica'),
	(53,'Cote D''ivoire'),
	(54,'Croatia'),
	(55,'Cuba'),
	(56,'Cyprus'),
	(57,'Czech Republic'),
	(58,'Denmark'),
	(59,'Djibouti'),
	(60,'Dominica'),
	(61,'Dominican Republic'),
	(62,'Ecuador'),
	(63,'Egypt'),
	(64,'El Salvador'),
	(65,'Equatorial'),
	(66,'Eritrea'),
	(67,'Estonia'),
	(68,'Ethiopia'),
	(69,'Falkland Islands (Malvinas)'),
	(70,'Faroe'),
	(71,'Fiji'),
	(72,'Finland'),
	(73,'France'),
	(74,'French Guiana'),
	(75,'French Polynesia'),
	(76,'French Southern Territories'),
	(77,'Gabon'),
	(78,'Gambia'),
	(79,'Georgia'),
	(80,'Germany'),
	(81,'Ghana'),
	(82,'Gibraltar'),
	(83,'Greece'),
	(84,'Greenland'),
	(85,'Grenada'),
	(86,'Guadeloupe'),
	(87,'Guam'),
	(88,'Guatemala'),
	(89,'Guinea'),
	(90,'Guinea -bissau'),
	(91,'Guyana'),
	(92,'Haiti'),
	(93,'Heard Island and Mcdonald Islands'),
	(94,'Honduras'),
	(95,'Hong'),
	(96,'Hungary'),
	(97,'Iceland'),
	(98,'India'),
	(99,'Indonesia'),
	(100,'Iran'),
	(101,'Iraq'),
	(102,'Ireland'),
	(103,'Israel'),
	(104,'Italy'),
	(105,'Jamaica'),
	(106,'Japan'),
	(107,'Jordan'),
	(108,'Kazakhstan'),
	(109,'Kenya'),
	(110,'Kiribati'),
	(111,'Korea'),
	(112,'Kuwait'),
	(113,'Kyrgyzstan'),
	(114,'Lao People''s Democratic Republic'),
	(115,'Latvia'),
	(116,'Lebanon'),
	(117,'Lesotho'),
	(118,'Liberia'),
	(119,'Libyan Arab Jamahiriya'),
	(120,'Liechtenstein'),
	(121,'Lithuania'),
	(122,'Luxembourg'),
	(123,'Macao'),
	(124,'Macedonia'),
	(125,'Madagascar'),
	(126,'Malawi'),
	(127,'Malaysia'),
	(128,'Maldives'),
	(129,'Mali'),
	(130,'Malta'),
	(131,'Marshall'),
	(132,'Martinique'),
	(133,'Mauritania'),
	(134,'Mauritius'),
	(135,'Mayotte'),
	(136,'Mexico'),
	(137,'Micronesia'),
	(138,'Moldova'),
	(139,'Monaco'),
	(140,'Mongolia'),
	(141,'Montserrat'),
	(142,'Morocco'),
	(143,'Mozambique'),
	(144,'Myanmar'),
	(145,'Namibia'),
	(146,'Nauru'),
	(147,'Nepal'),
	(148,'Netherlands'),
	(149,'Netherlands Antilles'),
	(150,'New Caledonia'),
	(151,'New Zealand'),
	(152,'Nicaragua'),
	(153,'Niger'),
	(154,'Nigeria'),
	(155,'Niue'),
	(156,'Norfolk'),
	(157,'Northern'),
	(158,'Norway'),
	(159,'Oman'),
	(160,'Pakistan'),
	(161,'Palau'),
	(162,'Palestinian Territory'),
	(163,'Panama'),
	(164,'Papua New Guinea'),
	(165,'Paraguay'),
	(166,'Peru'),
	(167,'Philippines'),
	(168,'Pitcairn'),
	(169,'Poland'),
	(170,'Portugal'),
	(171,'Puerto Rico'),
	(172,'Qatar'),
	(173,'Reunion'),
	(174,'Romania'),
	(175,'Russian Federation'),
	(176,'Rwanda'),
	(177,'Saint Helena Saint Helena'),
	(178,'Saint Kitts and Nevis'),
	(179,'Saint Lucia Saint Lucia'),
	(180,'Saint Pierre and Miquelon'),
	(181,'Saint Vincent and The Grenadines'),
	(182,'Samoa'),
	(183,'San Marino San Marino'),
	(184,'Sao Tome and Principe'),
	(185,'Saudi'),
	(186,'Senegal'),
	(187,'Serbia'),
	(188,'Seychelles'),
	(189,'Sierra Leone'),
	(190,'Singapore'),
	(191,'Slovakia'),
	(192,'Slovenia'),
	(193,'Solomon Islands'),
	(194,'Somalia'),
	(195,'South Africa'),
	(196,'South Georgia and The South Sandwich Islands'),
	(197,'Spain'),
	(198,'Sri'),
	(199,'Sudan'),
	(200,'Suriname'),
	(201,'Svalbard and Jan Mayen'),
	(202,'Swaziland'),
	(203,'Sweden'),
	(204,'Switzerland'),
	(205,'Syrian Arab Republic'),
	(206,'Taiwa'),
	(207,'Tajikistan'),
	(208,'Tanzania'),
	(209,'Thailand'),
	(210,'Timor-leste'),
	(211,'Togo'),
	(212,'Tokelau'),
	(213,'Tonga'),
	(214,'Trinidad and Tobago'),
	(215,'Tunisia'),
	(216,'Turkey'),
	(217,'Turkmenistan'),
	(218,'Turks and Caicos Islands'),
	(219,'Tuvalu'),
	(220,'Uganda'),
	(221,'Ukraine'),
	(222,'United Arab Emirates'),
	(223,'United Kingdom'),
	(224,'United States'),
	(225,'United States Minor Outlying Islands'),
	(226,'Uruguay'),
	(227,'Uzbekistan'),
	(228,'Vanuatu'),
	(229,'Venezuela'),
	(230,'Viet'),
	(231,'Virgin Islands, British Virgin Islands'),
	(232,'Virgin Islands, U.S. Virgin Islands'),
	(233,'Wallis and Futuna'),
	(234,'Western'),
	(235,'Yemen'),
	(236,'Zambia'),
	(237,'Zimbabwe');