<?php

namespace Apps\Admin\Controller;

class Login extends \Apps\Admin\Classes\Controller {
	
	public function action_index() {
		$this->view->set_view('login');
		return $this->view;
	}
	
	public function action_process() {
		$form = new \Miami\Classes\Form('login_');
		$form->set_required_field('username');
		$form->set_required_field('password', array(
			'pre_process' => function($value) {
				return $this->auth->get_hash($value);
			}
		));
		$login = $form->submit(function($fields) {
			$login = $this->database->prepare('
				SELECT
					`id`
				FROM
					`user`
				WHERE
					`username` = :username
				AND
					`password` = :password
				LIMIT 1
			');
			$login = $login->execute(array(
				':username' => $fields->username,
				':password' => $fields->password
			));
			return $login;
		});
		if ($login) {
			$this->route->redirect('/dashboard/');
		} else {
			$this->session->set_flash('login.error', 'Incorrect login details.');
			$this->route->redirect('/login/');
		}
	}
}