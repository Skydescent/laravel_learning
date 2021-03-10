<?php


namespace App\Service;


use App\News;

class NewsService
{
    /**
     * @var
     */
    private $news;

    /**
     * @param News $news
     * @return $this
     */
    public function setNews(News $news)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * @param $attributes
     * @return $this
     */
    public function storeOrUpdate($attributes)
    {
        $attributes['published'] = $attributes['published'] ?? 0;

        if (isset($attributes['title'])) {
            $this->news->slug = $attributes['title'];
        }

        if ($this->news->id) {
            $this->news->update($attributes);
        } else {
            $this->news->fill($attributes);
            $this->news->save();
        }

        return $this;
    }
}