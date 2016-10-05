<?php

#controlador de Login

defined('BASEPATH') OR exit('No direct script access allowed');

class Pet extends CI_Controller {

    public function __construct() {
        parent::__construct();

        #load libraries
        $this->load->helper(array('form', 'url', 'date', 'string'));
        #$this->load->library(array('basico', 'Basico_model', 'form_validation'));
        $this->load->library(array('basico', 'form_validation'));
        $this->load->model(array('Basico_model', 'Pet_model', 'Responsavel_model'));
        $this->load->driver('session');

        #load header view
        $this->load->view('basico/header');
        $this->load->view('basico/nav_principal');

        #$this->load->view('pet/nav_secundario');
    }

    public function index() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $this->load->view('pet/tela_index', $data);

        #load footer view
        $this->load->view('basico/footer');
    }

    public function cadastrar() {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = quotes_to_entities($this->input->post(array(
            'idApp_Pet',
            
            'NomePet',
            'StatusVida',
            'DataNascimento',
            'Sexo',
            
            'Especie',
            'Raca',
            'Pelo',
            'Cor',
            
            'Obs',
            'idApp_Responsavel',
                        ), TRUE));

        //echo '<br><br><br><br><br>==========================================='.$data['query']['StatusVida']='V';
        
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('NomePet', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');

        $data['select']['Sexo'] = $this->Basico_model->select_sexo();
        $data['select']['StatusVida'] = $this->Pet_model->select_status_vida();

        $data['titulo'] = 'Cadastrar Pet';
        $data['form_open_path'] = 'pet/cadastrar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 1;

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);
        
        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/form_pet', $data);
        } else {

            $data['query']['NomePet'] = trim(mb_strtoupper($data['query']['NomePet'], 'ISO-8859-1'));
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);

            $data['campos'] = array_keys($data['query']);
            $data['anterior'] = array();

            $data['idApp_Pet'] = $this->Pet_model->set_pet($data['query']);

            if ($data['idApp_Pet'] === FALSE) {
                $msg = "<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>";

                $this->basico->erro($msg);
                $this->load->view('pet/form_pet', $data);
            } else {

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['idApp_Pet'], FALSE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Pet', 'CREATE', $data['auditoriaitem']);
                $data['msg'] = '?m=1';

                redirect(base_url() . 'pet/pesquisar/' . $_SESSION['Responsavel']['idApp_Responsavel'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function alterar($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Pet',
            
            'NomePet',
            'StatusVida',
            'DataNascimento',
            'Sexo',
            
            'Especie',
            'Raca',
            'Pelo',
            'Cor',
            
            'Obs',
            'idApp_Responsavel',
                ), TRUE);

        if ($id) {
            $data['query'] = $this->Pet_model->get_pet($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
            $_SESSION['log']['idApp_Pet'] = $id;
        }

        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');

        $this->form_validation->set_rules('NomePet', 'Nome do Responsável', 'required|trim');
        $this->form_validation->set_rules('DataNascimento', 'Data de Nascimento', 'trim|valid_date');

        $data['select']['Sexo'] = $this->Basico_model->select_sexo();
        $data['select']['StatusVida'] = $this->Pet_model->select_status_vida();
               
        $data['titulo'] = 'Editar Dados';
        $data['form_open_path'] = 'pet/alterar';
        $data['readonly'] = '';
        $data['disabled'] = '';
        $data['panel'] = 'primary';
        $data['metodo'] = 2;

        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/form_pet', $data);
        } else {

            $data['query']['NomePet'] = trim(mb_strtoupper($data['query']['NomePet'], 'ISO-8859-1'));
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'mysql');
            $data['query']['Obs'] = nl2br($data['query']['Obs']);
            $data['query']['idApp_Pet'] = $_SESSION['log']['idApp_Pet'];

            $data['anterior'] = $this->Pet_model->get_pet($data['query']['idApp_Pet']);
            $data['campos'] = array_keys($data['query']);

            $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], $data['query'], $data['campos'], $data['query']['idApp_Pet'], TRUE);

            if ($data['auditoriaitem'] && $this->Pet_model->update_pet($data['query'], $data['query']['idApp_Pet']) === FALSE) {
                $data['msg'] = '?m=2';
                redirect(base_url() . 'pet/form_pet/' . $data['query']['idApp_Pet'] . $data['msg']);
                exit();
            } else {

                if ($data['auditoriaitem'] === FALSE) {
                    $data['msg'] = '';
                } else {
                    $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Pet', 'UPDATE', $data['auditoriaitem']);
                    $data['msg'] = '?m=1';
                }

                redirect(base_url() . 'pet/pesquisar/' . $_SESSION['Responsavel']['idApp_Responsavel'] . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function excluir($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        $data['query'] = $this->input->post(array(
            'idApp_Pet',
            'submit'
                ), TRUE);

        if ($id) {
            $data['query'] = $this->Pet_model->get_pet($id);
            $data['query']['DataNascimento'] = $this->basico->mascara_data($data['query']['DataNascimento'], 'barras');
            $data['query']['PetDataNascimento'] = $this->basico->mascara_data($data['query']['PetDataNascimento'], 'barras');
        }

        $data['select']['Municipio'] = $this->Basico_model->select_municipio();
        $data['select']['Sexo'] = $this->Basico_model->select_sexo();

        $data['titulo'] = 'Tem certeza que deseja excluir o registro abaixo?';
        $data['form_open_path'] = 'pet/excluir';
        $data['readonly'] = 'readonly';
        $data['disabled'] = 'disabled';
        $data['panel'] = 'danger';
        $data['metodo'] = 3;

        $data['tela'] = $this->load->view('pet/form_pet', $data, TRUE);

        #run form validation
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('pet/tela_pet', $data);
        } else {

            if ($data['query']['idApp_Pet'] === FALSE) {
                $data['msg'] = '?m=2';
                $this->load->view('pet/form_pet', $data);
            } else {

                $data['anterior'] = $this->Pet_model->get_pet($data['query']['idApp_Pet']);
                $data['campos'] = array_keys($data['anterior']);

                $data['auditoriaitem'] = $this->basico->set_log($data['anterior'], NULL, $data['campos'], $data['query']['idApp_Pet'], FALSE, TRUE);
                $data['auditoria'] = $this->Basico_model->set_auditoria($data['auditoriaitem'], 'App_Pet', 'DELETE', $data['auditoriaitem']);

                $this->Pet_model->delete_pet($data['query']['idApp_Pet']);

                $data['msg'] = '?m=1';

                redirect(base_url() . 'pet' . $data['msg']);
                exit();
            }
        }

        $this->load->view('basico/footer');
    }

    public function pesquisar($id = FALSE) {

        if ($this->input->get('m') == 1)
            $data['msg'] = $this->basico->msg('<strong>Informações salvas com sucesso</strong>', 'sucesso', TRUE, TRUE, TRUE);
        elseif ($this->input->get('m') == 2)
            $data['msg'] = $this->basico->msg('<strong>Erro no Banco de dados. Entre em contato com o administrador deste sistema.</strong>', 'erro', TRUE, TRUE, TRUE);
        else
            $data['msg'] = '';

        if ($this->input->get('start') && $this->input->get('end')) {
            //$data['start'] = substr($this->input->get('start'),0,-3);
            //$data['end'] = substr($this->input->get('end'),0,-3);
            $_SESSION['agenda']['HoraInicio'] = substr($this->input->get('start'), 0, -3);
            $_SESSION['agenda']['HoraFim'] = substr($this->input->get('end'), 0, -3);
        }

        $_SESSION['Responsavel'] = $this->Responsavel_model->get_responsavel($id, TRUE);
        
        //echo date('d/m/Y H:i:s', $data['start'],0,-3));

        $data['query'] = $this->Pet_model->lista_pet(TRUE);
        /*
          echo "<pre>";
          print_r($data['query']);
          echo "</pre>";
          exit();
         */
        if (!$data['query'])
            $data['list'] = FALSE;
        else
            $data['list'] = $this->load->view('pet/list_pet', $data, TRUE);
        
        $data['nav_secundario'] = $this->load->view('responsavel/nav_secundario', $data, TRUE);

        $this->load->view('pet/tela_pet', $data);

        $this->load->view('basico/footer');
    }

}
