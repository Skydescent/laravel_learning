<?php

namespace App\Http\Controllers;

use App\Service\AdminServiceable;
use App\Service\TagsInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class NewsController extends Controller
{

    /**
     * @var AdminServiceable
     */
    protected AdminServiceable $newsService;

    /**
     * @param AdminServiceable $newsService
     */
    public function __construct(AdminServiceable $newsService)
    {
        $this->newsService = $newsService;
    }


    /**
     * @param FormRequest|Request $request
     * @return array
     */
    protected function prepareAttributes(FormRequest|Request $request): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;

        return $attributes;
    }
}