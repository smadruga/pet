<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model(array('Login_model', 'Basico_model'));
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('basico', 'form_validation', 'user_agent'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/headerlogin');

        if ($this->agent->is_browser()) {

            if (
                    (preg_match("/(chrome|Firefox)/i", $this->agent->browser()) && $this->agent->version() < 30) ||
                    (preg_match("/(safari)/i", $this->agent->browser()) && $this->agent->version() < 6) ||
                    (preg_match("/(opera)/i", $this->agent->browser()) && $this->agent->version() < 12) ||
                    (preg_match("/(internet explorer)/i", $this->agent->browser()) && $this->agent->version() < 9 )
            ) {
                $msg = '<h2><strong>Navegador n�o suportado.</strong></h2>';

                echo $this->basico->erro($msg);
                exit();
            }
        }
    }

    public function index() {

        #$_SESSION['log']['cliente'] = $_SESSION['log']['nome_modulo'] = 
        $_SESSION['log']['nome_modulo'] = $_SESSION['log']['modulo'] = $data['modulo'] = $data['nome_modulo'] = 'pet';
        
        ###################################################
        #s� pra eu saber quando estou no banco de testes ou de produ��o
        #$CI = & get_instance();
        #$CI->load->database();
        #if ($CI->db->database != 'sishuap')
        #echo $CI->db->database;
        ###################################################
        #change error delimiter view
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #Get GET or POST data
        $usuario = $this->input->get_post('Usuario');
        $senha = md5($this->input->get_post('Senha'));

        #set validation rules
        $this->form_validation->set_rules('Usuario', 'Usu�rio', 'required|trim|callback_valid_usuario');
        $this->form_validation->set_rules('Senha', 'Senha', 'required|trim|md5|callback_valid_senha[' . $usuario . ']');

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 3)
            $data['msg'] = $this->basico->msg('<strong>Sua sess�o expirou. Fa�a o login novamente.</strong>', 'erro', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 4)
            $data['msg'] = $this->basico->msg('<strong>Usu�rio ativado com sucesso! Fa�a o login para acessar o sistema.</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 5)
            $data['msg'] = $this->basico->msg('<strong>Link expirado.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            #load login view
            $this->load->view('login/form_login', $data);
        } else {

            session_regenerate_id(true);

            #Get GET or POST data
            #$usuario = $this->input->get_post('Usuario');
            #$senha = md5($this->input->get_post('Senha'));
            /*
              echo "<pre>";
              print_r($query);
              echo "</pre>";
              exit();
             */
            $query = $this->Login_model->check_dados_usuario($senha, $usuario, TRUE);
            $_SESSION['log']['Agenda'] = $this->Login_model->get_agenda_padrao($query['idSis_Usuario']);

            #echo "<pre>".print_r($query)."</pre>";
            #exit();

            if ($query === FALSE) {
                #$msg = "<strong>Senha</strong> incorreta ou <strong>usu�rio</strong> inexistente.";
                #$this->basico->erro($msg);
                $data['msg'] = $this->basico->msg('<strong>Senha</strong> incorreta.', 'erro', FALSE, FALSE, FALSE);
                $this->load->view('form_login', $data);
                                        
            } else {
                #initialize session
                $this->load->driver('session');

                $_SESSION['log']['Usuario'] = $query['Usuario'];
                $_SESSION['log']['id'] = $query['idSis_Usuario'];

                if ($this->Login_model->set_acesso($_SESSION['log']['id'], 'LOGIN') === FALSE) {
                    $msg = "<strong>Erro no Banco de dados. Entre em contato com o Administrador.</strong>";

                    $this->basico->erro($msg);
                    $this->load->view('form_login');
                } else {
                    redirect('agenda');
                }
            }
        }

        #load footer view
        $this->load->view('basico/footerlogin');
        $this->load->view('basico/footer');
    }

    public function registrar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'Email',
            'Usuario',
            'Nome',
            'Senha',
            'Confirma',
            'DataNascimento',
            'Celular',
            'Sexo',
                ), TRUE);

        $this->form_validation->set_error_delimiters('<h5 style="color: red;">', '</h5>');

        $this->form_validation->set_rules('Email', 'E-mail', 'required|trim|valid_email|is_unique[Sis_Usuario.Email]');
        $this->form_validation->set_rules('Usuario', 'Usu�rio', 'required|trim|is_unique[Sis_Usuario.Usuario]');
        $this->form_validation->set_rules('Nome', 'Nome e Sobrenome', 'required|trim');
        $this->form_validation->set_rules('Senha', 'Senha', 'required|trim');
        $this->form_validation->set_rules('Confirma', 'Confirmar Senha', 'required|trim|matches[Senha]');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'required|trim|valid_date');

        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            #load login view
            $this->load->view('login/form_registrar', $data);
        } else {

            $data['query']['Senha'] = md5($data['query']['Senha']);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Codigo'] = md5(uniqid(time() . rand()));
            #$data['query']['Inativo'] = 1;
            //ACESSO LIBERADO PRA QUEM REALIZAR O CADASTRO
            $data['query']['Inativo'] = 0;
            unset($data['query']['Confirma']);

            $data['anterior'] = array();
            $data['campos'] = array_keys($data['query']);

            $data['idSis_Usuario'] = $this->Login_model->set_usuario($data['query']);
            $_SESSION['log']['id'] = 1;

            if ($data['idSis_Usuario'] === FALSE) {
                $data['msg'] = '?m=2';
                $this->load->view('login/form_login', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idSis_Usuario']);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'Sis_Usuario', 'CREATE', $data['auditoriaitem'], $data['idSis_Usuario']);
                /*
                  echo $this->db->last_query();
                  echo "<pre>";
                  print_r($data);
                  echo "</pre>";
                  exit();
                 */
                $data['agenda'] = array(
                    'NomeAgenda' => 'Padr�o',
                    'idSis_Usuario' => $data['idSis_Usuario']
                );
                $data['campos'] = array_keys($data['agenda']);

                $data['idApp_Agenda'] = $this->Login_model->set_agenda($data['agenda']);
                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['agenda'], $data['campos'], $data['idSis_Usuario']);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Agenda', 'CREATE', $data['auditoriaitem'], $data['idSis_Usuario']);

                $this->load->library('email');

                $this->email->from('nao_responda@romati.com.br', 'ROMATI');
                $this->email->to($data['query']['Email']);

                $this->email->subject('[ROMATI] Confirma��o de registro - Usu�rio: ' . $data['query']['Usuario']);
                /*
                  $this->email->message('Por favor, clique no link a seguir para confirmar seu registro: '
                  . 'http://www.romati.com.br/app/login/confirmar/' . $data['query']['Codigo']);

                  $this->email->send();

                  $data['aviso'] = ''
                  . '
                  <div class="alert alert-success" role="alert">
                  <h4>
                  <p><b>Usu�rio cadastrado com sucesso!</b></p>
                  <p>O link para ativa��o foi enviado para seu e-mail cadastrado.</p>
                  <p>Caso o e-mail com o link n�o esteja na sua caixa de entrada <b>verifique tamb�m sua caixa de SPAM</b>.</p>
                  </h4>
                  </div> '
                  . '';
                 */

                $this->email->message('Sua conta foi ativada com sucesso! Aproveite e teste todas as funcionalidades do sistema.'
                        . 'Qualquer sugest�o ou cr�tica ser� bem vinda. ');

                $this->email->send();

                $data['aviso'] = ''
                        . '
                  <div class="alert alert-success" role="alert">
                  <h4>
                  <p><b>Usu�rio cadastrado com sucesso!</b></p>
                  <p>Clique no bot�o abaixo e retorne para a tela de login para entrar no sistema.</p>
                  </h4>
                  <br>
                  <a class="btn btn-primary" href="http://www.romati.com.br/app/" role="button">Acessar o aplicativo</a>
                  </div> '
                        . '';

                $this->load->view('login/tela_msg', $data);
                #redirect(base_url() . 'login' . $data['msg']);
                #exit();
            }
        }

        $this->load->view('basico/footerlogin');
        $this->load->view('basico/footer');
    }

    public function confirmar($codigo) {

        $data['anterior'] = array(
            'Inativo' => '1',
            'Codigo' => $codigo
        );

        $data['confirmar'] = array(
            'Inativo' => '0',
            'Codigo' => 'NULL'
        );

        $data['campos'] = array_keys($data['confirmar']);
        $id = $this->Login_model->get_data_by_codigo($codigo);

        if ($this->Login_model->ativa_usuario($codigo, $data['confirmar']) === TRUE) {

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['confirmar'], $data['campos'], $id['idSis_Usuario'], TRUE);
            $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'Sis_Usuario', 'UPDATE', $data['auditoriaitem'], $id['idSis_Usuario']);

            $data['msg'] = '?m=4';
            redirect(base_url() . 'login/' . $data['msg']);
        } else {
            $data['msg'] = '?m=5';
            redirect(base_url() . 'login/' . $data['msg']);
        }
    }

    public function recuperar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'Usuario',
                ), TRUE);

        if (isset($_GET['usuario']))
            $data['query']['Usuario'] = $_GET['usuario'];

        $this->form_validation->set_error_delimiters('<h5 style="color: red;">', '</h5>');

        $this->form_validation->set_rules('Usuario', 'Usuario', 'required|trim|callback_valid_usuario');

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            #load login view
            $this->load->view('login/form_recuperar', $data);
        } else {

            $data['query']['Codigo'] = md5(uniqid(time() . rand()));

            $id = $this->Login_model->get_data_by_usuario($data['query']['Usuario']);

            if ($this->Login_model->troca_senha($id['idSis_Usuario'], array('Codigo' => $data['query']['Codigo'])) === FALSE) {

                $data['anterior'] = array(
                    'Codigo' => 'NULL'
                );

                $data['confirmar'] = array(
                    'Codigo' => $data['query']['Codigo']
                );

                $data['campos'] = array_keys($data['confirmar']);

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['confirmar'], $data['campos'], $id['idSis_Usuario'], TRUE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'Sis_Usuario', 'UPDATE', $data['auditoriaitem'], $id['idSis_Usuario']);

                $this->load->library('email');

                $this->email->from('nao_responda@romati.com.br', 'ROMATI');
                $this->email->to($id['Email']);

                $this->email->subject('[ROMATI] Altera��o de Senha - Usu�rio: ' . $data['query']['Usuario']);
                $this->email->message('Por favor, clique no link a seguir para alterar sua senha: '
                        . 'http://www.romati.com.br/app/login/trocar_senha/' . $data['query']['Codigo']);

                $this->email->send();

                $data['aviso'] = ''
                        . '   
                    <div class="alert alert-success" role="alert">
                        <h4>
                            <p><b>Link enviado com sucesso!</b></p>
                            <p>O link para alterar senha foi enviado para seu e-mail.</p>
                            <p>Caso o e-mail com o link n�o esteja na sua caixa de entrada <b>verifique tamb�m sua caixa de SPAM</b>.</p>
                        </h4>
                    </div> '
                        . '';

                #$data['msg'] = '?m=4';
                $this->load->view('login/tela_msg', $data);
            } else {
                $data['msg'] = '?m=5';
                redirect(base_url() . 'login/' . $data['msg']);
            }
        }
    }

    public function trocar_senha($codigo = NULL) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informa��es salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idSis_Usuario',
            'Email',
            'Usuario',
            'Codigo',
                ), TRUE);

        if ($codigo) {
            $data['query'] = $this->Login_model->get_data_by_codigo($codigo);
            $data['query']['Codigo'] = $codigo;
        } else {
            $data['query']['Codigo'] = $this->input->post('Codigo', TRUE);
        }

        $data['query']['Senha'] = $this->input->post('Senha', TRUE);
        $data['query']['Confirma'] = $this->input->post('Confirma', TRUE);

        $this->form_validation->set_error_delimiters('<h5 style="color: red;">', '</h5>');

        $this->form_validation->set_rules('Senha', 'Senha', 'required|trim');
        $this->form_validation->set_rules('Confirma', 'Confirmar Senha', 'required|trim|matches[Senha]');
        #$this->form_validation->set_rules('Codigo', 'C�digo', 'required|trim');
        #run form validation
        if ($this->form_validation->run() === FALSE) {
            #load login view
            $this->load->view('login/form_troca_senha', $data);
        } else {

            ###n�o est� registrando a auditoria do trocar senha. tenho que ver isso
            ###ver tamb�m o link para troca, quando expirado avisar
            #$id = $data['query']['Senha']
            $data['query']['Senha'] = md5($data['query']['Senha']);
            $data['query']['Codigo'] = NULL;
            unset($data['query']['Confirma']);

            $data['anterior'] = array();
            $data['campos'] = array_keys($data['query']);

            if ($this->Login_model->troca_senha($data['query']['idSis_Usuario'], $data['query']) === TRUE) {
                $data['msg'] = '?m=2';
                $this->load->view('login/form_troca_senha', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idSis_Usuario'], TRUE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'Sis_Usuario', 'UPDATE', $data['auditoriaitem'], $data['query']['idSis_Usuario']);
                /*
                  echo $this->db->last_query();
                  echo "<pre>";
                  print_r($data);
                  echo "</pre>";
                  exit();
                 */
                $data['msg'] = '?m=1';
                redirect(base_url() . 'login' . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footerlogin');
        $this->load->view('basico/footer');
    }

    public function sair($m = TRUE) {
        #change error delimiter view
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        #set logout in database
        if ($_SESSION['log'] && $m === TRUE) {
            $this->Login_model->set_acesso($_SESSION['log']['id'], 'LOGOUT');
        } else {
            if (!isset($_SESSION['log']['id'])) {
                $_SESSION['log']['id'] = 1;
            }
            $this->Login_model->set_acesso($_SESSION['log']['id'], 'TIMEOUT');
            $data['msg'] = '?m=2';
        }

        #clear de session data
        $this->session->unset_userdata('log');
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage

        /*
          #load header view
          $this->load->view('basico/headerlogin');

          $msg = "<strong>Voc� saiu do sistema.</strong>";

          $this->basico->alerta($msg);
          $this->load->view('login');
          $this->load->view('basico/footer');
         * 
         */

        redirect(base_url() . 'login/' . $data['msg']);
        #redirect('login');
    }

    function valid_usuario($data) {

        if ($this->Login_model->check_usuario($data) == 1) {
            $this->form_validation->set_message('valid_usuario', '<strong>%s</strong> n�o existe.');
            return FALSE;
        } else if ($this->Login_model->check_usuario($data) == 2) {
            $this->form_validation->set_message('valid_usuario', '<strong>%s</strong> inativo.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function valid_senha($senha, $usuario) {

        if ($this->Login_model->check_dados_usuario($senha, $usuario) == FALSE) {
            $this->form_validation->set_message('valid_senha', '<strong>%s</strong> incorreta.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
