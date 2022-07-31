<?php

namespace App\Controllers;


use CodeIgniter\RESTful\ResourceController;

use App\Models\BlogModel;

class Blog extends ResourceController
{
    public function index()
    {

        $model = new BlogModel();
        return $this->respond($model->findAll());

    }
}
