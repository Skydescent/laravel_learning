<?php


namespace App\Service;


use App\News;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NewsService implements RepositoryServiceable
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

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        if ($this->news->id) {
            $this->news->update($attributes);
        } else {
            $this->news->fill($attributes);
            $this->news->save();
        }

        $this->news->syncTags($tags);

        return $this;
    }

    /**
     * @param FormRequest|Request $request
     */
    public function store(FormRequest|Request $request)
    {
        // TODO: Implement store() method.
    }

    /**
     * @param FormRequest|Request $request
     * @param $model
     */
    public function update(FormRequest|Request $request, $model)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        // TODO: Implement destroy() method.
    }
}