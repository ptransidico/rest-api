<?php

namespace App\Controllers;

use App\Models\BlogModel;
use CodeIgniter\RESTful\ResourceController;

class Blog extends ResourceController
{
    public function index()
    {

        $model = new BlogModel();
        return $this->respond($model->findAll());

    }

    public function create()
    {
        $model = new BlogModel();
        helper(['form']);
        $rules = [
            'title' => 'required|min_length[6]',
            'description' => 'required',
            'featured_image' => 'uploaded[featured_image]|max_size[featured_image,3048]!is_image[featured_image]',
        ];

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator->getErrors();
            return $this->fail($data);

        } else {

            $file = $this->request->getFile('featured_image');
            if (!$file->isValid()) {
                return $this->fail($file->getErrorString());
            }

            $file->move('./assets/uploads');

            $data = [
                'post_title' => $this->request->getPost('title'),
                'post_description' => $this->request->getPost('description'),
                'post_featured_image' => $file->getName(),

            ];

            $post_id = $model->insert($data);
            $data['post_id'] = $post_id;
            return $this->respond($data);

        }

    }

    public function show($id = null)
    {

        $model = new BlogModel();
        $data = $model->find($id);
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound("Item not found");
        }
    }

    public function update($id = null)
    {

        $model = new BlogModel();
        helper(['form','array']);

        $rules = [
            'title' => 'required|min_length[6]',
            'description' => 'required|min_length[6]',
        ];

        $fileName=dot_array_search('featured_image.name',$_FILES);

        if ($fileName != '') {
            $img = ['featured_image' => 'uploaded[featured_image]|max_size[featured_image,3048]!is_image[featured_image]'];
            $rules = array_merge($rules,$img);
        }

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator->getErrors();
            return $this->fail($data);

        } else {

            //$input = $this->request->getRawInput();
            $data = [
                'post_id' => $id,
                'post_title' => $this->request->getVar('title'),
                'post_description' => $this->request->getVar('description'),
            ];

            if ($fileName != '') {

                $file = $this->request->getFile('featured_image');
                if (!$file->isValid()) {
                    return $this->fail($file->getErrorString());
                }

                $file->move('./assets/uploads');

                $data['post_featured_image'] = $file->getName();

            }

            $model->save($data);

            return $this->respond($data);
        }

    }

    public function delete($id = null)
    {
        $model = new BlogModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            return $this->respondDeleted($data);
        } else {
            return $this->failNotFound("Item not found");
        }
    }

}
