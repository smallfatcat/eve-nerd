/* corporation kills of citadels BY CLASS*/
SELECT A.corporation_id,
	A.corporation_name,
    SUM(case when A.security = 'H' then 1 else 0 end) AS H,
    SUM(case when A.security = 'L' then 1 else 0 end) AS L,
    SUM(case when A.security = '0.0' then 1 else 0 end) AS NS,
    SUM(case when A.security = 'C1' then 1 else 0 end) AS C1,
    SUM(case when A.security = 'C2' then 1 else 0 end) AS C2,
    SUM(case when A.security = 'C3' then 1 else 0 end) AS C3,
    SUM(case when A.security = 'C4' then 1 else 0 end) AS C4,
    SUM(case when A.security = 'C5' then 1 else 0 end) AS C5,
    SUM(case when A.security = 'C6' then 1 else 0 end) AS C6,
	COUNT(A.corporation_id) as Total
FROM(
    SELECT DISTINCT CONCAT(attackers.corporation_id, killmails.killmail_id) as sig,
	attackers.corporation_id,
	killmails.killmail_id,
    corporations.corporation_name,
    system_info.security
	FROM killmails
	INNER JOIN
	attackers
	ON attackers.killmail_id = killmails.killmail_id
    INNER JOIN
    system_info
    ON system_info.id = killmails.solar_system_id
    INNER JOIN
    corporations
    ON corporations.corporation_id = attackers.corporation_id
	WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
    AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00'
	/*AND system_info.security IN ('C1','C2','C3','C4','C5','C6')*/
) AS A
GROUP BY A.corporation_id
ORDER BY Total DESC

/* Alliance kills of citadels BY CLASS in wormholes*/
SELECT A.alliance_id,
	A.alliance_name,
    SUM(case when A.security = 'C1' then 1 else 0 end) AS C1,
    SUM(case when A.security = 'C2' then 1 else 0 end) AS C2,
    SUM(case when A.security = 'C3' then 1 else 0 end) AS C3,
    SUM(case when A.security = 'C4' then 1 else 0 end) AS C4,
    SUM(case when A.security = 'C5' then 1 else 0 end) AS C5,
    SUM(case when A.security = 'C6' then 1 else 0 end) AS C6,
	COUNT(A.alliance_id) as Total
FROM(
    SELECT DISTINCT CONCAT(attackers.alliance_id, killmails.killmail_id) as sig,
	attackers.alliance_id,
	killmails.killmail_id,
    alliances.alliance_name,
    system_info.security
	FROM killmails
	INNER JOIN
	attackers
	ON attackers.killmail_id = killmails.killmail_id
    INNER JOIN
    system_info
    ON system_info.id = killmails.solar_system_id
    INNER JOIN
    alliances
    ON alliances.alliance_id = attackers.alliance_id
	WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
    AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00'
	AND system_info.security IN ('C1','C2','C3','C4','C5','C6')
) AS A
GROUP BY A.alliance_id
ORDER BY Total DESC

/* Alliance kills of citadels BY CLASS*/
SELECT A.alliance_id,
	A.alliance_name,
    SUM(case when A.security = 'H' then 1 else 0 end) AS H,
    SUM(case when A.security = 'L' then 1 else 0 end) AS L,
    SUM(case when A.security = '0.0' then 1 else 0 end) AS NS,
    SUM(case when A.security = 'C1' then 1 else 0 end) AS C1,
    SUM(case when A.security = 'C2' then 1 else 0 end) AS C2,
    SUM(case when A.security = 'C3' then 1 else 0 end) AS C3,
    SUM(case when A.security = 'C4' then 1 else 0 end) AS C4,
    SUM(case when A.security = 'C5' then 1 else 0 end) AS C5,
    SUM(case when A.security = 'C6' then 1 else 0 end) AS C6,
	COUNT(A.alliance_id) as Total
FROM(
    SELECT DISTINCT CONCAT(attackers.alliance_id, killmails.killmail_id) as sig,
	attackers.alliance_id,
	killmails.killmail_id,
    alliances.alliance_name,
    system_info.security
	FROM killmails
	INNER JOIN
	attackers
	ON attackers.killmail_id = killmails.killmail_id
    INNER JOIN
    system_info
    ON system_info.id = killmails.solar_system_id
    INNER JOIN
    alliances
    ON alliances.alliance_id = attackers.alliance_id
	WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
    AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00'
	/*AND system_info.security IN ('C1','C2','C3','C4','C5','C6')*/
) AS A
GROUP BY A.alliance_id
ORDER BY Total DESC

/* Alliance kills of citadels in wormholes*/
SELECT A.alliance_id,
	A.alliance_name,
	COUNT(A.alliance_id) as Total
