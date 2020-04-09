<?php
declare(strict_types=1);

namespace App\Controller;

use App\DataTransformer\CategoryDataTransformer;
use App\DTO\BaseDto;
use App\DTO\CategoryDto;
use App\Repository\CategoryRepository;
use App\Service\CategoryManager;
use App\Support\Error\ValidationException;
use Ramsey\Uuid\Uuid;
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
            
            $id = $this->categoryManager->createCategory($dto);
            
            return $this->response(Response::HTTP_CREATED, 'Category created', ['id' => $id]);
        } catch (ValidationException $e) {
            return $this->response(Response::HTTP_BAD_REQUEST, $e->getMessage(), $e->getErrors());
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
            return $this->response(Response::HTTP_NOT_FOUND, 'Category not found');
        }
        
        $this->categoryManager->deleteCategory($id);
            
        return $this->response(Response::HTTP_OK, 'Category deleted');
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
        
        return $this->response(
            Response::HTTP_OK, 
            'Categories found', 
            $outputList, 
            ['count' => count($outputList)]
        );
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
            return $this->response(Response::HTTP_NOT_FOUND, 'Category not found');
        }
        
        $output = $this->categoryDataTransformer->transformOutput($category, [BaseDto::GROUP_SINGLE]);

        return $this->response(Response::HTTP_OK, 'Category found', $output);
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
                return $this->response(Response::HTTP_NOT_FOUND, 'Category not found');
            }

            /** @var CategoryDto */
            $dto = $this->categoryDataTransformer->transformInput($request);
            $dto->id = Uuid::fromString($category['id']);
            
            $dto->validate([BaseDto::GROUP_UPDATE]);
            
            $this->categoryManager->updateCategory($dto);

            return $this->response(Response::HTTP_OK, 'Category updated');
        } catch (ValidationException $e) {
            return $this->response(Response::HTTP_BAD_REQUEST, $e->getMessage(), $e->getErrors());
        }
    }
}

