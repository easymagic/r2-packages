<?php

namespace R2Packages\Framework\Ecommerce\Product;

use R2Packages\Framework\Request;
use R2Packages\Framework\BaseUser\UserIdService;

class ProductController
{
    private ProductService $productService;
    private Request $request;
    private MyProductRepository $myProductRepository;
    private ProductIdService $productIdService;
    private UserIdService $userIdService;

    public function __construct(
        ProductService $productService,
        Request $request,
        MyProductRepository $myProductRepository,
        ProductIdService $productIdService,
        UserIdService $userIdService,
    ) {
        $this->productService = $productService;
        $this->request = $request;
        $this->myProductRepository = $myProductRepository;
        $this->productIdService = $productIdService;
        $this->userIdService = $userIdService;
    }

    public function index()
    {
        $products = $this->myProductRepository->fetchAll();
        jsonResponse([
            'message' => 'Products fetched successfully',
            'data' => $products,
            "success" => true
        ]);
    }

    public function create()
    {
        $user = $this->userIdService->getUser();
        $product = $this->productService->create($this->request, $user);
        jsonResponse([
            'message' => 'Product created successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function update()
    {
        $product = $this->productService->update(
            $this->request,
            $this->productIdService->getProduct(),
            $this->userIdService->getUser()
        );
        jsonResponse([
            'message' => 'Product updated successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function get()
    {
        $product = $this->productIdService->getProduct();
        jsonResponse([
            'message' => 'Product fetched successfully',
            'data' => $product,
            "success" => true
        ]);
    }

    public function delete()
    {
        $product = $this->productIdService->getProduct();
        $this->productService->delete(
            $product,
            $this->userIdService->getUser()
        );
        jsonResponse([
            'message' => 'Product deleted successfully',
            "success" => true
        ]);
    }
}