FROM(
    SELECT DISTINCT CONCAT(attackers.alliance_id, killmails.killmail_id) as sig,
	attackers.alliance_id,
	killmails.killmail_id,
    alliances.alliance_name
	FROM killmails
	INNER JOIN
	attackers
	ON attackers.killmail_id = killmails.killmail_id
    INNER JOIN
    system_info
    ON system_info.id = killmails.solar_system_id
    INNER JOIN
    alliances
    ON alliances.alliance_id = attackers.alliance_id
	WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
    AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00'
	AND system_info.security IN ('C1','C2','C3','C4','C5','C6')
) AS A
GROUP BY A.alliance_id
ORDER BY Total DESC

/* Corporation kills of citadels in wormholes*/
SELECT A.corporation_id,
	A.corporation_name,
	COUNT(A.corporation_id) as Total
FROM(
    SELECT DISTINCT CONCAT(attackers.corporation_id, killmails.killmail_id) as sig,
	attackers.corporation_id,
	killmails.killmail_id,
    corporations.corporation_name
	FROM killmails
	INNER JOIN
	attackers
	ON attackers.killmail_id = killmails.killmail_id
    INNER JOIN
    system_info
    ON system_info.id = killmails.solar_system_id
    INNER JOIN
    corporations
    ON corporations.corporation_id = attackers.corporation_id
	WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
    AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00'
	AND system_info.security IN ('C1','C2','C3','C4','C5','C6')
) AS A
GROUP BY A.corporation_id
ORDER BY Total DESC

/* summary by day and class*/

SELECT DATE(killmails.killmail_time) as Day,
 COUNT( DATE(killmails.killmail_time)) as Total,
 SUM(case when system_info.security = 'H' then 1 else 0 end) AS H,
 SUM(case when system_info.security = 'L' then 1 else 0 end) AS L,
 SUM(case when system_info.security = '0.0' then 1 else 0 end) AS NS,
 SUM(case when system_info.security = 'C1' then 1 else 0 end) AS C1,
 SUM(case when system_info.security = 'C2' then 1 else 0 end) AS C2,
 SUM(case when system_info.security = 'C3' then 1 else 0 end) AS C3,
 SUM(case when system_info.security = 'C4' then 1 else 0 end) AS C4,
 SUM(case when system_info.security = 'C5' then 1 else 0 end) AS C5,
 SUM(case when system_info.security = 'C6' then 1 else 0 end) AS C6
FROM killmails
INNER JOIN
system_info
ON system_info.id = killmails.solar_system_id
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00' 
GROUP BY  DATE(killmails.killmail_time)

/* summary by day and type*/

SELECT DATE(killmails.killmail_time) as Day,
 COUNT( DATE(killmails.killmail_time)) as Total,
 SUM(case when killmails.ship_type_id = 35835 then 1 else 0 end) AS Athanor,
 SUM(case when killmails.ship_type_id = 35836 then 1 else 0 end) AS Tatara,
 SUM(case when killmails.ship_type_id = 35832 then 1 else 0 end) AS Astrahus,
 SUM(case when killmails.ship_type_id = 35833 then 1 else 0 end) AS Fortizar,
 SUM(case when killmails.ship_type_id = 35834 then 1 else 0 end) AS Keepstar,
 SUM(case when killmails.ship_type_id = 35825 then 1 else 0 end) AS Raitaru,
 SUM(case when killmails.ship_type_id = 35826 then 1 else 0 end) AS Azbel,
 SUM(case when killmails.ship_type_id = 35827 then 1 else 0 end) AS Sotiyo
FROM killmails
INNER JOIN
system_info
ON system_info.id = killmails.solar_system_id
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
AND killmails.killmail_time BETWEEN '2020-05-18 11:00:00' AND '2020-06-12 11:00:00' 
GROUP BY  DATE(killmails.killmail_time)

/* summary by sec and type*/
SELECT system_info.security as Sec,
 COUNT(system_info.security) as Total,
 SUM(case when killmails.ship_type_id = 35835 then 1 else 0 end) AS Athanor,
 SUM(case when killmails.ship_type_id = 35836 then 1 else 0 end) AS Tatara,
 SUM(case when killmails.ship_type_id = 35832 then 1 else 0 end) AS Astrahus,
 SUM(case when killmails.ship_type_id = 35833 then 1 else 0 end) AS Fortizar,
 SUM(case when killmails.ship_type_id = 35834 then 1 else 0 end) AS Keepstar,
 SUM(case when killmails.ship_type_id = 35825 then 1 else 0 end) AS Raitaru,
 SUM(case when killmails.ship_type_id = 35826 then 1 else 0 end) AS Azbel,
 SUM(case when killmails.ship_type_id = 35827 then 1 else 0 end) AS Sotiyo
