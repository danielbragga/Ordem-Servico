<?php
defined('BASEPATH') or exit('Ação não permitida');

class Core_model extends CI_Model
{
	public function get_all($tabela = null, $condicao = null)
	{
		if ($tabela) {
			if (is_array($condicao)) {
				$this->db->where($condicao);
			}
			return $this->db->get($tabela)->result();
		} else {
			return false;
		}
	}

	public function get_by_id($tabela = null, $condicao = null)
	{
		if ($tabela && is_array($condicao)) {
			$this->db->where($condicao);
			$this->db->limit(1);

			return $this->db->get($tabela)->row();
		} else {
			return false;
		}
	}

	public function insert($tabela = null, $data = null, $get_last_id = null)
	{
		if ($tabela && is_array($data)) {
			$this->db->insert($tabela, $data);

			if ($get_last_id) {
				$this->session->set_userdata('last_id', $this->db->insert_id());
			}

			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('success', 'Registro inserido com sucesso!');
			} else {
				$this->session->set_flashdata('error', 'Erro ao inserir registro!');
			}
		} else {
			return false;
		}
	}

	public function update($tabela = null, $data = null, $condicao = null)
	{
		if ($tabela && is_array($data) && is_array($condicao)) {
			if ($this->db->update($tabela, $data, $condicao)) {
				$this->session->set_flashdata('success', 'Registro atualizado com sucesso!');
			} else {
				$this->session->set_flashdata('error', 'Erro ao atualizar registro!');
			}
		} else {
			return false;
		}
	}

	public function delete($tabela = null, $condicao = null)
	{
		$this->db->db_debug = false;

		if ($tabela && is_array($condicao)) {
			if ($this->db->delete($tabela, $condicao)) {
				$status = $this->db->delete($tabela, $condicao);
				$error = $this->db->error();

				if (!$status) {
					foreach ($error as $code) {
						if ($code == 1451) {
							$this->session->set_flashdata('error', 'Esse registro não pode ser excluído pois ele está relacionado a outros registros!');
						}
					}
				} else {
					$this->session->set_flashdata('success', 'Registro excluído com sucesso!');
				}

				$this->db->db_debug = true;

			}
		} else {
			return false;
		}
	}
}
