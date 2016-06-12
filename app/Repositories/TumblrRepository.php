<?php namespace App\Repositories;

use Tumblr\API\Client;
use Illuminate\Support\Collection;
use App\Contracts\Repositories\TumblrRepositoryContract;

class TumblrRepository implements TumblrRepositoryContract
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Tumblr constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        // This will already have the consumer/secret key per the AppServiceProvider
        $this->client = $client;
    }

    /**
     * Get the blog posts
     *
     * @param array $options
     * @return array|mixed
     */
    public function getPosts($options = [])
    {
        // Get the posts
        $posts = $this->client->getBlogPosts($options['account'], [
            'limit' => isset($options['limit']) ? $options['limit'] : 5,
        ]);

        // Return trimmed the posts
        return $this->trimPosts(collect($posts->posts));
    }

    /**
     * Trim the posts data down to only what we need so it is consistent on the view
     *
     * @codeCoverageIgnore
     * @param Collection $posts
     * @return array|mixed
     */
    private function trimPosts(Collection $posts)
    {
        return $posts->map(function ($post) {
            // Since Tumblr does not have a consistent data type for "title", make one
            if(isset($post->text)) {
                $title = $post->text;
            }elseif(isset($post->title)) {
                $title = $post->title;
            }else{
                $title = '';
            }

            // Since Tumblr does not have a consistent data type for description, make one
            if(isset($post->caption)) {
                $description = $post->caption;
            }elseif(isset($post->source)) {
                $description = $post->source;
            }elseif(isset($post->body)) {
                $description = $post->body;
            }else{
                $description = '';
            }

            return [
                'image_url' => isset($post->photos[0]->original_size->url) ? $post->photos[0]->original_size->url : '',
                'url' => isset($post->post_url) ? $post->post_url : '',
                'title' => $title,
                'description' => nl2br($description),
            ];
        });
    }
}