FROM system_info
INNER JOIN
killmails
ON system_info.id = killmails.solar_system_id
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
AND killmails.killmail_time BETWEEN '2020-06-02 11:00:00' AND '2020-06-12 11:00:00' 
GROUP BY system_info.security


SELECT DISTINCT attackers.killmail_id, killmails.ship_type_id 
FROM `attackers` 
INNER JOIN `killmails` 
ON `attackers`.killmail_id = `killmails`.killmail_id 
WHERE (attackers.corporation_id = 98015080 
	AND killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834))

SELECT killmails.killmail_id 
FROM killmails 
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)

SELECT killmails.killmail_id, killmails.ship_type_id, system_info.name 
FROM killmails 
INNER JOIN system_info 
ON killmails.solar_system_id = system_info.id 
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)

SELECT killmails.killmail_id, killmails.ship_type_id, system_info.name, system_info.securityClass, system_info.securityStatus, system_info.security 
FROM killmails 
INNER JOIN system_info 
ON killmails.solar_system_id = system_info.id 
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834) 
AND system_info.security = 'H'

SELECT killmails.killmail_id, killmails.ship_type_id, system_info.name, system_info.securityClass, system_info.securityStatus, system_info.security, killmails.killmail_time 
FROM killmails 
INNER JOIN system_info 
ON killmails.solar_system_id = system_info.id 
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834) 
AND system_info.security IN ('C1','C2','C3','C4','C5','C6','C13') 
AND killmails.killmail_time BETWEEN '2020-06-02 11:00:00' AND '2020-06-11 11:00:00' 
ORDER BY killmail_time

SELECT killmails.killmail_id,
	types.name as shiptype,
  system_info.name as systemName,
  system_info.securityClass,
  system_info.securityStatus,
  system_info.security,
  characters.character_name
FROM killmails 
INNER JOIN system_info 
ON killmails.solar_system_id = system_info.id 
INNER JOIN types 
ON killmails.ship_type_id = types.id
INNER JOIN characters
ON characters.character_id = killmails.character_id
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834) 
AND system_info.security IN ('C1','C2','C3','C4','C5','C6','C13')

SELECT killmails.killmail_id,
	types.name as shiptype,
  system_info.name as systemName,
  system_info.securityClass,
  system_info.securityStatus,
  system_info.security,
  characters.character_name
FROM killmails
INNER JOIN characters
ON characters.character_id = killmails.character_id
INNER JOIN system_info 
ON killmails.solar_system_id = system_info.id 
INNER JOIN types 
ON killmails.ship_type_id = types.id
WHERE characters.character_name = 'SmallFatCat'

SELECT killmails.killmail_id, COUNT(attackers.character_id) AS NumberOfAttackers 
FROM attackers
LEFT JOIN killmails ON killmails.killmail_id = attackers.killmail_id
GROUP BY killmail_id;

SELECT
	killmails.killmail_id,
	COUNT(attackers.character_id) AS NumberOfAttackers,
    types.name,
    system_info.name AS solarSystem,
    system_info.security
FROM attackers
LEFT JOIN killmails 
ON killmails.killmail_id = attackers.killmail_id
LEFT JOIN types
ON types.id = killmails.ship_type_id
LEFT JOIN system_info
ON system_info.id = killmails.solar_system_id
WHERE killmails.ship_type_id IN (35835,35836,35825,35827,35826,35832,35833,35834)
AND system_info.security IN ('H')
GROUP BY killmail_id
ORDER BY NumberOfAttackers DESC

SELECT nerdDB.corp_assets.quantity,
nerdDB.corp_assets.location_id,
nerdDB.corp_assets.location_flag,
eve_sde.invtypes.typeName,
eve_sde.invtypes.basePrice,
eve_sde.invtypes.volume
FROM nerdDB.corp_assets
INNER JOIN eve_sde.invtypes
ON eve_sde.invtypes.typeID = nerdDB.corp_assets.type_id

SELECT nerdDB.corp_assets.quantity,
nerdDB.corp_assets.location_id,
nerdDB.corp_assets.location_flag,
nerdDB.corp_assets.location_type,
nerdDB.corp_assets.item_id,
eve_sde.invtypes.typeName,
eve_sde.invtypes.basePrice,
eve_sde.invtypes.volume
FROM nerdDB.corp_assets
INNER JOIN eve_sde.invtypes
ON eve_sde.invtypes.typeID = nerdDB.corp_assets.type_id 
WHERE nerdDB.corp_assets.is_blueprint_copy IS NULL  
ORDER BY `corp_assets`.`location_id`  ASC