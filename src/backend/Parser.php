<?php

namespace App\Parsing;

use GuzzleHttp\Client;
use App\Parsing\DOM\Document;
use App\Parsing\DOM\Element;

class Parser
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $userAgent
     */
    public function __construct($userAgent)
    {
        $this->client = new Client([
            'headers' => [
                'User-Agent' => $userAgent,
            ],
        ]);
    }

    /**
     * @param Document $document
     * @return array
     */
    protected function parseGroupAttributes(Document $document)
    {
        $attributes = [
            'id' => null,
            'url' => null,
            'image' => null,
            'name' => null,
            'description' => null,
        ];

        if ($element = $document->querySelector('._5i-y > [href^="/groups/"]')) {
            if (preg_match('/^\/groups\/(\d+)/', $element->href, $matches)) {
                if ($matches[1]) {
                    $attributes['id'] = $matches[1];
                    $attributes['url'] = 'https://facebook.com/'.$attributes['id'];
                }
            }
        }

        if ($element = $document->querySelector('._3m1l > .img')) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
                if ($matches[1]) {
                    $attributes['image'] = $this->decodeResourceUrl($matches[1]);
                }
            }
        }

        if ($element = $document->querySelector('._de1')) {
            $attributes['name'] = trim(utf8_decode($element->textContent));
        }

        if (($count = count($elements = $document->querySelectorAll('._4g34._5b6q._5b6p'))) > 0) {
            $attributes['description'] = trim(utf8_decode($elements[$count - 1]->textContent));
        }

        return $attributes;
    }

    /**
     * @param Element $document
     * @param string $groupId
     *
     * @return array
     */
    public function parsePostAttributes(Element $postElement, $groupId)
    {
        $attributes = [
            'id' => null,
            'group_id' => $groupId,
            'url' => null,
            'user_id' => null,
            'user_url' => null,
            'user_image' => null,
            'user_name' => null,
            'message' => null,
            'images' => [],
            'videos' => [],
            'likes' => 0,
            'comments' => 0,
            'published_at' => time(),
        ];

        if (preg_match('/top_level_post_id\.(\d+)/', $postElement->{'data-store'}, $matches)) {
            if ($matches[1]) {
                $attributes['id'] = $groupId.'_'.$matches[1];
            }
        }

        if ($element = $postElement->querySelector('.story_body_container > header h3 [href^="/story.php?"]')) {
            $attributes['url'] = 'https://facebook.com'.$element->href;
        } elseif ($element = $postElement->querySelector('.story_body_container  [aria-label="Открыть новость"]')) {
            $attributes['url'] = 'https://facebook.com'.$element->href;
        }

        if (preg_match('/\\\"actor_id\\\":(\d+)/', $postElement->{'data-store'}, $matches)) {
            if ($matches[1]) {
                $attributes['user_id'] = $matches[1];
            }
        }

        if ($element = $postElement->querySelector('.story_body_container > header h3 a')) {
            if ($element->href) {
                $attributes['user_url'] = 'https://facebook.com'.$element->href;
            }

            $attributes['user_name'] = utf8_decode($element->textContent);
        }

        if ($element = $postElement->querySelector('.story_body_container > header .profpic')) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
                if ($matches[1]) {
                    $attributes['user_image'] = $this->decodeResourceUrl($matches[1]);
                }
            }
        }

        $message = '';

        if ($element = $postElement->querySelector('.story_body_container > header ~ * [role="presentation"]')) {
            $message = trim(utf8_decode($element->textContent));
        } elseif ($element = $postElement->querySelector('.story_body_container > header ~ *')) {
            $message = trim(utf8_decode($element->textContent));

            if ($element = $postElement->querySelector('.story_body_container .story_body_container > header ~ *')) {
                $message .= ' '.trim(utf8_decode($element->textContent));
            }
        } elseif ($element = $postElement->querySelector('.story_body_container .story_body_container > header ~ *')) {
            $message = trim(utf8_decode($element->textContent));
        }

        if ($message) {
            $attributes['message'] = $message;
        }

        foreach ($postElement->querySelectorAll('.story_body_container > header ~ * .img') as $element) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $element->style, $matches)) {
                if ($matches[1]) {
                    $attributes['images'][] = $this->decodeResourceUrl($matches[1]);
                }
            }
        }

        foreach ($postElement->querySelectorAll('.story_body_container > header ~ * [data-store*="videoID"]') as $element) {
            if (preg_match('/"src":"(.+?)"/', $element->{'data-store'}, $matches)) {
                if ($matches[1]) {
                    $attributes['videos'][] = str_replace("\/", "/", $matches[1]);
                }
            }
        }

        if ($element = $postElement->querySelector('footer .like_def')) {
            $attributes['likes'] = (int) preg_replace(
                '/\D/', '', utf8_decode($element->textContent)
            );
        }

        if ($element = $postElement->querySelector('footer .cmt_def')) {
            $attributes['comments'] = (int) preg_replace(
                '/\D/', '', utf8_decode($element->textContent)
            );
        }

        if (preg_match('/\\\"publish_time\\\":(\d+)/', $postElement->{'data-store'}, $matches)) {
            if ($matches[1]) {
                $attributes['published_at'] = (int) $matches[1];
            }
        }

        return $attributes;
    }

    /**
     * @param string $url
     * @return string
     */
    protected function decodeResourceUrl($url)
    {
        return preg_replace_callback(
            '/\\\([a-zA-Z0-9]{2}) /', [$this, 'hexToChar'], $url
        );
    }

    /**
     * @param array $matches
     * @return string
     */
    protected function hexToChar($matches)
    {
        return chr(hexdec($matches[1]));
    }
}
