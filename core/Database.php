<?php

namespace Miami\Core;

class Database extends \PDO {
	
	public function __construct(\stdClass $options) {
		
		$dsn = sprintf('%s:dbname=%s;host=%s;port=%d',
			$options->driver,
			$options->database,
			$options->host,
			$options->port
		);
		
		parent::__construct($dsn, $options->username, $options->password);
	}
	
	public function insert($table, $data) {
		$sql = sprintf('INSERT INTO %s ', $table);
		reset($data);
		$use_columns = !is_int(key($data));
		if ($use_columns) {
			$sql .= sprintf('(`%s`) ', implode('`,`', array_keys($data)));
		}
		$sql .= sprintf('VALUES (%s);', substr(str_repeat('?,', count($data)), 0, -1));
		$insert = $this->prepare($sql);
		return $insert->execute(array_values($data));
	}
}