<?php

namespace App\Http\Controllers;

use App\Service\StepsInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class StepsController extends Controller
{
    /**
     * @var StepsInterface
     */
    protected StepsInterface $stepsService;

    /**
     * @param StepsInterface $stepsService
     */
    public function __construct(StepsInterface $stepsService)
    {
        $this->stepsService = $stepsService;
    }

    /**
     * @param Request|FormRequest $request
     * @return array
     */
    protected function prepareAttributes(Request|FormRequest $request) : array
    {
        if ($request->get('description') !== null) {
            $request->validate([
                'description' => 'required|min:5'
            ]);
        }
        $attributes = $request->all();
        $attributes['completed'] = isset($attributes['completed']) && $attributes['completed'] === 'on';

        return $attributes;
    }
}