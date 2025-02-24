<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Actions\Auth\LoginAction;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\Validation\Validator as IlluminateValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Mauricius\LaravelHtmx\Http\HtmxResponse;
use Mauricius\LaravelHtmx\Http\HtmxResponseClientRedirect;

final class LoginController
{
    public const VIEW = 'resources.auth.login';

    public const REDIRECT_TO = 'dashboard';

    public function __invoke(
        LoginRequest $request,
        LoginAction $action
    ): HtmxResponse|HtmxResponseClientRedirect {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return $this->sendHtmxValidationResponse($request, $validator);
        }

        $ok = $action->execute($validator->getData());

        // When user enters incorrect email or password
        if (! $ok) {
            $validator->errors()->add('email', __('auth.failed'));

            return $this->sendHtmxValidationResponse($request, $validator);
        }

        return new HtmxResponseClientRedirect(route(self::REDIRECT_TO));
    }

    // TODO: DO better flash input save
    private function sendHtmxValidationResponse(
        LoginRequest $request,
        IlluminateValidator $validator
    ): HtmxResponse {
        // On failed input don't store password value in input field
        session()->flash('email', $request->email);

        // Swap form with errors using HTMX
        $response = (new HtmxResponse())
            ->addRenderedFragment(
                View::renderFragment(self::VIEW, 'form', ['errors' => $validator->errors()])
            )
            ->reswap('outerHTML')
            ->retarget('form');

        // Forget previous input values
        session()->forget('email');

        return $response;
    }
}
