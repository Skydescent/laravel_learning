<?php

namespace App\Http\Controllers;

use App\Service\AdminServiceable;
use App\Service\TagsInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class PostsController extends Controller
{

    /**
     * @var AdminServiceable
     */
    protected AdminServiceable $postsService;

    /**
     * @var TagsInterface
     */
    protected TagsInterface $tagsService;

    /**
     * @param AdminServiceable $postsService
     */
    public function __construct(AdminServiceable $postsService)
    {
        $this->postsService = $postsService;
        $this->tagsService = $this->getTagService();
    }

    /**
     * @param FormRequest|Request $request
     * @return array
     */
    protected function prepareAttributes(FormRequest|Request $request): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :cachedUser()->id;
        return $attributes;
    }

}