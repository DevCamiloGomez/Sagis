<?php

namespace App\Repositories;

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

use App\Repositories\AbstractRepository;
use App\Models\Post;

class PostRepository extends AbstractRepository
{
    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $params
     * @param int $postCategoryId
     * 
     * @return $query
     */
    public function search(array $params = [], int $postCategoryId = null, string $with = null)
    {
        $query = $this->model
            ->select();

        if (isset($postCategoryId) && $postCategoryId) {
            $query->where('post_category_id', $postCategoryId);
        }

        if (isset($with)) {
            $query->with($with);
        }

        if (isset($params['title']) && $params['title']) {
            $query->where('title', 'like', '%' . $params['title'] . '%');
        }
        if (isset($params['created_at_from']) && $params['created_at_from']) {
            $query->where('date', '>=', $params['created_at_from']);
        }
        if (isset($params['created_at_to']) && $params['created_at_to']) {
            $query->where('date', '<=', $params['created_at_to']);
        }

        return $query;
    }

    public function getTotalPosts()
    {

        $query = $this->model
            ->select('posts.id')
            ->get();

        return $query;
    }


    public function getPosts()
    {

        $query = $this->model
            ->select('*')
            ->get();

        return $query;
    }



    public function getPotsNoticias()
    {

        $table = $this->model->getTable();
        $joinPostCategories = "post_categories";
        $query = $this->model
            ->select("{$table}.id")
            ->join("{$joinPostCategories}", "{$table}.post_category_id", "{$joinPostCategories}.id")
            ->where('post_categories.name', 'like', '%Noticias%');

        // return $this->all(['id'])->where('post_category_id', 1);

        return $query;
    }

    /**
     * @param array $params
     * 
     * @return array
     */
    public function transformParameters(array $params)
    {
        /*  if (empty($params)) {
            $params = set_sub_month_date_filter($params, 'created_at_from', 1);
        }

        # Clean empty keys from array
        $params = array_filter($params); */

        return $params;
    }

    /**
     * @param $query
     * @param array $params
     * @param int $pageNumber
     * @param int $total
     * 
     * @return LengthAwarePaginator $items
     */
    public function customPagination($query, $params, $pageNumber, $total)
    {
        try {
            $perPage = 12;
            $pageName = 'page';
            $offset = ($pageNumber -  1) * $perPage;

            $page = Paginator::resolveCurrentPage($pageName);

            $query->skip($offset)
                ->take($perPage);

            if (isset($params['order_by'])) {
                switch ($params['order_by']) {
                    case '1':
                        $query->orderBy('title', 'ASC');
                        break;
                    case '2':
                        $query->orderBy('title', 'DESC');
                        break;
                    case '3':
                        $query->orderBy('date', 'DESC');
                        break;
                    case '4':
                        $query->orderBy('date', 'ASC');
                        break;
                    default:
                        $query->orderBy('date', 'DESC');
                }
            } else {
                $query->orderBy('date', 'DESC');
            }

            $items = $query->get();

            $items = new LengthAwarePaginator($items, $total, $perPage, $page, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName
            ]);

            $items->appends($params);

            return $items;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @var \App\Models\PostCategory $postCategory
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allWithImages($postCategory)
    {
        return $this->model->with('images')->where('post_category_id', $postCategory->id);
    }

    /**
     * Get the latest post from a specific category
     * 
     * @param int $categoryId
     * @return \App\Models\Post|null
     */
    public function getLatestPostByCategory($categoryId)
    {
        return $this->model
            ->where('post_category_id', $categoryId)
            ->orderBy('date', 'desc')
            ->first();
    }
}
