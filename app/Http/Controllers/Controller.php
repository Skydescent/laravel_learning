<?php

namespace App\Http\Controllers;

use App\Service\AdminServiceable;
use App\Service\Serviceable;
use App\Service\TagsInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function prepareAttributes(Request|FormRequest $request): array
    {
        return $request->all();
    }

    /**
     * @throws AuthorizationException
     */
    protected function authorizeIfNeeded(?string $policyAuthMethod, $model)
    {
        if ($policyAuthMethod) {
            $this->authorize($policyAuthMethod, $model);
        }
    }

    protected function getTagService(): TagsInterface
    {
        return app()->get(TagsInterface::class);
    }

}
