<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransformer\CategoryDataTransformer;
use App\DTO\BaseDto;
use App\DTO\CategoryDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use App\Support\ValidationException;
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
     * @var CategoryDataTransformer
     */
    private $categoryDataTransformer;

    /**
     * CategoryController constructor
     * @param CategoryDataTransformer $categoryDataTransformer
     * @param CategoryManager $categoryManager
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CategoryDataTransformer $categoryDataTransformer,
        CategoryManager $categoryManager, 
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryDataTransformer = $categoryDataTransformer;
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
            /** @var CategoryDto */
            $dto = $this->categoryDataTransformer->transformInput($request, [BaseDto::GROUP_CREATE]);
            
            $this->categoryManager->createCategory($dto);
            
            return $this->responseWithSuccess("Category created", Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->responseWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->responseWithError("Data no valid", Response::HTTP_BAD_REQUEST);
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
        /** @var Category */
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
        /** @var Category */
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
            /** @var Category */
            $category = $this->categoryRepository->find($id);

            if (!$category) {
                return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
            }

            /** @var CategoryDto */
            $dto = $this->categoryDataTransformer->transformInput($request, [BaseDto::GROUP_UPDATE]);

            $this->categoryManager->updateCategory($category, $dto);

            return $this->responseWithSuccess("Category updated", Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->responseWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->responseWithError("Data no valid", Response::HTTP_BAD_REQUEST);
        }
    }
}

