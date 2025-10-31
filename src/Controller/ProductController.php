<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAvailable();
        
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/category/{category}', name: 'app_product_category')]
    public function category(string $category, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findByCategory($category);
        
        return $this->render('product/category.html.twig', [
            'products' => $products,
            'category' => $category,
        ]);
    }

    #[Route('/search', name: 'app_product_search')]
    public function search(Request $request, ProductRepository $productRepository): Response
    {
        $query = $request->query->get('q', '');
        $products = [];
        
        if ($query) {
            // Nettoie et valide la requête de recherche
            $query = strip_tags($query);
            $query = htmlspecialchars($query, ENT_QUOTES, 'UTF-8');
            $query = substr($query, 0, 100); // Limite la longueur
            
            if (strlen(trim($query)) > 0) {
                $products = $productRepository->searchProducts($query);
            }
        }
        
        return $this->render('product/search.html.twig', [
            'products' => $products,
            'query' => $query,
        ]);
    }

    #[Route('/{id}', name: 'app_product_show', requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }
}
