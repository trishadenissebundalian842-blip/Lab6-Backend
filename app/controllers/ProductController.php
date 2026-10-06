<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->call->database();
        $this->call->model('ProductModel');
        $this->call->library('api');
        
    }

    // GET /products
    public function index()
{
    $this->api->require_jwt();

    $products = $this->ProductModel->all();

    $this->api->respond([
        'status' => true,
        'data'   => $products
    ]);
}
    // GET /products/{id}
    public function show($id)
    {
        $this->api->require_jwt();
        $product = $this->ProductModel->find($id);

        if (!$product) {
            $this->api->respond_error('Product not found', 404);
            return;
        }

        $this->api->respond([
            'status' => true,
            'data'   => $product
        ]);
    }

    // POST /products
    public function store()
{
    $this->api->require_jwt();
    $this->api->require_method('POST');

    $input = $this->api->body();

    $data = [
        'product_name' => $input['product_name'] ?? '',
        'description'  => $input['description'] ?? '',
        'price'        => $input['price'] ?? 0,
        'quantity'     => $input['quantity'] ?? 0
    ];

    $id = $this->ProductModel->insert($data);

    $this->api->respond([
        'status'  => true,
        'message' => 'Product created successfully',
        'id'      => $id
    ], 201);
}

    // PUT /products/{id}
    public function update($id)
    {
        $this->api->require_jwt();
        $this->api->require_method('PUT');

        $product = $this->ProductModel->find($id);

        if (!$product) {
            $this->api->respond_error('Product not found', 404);
            return;
        }

        $input = $this->api->body();

        $data = [
            'product_name' => $input['product_name'] ?? $product['product_name'],
            'description'  => $input['description'] ?? $product['description'],
            'price'        => $input['price'] ?? $product['price'],
            'quantity'     => $input['quantity'] ?? $product['quantity']
        ];

        $this->ProductModel->update($id, $data);

        $this->api->respond([
            'status'  => true,
            'message' => 'Product updated successfully'
        ]);
    }

    // DELETE /products/{id}
    public function delete($id)
    {
        $this->api->require_jwt();
        $this->api->require_method('DELETE');

        $product = $this->ProductModel->find($id);

        if (!$product) {
            $this->api->respond_error('Product not found', 404);
            return;
        }

        $this->ProductModel->delete($id);

        $this->api->respond([
            'status'  => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}