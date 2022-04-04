<?php
class ControllerExtensionModuleFeedback extends Controller {

	private function getForm() {

		$this->load->language('extension/module/feedback');
		
		return $this->load->view('extension/module/feedback');
	}

	public function appendTo(&$route, &$data, &$output){
		
		if($this->config->get('module_feedback_status') && $route !== 'extension/module/feedback'){

			$output = preg_replace_callback("/{#\s*feedback_form\s*#}/", array($this, 'getForm'), $output);
		}
	}

	public function send(){

		$json = array();

		$this->load->language('extension/module/feedback');

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['phone']) < 10) || (utf8_strlen($this->request->post['phone']) > 255)) {
			$json['error']['phone'] = $this->language->get('error_phone');
		}

		if (!isset($json['error'])) {

			$this->load->model('extension/module/feedback');

			$this->model_extension_module_feedback->setFeedback($this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}