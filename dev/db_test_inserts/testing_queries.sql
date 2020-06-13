/* summary by day*/

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

/* summary by sec*/
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