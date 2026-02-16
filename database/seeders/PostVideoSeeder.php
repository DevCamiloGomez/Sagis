<?php

namespace Database\Seeders;

use App\Repositories\PostCategoryRepository;
use App\Repositories\PostRepository;
use App\Repositories\PostVideoRepository;
use Exception;
use Illuminate\Database\Seeder;

class PostVideoSeeder extends Seeder
{
    /** @var PostVideoRepository */
    protected $postVideoRepository;

    /** @var PostRepository */
    protected $postRepository;

    /** @var PostCategoryRepository */
    protected $postCategoryRepository;

    /** @var array */
    protected $videosYT;

    public function __construct(
        PostVideoRepository $postVideoRepository,
        PostRepository $postRepository,
        PostCategoryRepository $postCategoryRepository
    ) {
        $this->postVideoRepository = $postVideoRepository;
        $this->postRepository = $postRepository;
        $this->postCategoryRepository = $postCategoryRepository;
        $this->videosYT = array('MfE1tnMG6fE', 'NuGBzmHlINQ', 'CdwK41cRCTw', 'RbKEYDtkAJI', 'sukS7QOBpK0');
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postVideo = $this->postCategoryRepository->getByAttribute('name', 'Vídeos');

        $posts = $this->postRepository->all()->where('post_category_id', $postVideo->id);

        $this->createPostAssets($postVideo, $posts);
    }

    /**
     * @param \App\Models\PostCategory $postCategory
     * @param \Illuminate\Database\Eloquent\Collection $posts
     * 
     * @return void
     */
    protected function createPostAssets($postCategory, $posts)
    {
        try {
            $randomNumber = generateRandomPostAssets(3, 8);

            $posts->map(function ($post) use ($randomNumber) {
                $randomVideo = rand(0, count($this->videosYT) - 1);

                $this->postVideoRepository->create([
                    'post_id' => $post->id,
                    'asset_url' => $this->videosYT[$randomVideo],
                    'is_header' => true
                ]);

                /*  */
              /*   do {
                    $randomVideo = rand(0, count($this->videosYT) - 1);
                    $this->postVideoRepository->create([
                        'post_id' => $post->id,
                        'asset_url' => $this->videosYT[$randomVideo],
                    ]);

                    $randomNumber--;
                } while ($randomNumber > 0); */
                /*  */
            });
        } catch (Exception $th) {
            print($th->getMessage());
        }
    }
}
