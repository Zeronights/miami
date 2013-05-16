<?php

namespace Miami\Core;

class Auth {
	
	protected $salt = '';
	public function set_salt($salt) {
		$this->salt = $salt;
	}
	
	public function get_hash($data) {
		$bcrypt_salt = '$2a$07$R.gJb2U2N.FmZ4hPp1y2CN$';
		return crypt($data . $this->salt, $bcrypt_salt);
	}
}