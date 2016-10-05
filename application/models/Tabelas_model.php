<?php

#modelo que verifica usuário e senha e loga o usuário no sistema, criando as sessões necessárias

defined('BASEPATH') OR exit('No direct script access allowed');

class Tabelas_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('basico');
    }
   
    public function set_veterinario($data) {

        $query = $this->db->insert('App_Veterinario', $data);

        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            #return TRUE;
            return $this->db->insert_id();
        }
    }

    public function get_veterinario($data) {
        $query = $this->db->query('SELECT * FROM App_Veterinario WHERE idApp_Veterinario = ' . $data);
        $query = $query->result_array();

        return $query[0];
    }

    public function update_veterinario($data, $id) {

        unset($data['Id']);
        $query = $this->db->update('App_Veterinario', $data, array('idApp_Veterinario' => $id));
        /*
          echo $this->db->last_query();
          echo '<br>';
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit ();
         */
        if ($this->db->affected_rows() === 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function lista_veterinario($x) {

        $query = $this->db->query('SELECT * '
                . 'FROM App_Veterinario '
                . 'WHERE idSis_Usuario = ' . $_SESSION['log']['id'] . ' '
                . 'ORDER BY NomeVeterinario ASC ');
        
        /*
          echo $this->db->last_query();
          $query = $query->result_array();
          echo "<pre>";
          print_r($query);
          echo "</pre>";
          exit();
        */
        if ($query->num_rows() === 0) {
            return FALSE;
        } else {
            if ($x === FALSE) {
                return TRUE;
            } else {
                #foreach ($query->result_array() as $row) {
                #    $row->idApp_Veterinario = $row->idApp_Veterinario;
                #    $row->NomeVeterinario = $row->NomeVeterinario;
                #}
                $query = $query->result_array();
                return $query;
            }
        }
    }
    
}
