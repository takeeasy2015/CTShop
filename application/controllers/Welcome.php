<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}


	// Lesson9 practice1
	public function hello() {
		$this->load->view('hello');
	}

	// Lesson9 practice2
    public function heyman() {
      $view_data = array(
         'title' => 'heyman page',
         'name' => 'Mark'
      );

      $this->load->view('heyman', $view_data);

    }

	public function testSendMail() {
        $to = "markkao@ct.org.tw";
        $subject = "My subject";
        $txt = "Hello world!";
        $headers = "From: testmybaby00@gmail.com";

	    echo "mail ready send.";
        if (mail($to,$subject,$txt,$headers)) {
	        echo "mail send.";
	    } else {
	        echo "mail not send.";
        }
    }

	function checkPhpInfo() {
		echo phpinfo();
	}

}
