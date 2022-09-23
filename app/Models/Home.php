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
}
