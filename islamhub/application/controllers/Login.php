<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
        parent::__construct();

		$this->load->model('TabelClient');
		$this->load->model('TabelPakar');
    }

	public function index()
	{
		$this->load->view('login');
    }
    
    public function actionLogin(){
	    $rules[] = array('field' => 'email',	'label' => 'Email', 'rules' => 'required');
		$rules[] = array('field' => 'password', 'label' => 'Password', 'rules' => 'required');
		$rules[] = array('field' => 'sebagai', 'label' => 'Sebagai', 'rules' => 'required');
		$this->form_validation->set_rules($rules);
		if ($this->form_validation->run() == FALSE){
			$this->session->set_flashdata('message', validation_errors());
            $this->session->set_flashdata('type_message','danger');
			redirect('Login');
		}else{
			$data = array(
				'email' => $this->input->post('email'),
				'password' => md5(md5($this->input->post('password'))),
			);
			if($this->input->post('sebagai') == "client"){
				$tabelLogin = $this->TabelClient->whereAnd($data);
				if ($tabelLogin->num_rows() > 0) {
					$tabelLogin = $tabelLogin->row();
					$data = array(
						'logged' 		=> TRUE,
						'pakar'			=> FALSE,
						'client'			=> TRUE,
                        'id'		=> $tabelLogin->id_client,
						'email'		=> $tabelLogin->email,
						'nama'		=> $tabelLogin->nama,
                    );
                    $this->session->set_userdata($data);
                    redirect('Dashboard');
				}else{
					$this->session->set_flashdata('message','Gagal Login. Periksa kembali email dan password.');
					$this->session->set_flashdata('type_message','danger');
					redirect('Login');
				}
			}else{
				$tabelLogin = $this->TabelPakar->whereAnd($data);
				if ($tabelLogin->num_rows() > 0) {
					$tabelLogin = $tabelLogin->row();
					$data = array(
						'logged' 		=> TRUE, 
						'pakar'			=> TRUE,
						'client'			=> FALSE,
                        'id'		=> $tabelLogin->id_pakar,
						'email'		=> $tabelLogin->email,
						'nama'		=> $tabelLogin->nama,
                    );
                    $this->session->set_userdata($data);
                    redirect('Dashboard');
				}else{
					$this->session->set_flashdata('message','Gagal Login. Periksa kembali email dan password.');
					$this->session->set_flashdata('type_message','danger');
					redirect('Login');
				}
			}
		}
    }
    
    public function Logout(){
		
		$this->session->sess_destroy();
		redirect('Login');
	}
}
