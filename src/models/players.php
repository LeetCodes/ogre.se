<?php
require_once dirname(__FILE__) . "/../db/db.php";
require_once dirname(__FILE__) . "/../pkg/runecrawler/crawler.php";

class Players {
	static function GetAllPlayers() {
		global $db;
		$sql = "
		SELECT * FROM player
		WHERE active
		";
		$query = $db->prepare($sql);
		$query->execute();
		$players = $query->fetchAll(PDO::FETCH_ASSOC);
		if ($players) {
			return $players;
		}
		else {
			return false;
		}
	}
	
	static function GetPlayerIdByName($name) {
		global $db;
		$sql = "
		SELECT id FROM player
		WHERE name ILIKE ?
		LIMIT 1
		";
		$query = $db->prepare($sql);
		$query->execute([
			$name
		]);
		$player = $query->fetchColumn();
		if ($player) {
			return $player;
		}
		else {
			return false;
		}
	}
	
	static function AddPlayer($name) {
		if (!Crawler::Fetch($name)) {
			return false;
		}
		global $db;
		$sql = "
		INSERT INTO player(name)
		VALUES (?)
		";
		$query = $db->prepare($sql);
		$query->execute([
			$name
		]);
		if ($query->rowCount() != 0) {
			return $db->lastInsertId("player_id_seq");
		} else {
			return false;
		}
	}
};
?>
