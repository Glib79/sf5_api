<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransformer\CategoryDataTransformer;
use App\DTO\BaseDto;
use App\DTO\CategoryDto;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use App\Support\ValidationException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

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
     * Add category
     * @param Request $request
     * @return JsonResponse
     * @Route("/category", name="category_add", methods={"POST"})
     */
    public function addCategory(Request $request): JsonResponse
    {
        try {      
            /** @var CategoryDto */
            $dto = $this->categoryDataTransformer->transformInput($request);
            
            $dto->validate([BaseDto::GROUP_CREATE]);
            
            $this->categoryManager->createCategory($dto);
            
            return $this->responseWithSuccess("Category created", Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->responseWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return $this->responseWithError("Category has not been created!", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Delete category
     * @param string $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_delete", methods={"DELETE"})
     */
    public function deleteCategory(string $id): JsonResponse
    {
        $category = $this->categoryRepository->getCategoryById($id);
        
        if (!$category) {
            return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
        }
        
        try {
            $this->categoryManager->deleteCategory($id);
            
            return $this->responseWithSuccess("Category deleted", Response::HTTP_OK);
        } catch (Throwable $e) {
            return $this->responseWithError("Category has not been deleted!", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Categories List
     * @return JsonResponse
     * @Route("/categories", name="categories", methods={"GET"})
     */
    public function getCategories(): JsonResponse
    {
        $data = $this->categoryRepository->findCategories();
        
        $outputList = $this->categoryDataTransformer->transformList($data, [BaseDto::GROUP_LIST]);
        
        return $this->responseWithData($outputList);
    }

    /**
     * Get single category
     * @param string $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_get", methods={"GET"})
     */
    public function getCategory(string $id): JsonResponse
    {
        $category = $this->categoryRepository->getCategoryById($id);

        if (!$category) {
            return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
        }
        
        $output = $this->categoryDataTransformer->transformOutput($category, [BaseDto::GROUP_SINGLE]);

        return $this->responseWithData($output);
    }

    /**
     * Update category
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     * @Route("/category/{id}", name="category_put", methods={"PUT"})
     */
    public function updateCategory(Request $request, string $id): JsonResponse
    {
        try {
            $category = $this->categoryRepository->getCategoryById($id);

            if (!$category) {
                return $this->responseWithError("Category not found", Response::HTTP_NOT_FOUND);
            }

            /** @var CategoryDto */
            $dto = $this->categoryDataTransformer->transformInput($request);
            $dto->id = Uuid::fromString($category['id']);
            
            $dto->validate([BaseDto::GROUP_UPDATE]);
            
            $this->categoryManager->updateCategory($dto);

            return $this->responseWithSuccess("Category updated", Response::HTTP_OK);
        } catch (ValidationException $e) {
            return $this->responseWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        } catch (Throwable $e) {
            return $this->responseWithError("Category has not been updated!", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

