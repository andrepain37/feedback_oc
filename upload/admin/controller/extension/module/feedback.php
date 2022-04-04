<?php
class ControllerExtensionModuleFeedback extends Controller {

	public function index() {
		$this->load->language('extension/module/feedback');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->model_setting_setting->editSetting('module_feedback', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/feedback', 'user_token=' . $this->session->data['user_token'], true)
		);

		if (isset($this->request->post['module_feedback_status'])) {
			$data['module_feedback_status'] = $this->request->post['module_feedback_status'];
		} else {
			$data['module_feedback_status'] = $this->config->get('module_feedback_status');
		}

		$data['action'] = $this->url->link('extension/module/feedback', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$this->response->setOutput($this->load->view('extension/module/feedback', $data));
	}
	
	public function install() {
		$this->db->query("CREATE TABLE IF NOT EXISTS ".DB_PREFIX."feedback (
			`feedback_id` INT(11) NOT NULL AUTO_INCREMENT, 
			`name` VARCHAR(255) NOT NULL,
			`email` VARCHAR(255) NOT NULL,
			`phone` VARCHAR(255) NOT NULL,
			`date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`feedback_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		$this->load->model('setting/event');
		$this->model_setting_event->addEvent('feedback_append', 'catalog/view/*/*/after', 'extension/module/feedback/appendTo');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "feedback");
		$this->load->model('setting/event');
       $this->model_setting_event->deleteEventByCode('feedback_append');
	}
	
}