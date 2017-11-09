get_killmails_killmail_id_killmail_hash_ok {
	killmail_id (integer): ID of the killmail ,
	killmail_time (string): Time that the victim was killed and the killmail generated ,
	victim (get_killmails_killmail_id_killmail_hash_victim): victim object ,
	attackers (Array[get_killmails_killmail_id_killmail_hash_attacker]): attackers array ,
	solar_system_id (integer): Solar system that the kill took place in ,
	moon_id (integer, optional): Moon if the kill took place at one ,
	war_id (integer, optional): War if the killmail is generated in relation to an official war
}
	get_killmails_killmail_id_killmail_hash_victim {
	character_id (integer, optional): character_id integer ,
	corporation_id (integer, optional): corporation_id integer ,
	alliance_id (integer, optional): alliance_id integer ,
	faction_id (integer, optional): faction_id integer ,
	damage_taken (integer): How much total damage was taken by the victim ,
	ship_type_id (integer): The ship that the victim was piloting and was destroyed ,
	items (Array[get_killmails_killmail_id_killmail_hash_item], optional): items array ,
	position (get_killmails_killmail_id_killmail_hash_position, optional): Coordinates of the victim in Cartesian space relative to the Sun
}
	get_killmails_killmail_id_killmail_hash_attacker {
	character_id (integer, optional): character_id integer ,
	corporation_id (integer, optional): corporation_id integer ,
	alliance_id (integer, optional): alliance_id integer ,
	faction_id (integer, optional): faction_id integer ,
	security_status (number): Security status for the attacker ,
	final_blow (boolean): Was the attacker the one to achieve the final blow ,
	damage_done (integer): damage_done integer ,
	ship_type_id (integer, optional): What ship was the attacker flying ,
	weapon_type_id (integer, optional): What weapon was used by the attacker for the kill
}
	get_killmails_killmail_id_killmail_hash_item {
	item_type_id (integer): item_type_id integer ,
	quantity_destroyed (integer, optional): How many of the item were destroyed if any ,
	quantity_dropped (integer, optional): How many of the item were dropped if any ,
	singleton (integer): singleton integer ,
	flag (integer): Flag for the location of the item ,
	items (Array[get_killmails_killmail_id_killmail_hash_item], optional): items array
}
get_killmails_killmail_id_killmail_hash_position {
	x (number): x number ,
	y (number): y number ,
	z (number): z number
}