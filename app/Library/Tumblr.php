<?php namespace App\Library;

use Tumblr\API\Client;

/*
 * @property Client client
 */
class Tumblr
{
    /**
     * @var Client
     */
    private $client;

    /**
     * Tumblr constructor.
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
     * @param null $account
     * @return array|mixed
     */
    public function getPosts($options = [])
    {
        // Get the posts
        $posts = $this->client->getBlogPosts($options['account'], [
            'limit' => isset($options['limit']) ? $options['limit'] : 5,
        ]);

        // Trim the posts
        $posts = $this->trimPosts($posts);

        return $posts;
    }

    /**
     * Trim the posts data down to only what we need so it is consistent on the view
     *
     * @param array $posts
     */
    public function trimPosts($posts = [])
    {
        // Build the blog info
        $trimmed['blog']['title'] = isset($posts->blog->title) ? $posts->blog->title : '';
        $trimmed['blog']['url'] = isset($posts->blog->url) ? $posts->blog->url : '';

        // Build the posts info
        foreach($posts->posts as $key => $post) {
            $image_url = isset($post->photos[0]->original_size->url) ? $post->photos[0]->original_size->url : '';
            $url = isset($post->post_url) ? $post->post_url : '';

            if(isset($post->text)) {
                $title = $post->text;
            }elseif(isset($post->title)) {
                $title = $post->title;
            }else{
                $title = '';
            }

            if(isset($post->caption)) {
                $description = $post->caption;
            }elseif(isset($post->source)) {
                $description = $post->source;
            }elseif(isset($post->body)) {
                $description = $post->body;
            }else{
                $description = '';
            }

            $trimmed['posts'][] = [
                'image_url' => $image_url,
                'url' => $url,
                'title' => $title,
                'description' => nl2br($description),
            ];
        }

        return $trimmed;
    }
}