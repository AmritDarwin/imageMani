<?php

namespace App\Models;

use CodeIgniter\Model;

class Home extends Model
{
    public function addTemplate($data)
    {
        return $this->db->table('template')->insert($data);
    }

    public function getTemplate($name)
    {
        return $this->db->table('template')->where('name', $name)->get()->getRowArray();
    }

    public function getAllImages()
    {
        return $this->db->table('template')->get()->getResultArray();
    }

    public function editTemplate($id, $data)
    {
        return $this->db->table('template')->where('id', $id)->update($data);
    }

    public function deleteTemplate($id)
    {
        return $this->db->table('template')->where('id', $id)->delete();
    }
}
