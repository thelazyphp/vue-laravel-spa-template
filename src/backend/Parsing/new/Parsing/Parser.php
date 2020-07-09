<?php

namespace App\Parsing;

use App\Group;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use App\Parsing\DOM\Element;
use App\Parsing\DOM\Document;

/**
 *
 */
class Parser
{
    /**
     * @var string
     */
    protected $host = 'm.facebook.com';

    /**
     * @var string
     */
    protected $userAgent = 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1';

    /**
     * @param string|\Psr\Http\Message\UriInterface $url
     * @return \Psr\Http\Message\UriInterface
     */
    protected function replaceHost($url)
    {
        if (is_string($url)) {
            $url = new Uri($url);
        }

        return $url->withHost($this->host);
    }

    public function start()
    {
        error_reporting(E_ERROR);
        set_time_limit(0);

        $start = microtime(true);

        $posts = [];

        foreach (Group::all() as $group) {
            $html = (string) $this->makeClient()->get($this->replaceHost($group->url))->getBody();

            $doc = new Document();
            $doc->loadHTML($html);

            foreach ($doc->querySelectorAll('#m_group_stories_container > section > article') as $articleElem) {
                $posts[] = $this->parsePostAttrs($articleElem, $group->id);
            }
        }

        echo '<pre>';
        print_r($posts); echo '</pre>';
    }

    /**
     * @param \App\Parsing\DOM\Document $doc
     * @return array[]
     */
    protected function parseGroupAttrs(Document $doc)
    {
        $attrs = array_fill_keys(
            ['id', 'url', 'image', 'name', 'description'], null
        );

        if ($elem = $doc->querySelector('._5i-y > [href^="/groups/"]')) {
            if (preg_match('/^\/groups\/(\d+)/', $elem->getAttribute('href'), $matches) && $matches[1]) {
                $attrs['id'] = $matches[1];
            }
        }

        if ($attrs['id']) {
            $attrs['url'] = 'https://facebook.com/groups/'.$attrs[1];
        }

        if ($elem = $doc->querySelector('._3m1l > .img')) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $elem->getAttribute('style'), $matches) && $matches[1]) {
                $attrs['image'] = $this->decodeResourceUrl($matches[1]);
            }
        }

        if ($elem = $doc->querySelector('._de1')) {
            $attrs['name'] = trim(utf8_decode($elem->textContent));
        }

        if (($count = count($elems = $doc->querySelectorAll('._4g34._5b6q._5b6p'))) > 0) {
            $attrs['description'] = trim(utf8_decode($elems[$count - 1]->textContent));
        }

        return $attrs;
    }

    /**
     * @param \App\Parsing\DOM\Element $postElem
     * @param string $groupId
     *
     * @return array[]
     */
    protected function parsePostAttrs(Element $postElem, $groupId)
    {
        $attrs = [
            'id'           => null,
            'group_id'     => $groupId,
            'url'          => null,
            'user_id'      => null,
            'user_url'     => null,
            'user_image'   => null,
            'user_name'    => null,
            'message'      => null,
            'images'       => [],
            'videos'       => [],
            'likes'        => 0,
            'comments'     => 0,
            'published_at' => null,
        ];

        if (preg_match('/top_level_post_id\.(\d+)/', $postElem->getAttribute('data-store'), $matches) && $matches[1]) {
            $attrs['id'] = $groupId.'_'.$matches[1];
        }

        if ($elem = $postElem->querySelector('.story_body_container > header h3 [href^="/story.php?"]')) {
            $attrs['url'] = 'https://facebook.com'.$elem->getAttribute('href');
        } elseif ($elem = $postElem->querySelector('.story_body_container  [aria-label="Открыть новость"]')) {
            $attrs['url'] = 'https://facebook.com'.$elem->getAttribute('href');
        }

        if (preg_match('/\\\"actor_id\\\":(\d+)/', $postElem->getAttribute('data-store'), $matches) && $matches[1]) {
            $attrs['user_id'] = $matches[1];
        }

        if ($elem = $postElem->querySelector('.story_body_container > header h3 a')) {
            if ($attr = $elem->getAttribute('href')) {
                $attrs['user_url'] = 'https://facebook.com'.$attr;
            }

            $attrs['user_name'] = utf8_decode($elem->textContent);
        }

        if ($elem = $postElem->querySelector('.story_body_container > header .profpic')) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $elem->getAttribute('style'), $matches) && $matches[1]) {
                $attrs['user_image'] = $this->decodeResourceUrl($matches[1]);
            }
        }

        $message = '';

        if ($elem = $postElem->querySelector('.story_body_container > header ~ * [role="presentation"]')) {
            $message = trim(utf8_decode($elem->textContent));
        } elseif ($elem = $postElem->querySelector('.story_body_container > header ~ *')) {
            $message = trim(utf8_decode($elem->textContent));

            if ($elem = $postElem->querySelector('.story_body_container .story_body_container > header ~ *')) {
                $message .= ' '.trim(utf8_decode($elem->textContent));
            }
        } else if ($elem = $postElem->querySelector('.story_body_container .story_body_container > header ~ *')) {
            $message = trim(utf8_decode($elem->textContent));
        }

        if ($message) {
            $attrs['message'] = $message;
        }

        foreach ($postElem->querySelectorAll('.story_body_container > header ~ * .img') as $elem) {
            if (preg_match('/url\([\'"]?(.+?)[\'"]?\)/', $elem->getAttribute('style'), $matches) && $matches[1]) {
                $attrs['images'][] = $this->decodeResourceUrl($matches[1]);
            }
        }

        foreach ($postElem->querySelectorAll('.story_body_container > header ~ * [data-store*="videoID"]') as $elem) {
            if (preg_match('/"src":"(.+?)"/', $elem->getAttribute('data-store'), $matches) && $matches[1]) {
                $attrs['videos'][] = str_replace("\/", "/", $matches[1]);
            }
        }

        if ($elem = $postElem->querySelector('footer .like_def')) {
            $attrs['likes'] =  (int) preg_replace(
                '/\D/', '', utf8_decode($elem->textContent)
            );
        }

        if ($elem = $postElem->querySelector('footer .cmt_def')) {
            $attrs['comments'] =  (int) preg_replace(
                '/\D/', '', utf8_decode($elem->textContent)
            );
        }

        if (preg_match('/\\\"publish_time\\\":(\d+)/', $postElem->getAttribute('data-store'), $matches) && $matches[1]) {
            $attrs['published_at'] = (int) $matches[1];
        }

        return $attrs;
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

    /**
     * @return \GuzzleHttp\Client
     */
    protected function makeClient()
    {
        return new Client([
            'cookies' => true,
            'verify' => false,

            'headers' => [
                'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
                'user-agent' => $this->userAgent,
            ],
        ]);
    }
}
