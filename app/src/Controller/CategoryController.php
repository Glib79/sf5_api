<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="category_api")
 */
class CategoryController extends BaseApiController
{
    /**
     * @var CategoryManager
     */
    private $categoryManager;
    
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CategoryController constructor
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CategoryManager $categoryManager, CategoryRepository $categoryRepository)
    {
        $this->categoryManager = $categoryManager;
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * Add Category
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @Route("/category", name="category_add", methods={"POST"})
     */
    public function addCategory(Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);
            $name = $request->get('name');
            
            if (empty($name)) {
                throw new Exception();
            }

            $this->categoryManager->createCategory($name);
            
            return $this->responseWithSuccess("Category created", Response::HTTP_CREATED);
        } catch (Exception $e) {
            return $this->responseWithError("Data no valid", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    
    /**
     * Delete Category
     * @param int $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_delete", methods={"DELETE"})
     */
    public function deleteCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        
        if (!$category) {
            return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
        }
        
        $this->categoryManager->deleteCategory($category);
        
        return $this->responseWithSuccess("Category deleted", Response::HTTP_OK);
    }
    
    /**
     * Categories List
     * @return JsonResponse
     * @Route("/categories", name="categories", methods={"GET"})
     */
    public function getCategories(): JsonResponse
    {
        $data = $this->categoryRepository->findAll();
        
        return $this->responseWithData($data);
    }

    /**
     * Get single category
     * @param $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_get", methods={"GET"})
     */
    public function getCategory($id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
        }

        return $this->responseWithData($category->jsonSerialize());
    }

    /**
     * Update Category
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_put", methods={"PUT"})
     */
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
            }

            $request = $this->transformJsonBody($request);
            $name = $request->get('name');

            if (empty($name)) {
                throw new Exception();
            }

            $this->categoryManager->updateCategory($category, $name);

            return $this->responseWithSuccess("Category updated", Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->responseWithError("Data no valid", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}

