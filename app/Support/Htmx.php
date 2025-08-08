<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Response;
use Illuminate\Support\Traits\Macroable;

final class Htmx
{
    use Macroable;

    /**
     * The headers applied to response.
     */
    private array $headers = [];

    /**
     * Check if the request is an HTMX request.
     */
    public function isRequest(): bool
    {
        return request()->hasHeader('HX-Request');
    }

    /**
     * Set the HX-Retarget header.
     */
    public function target(string $target): self
    {
        $this->headers['HX-Retarget'] = $target;

        return $this;
    }

    /**
     * Set the HX-Reswap header.
     */
    public function swap(string $type): self
    {
        $this->headers['HX-Reswap'] = $type;

        return $this;
    }

    /**
     * Set the HX-Trigger header.
     */
    public function trigger(string|array $events): self
    {
        $this->headers['HX-Trigger'] = is_array($events) ? json_encode($events) : $events;

        return $this;
    }

    /**
     * Set the HX-Trigger-After-Swap header.
     */
    public function triggerAfterSwap(string|array $events): self
    {
        $this->headers['HX-Trigger-After-Swap'] = is_array($events) ? json_encode($events) : $events;

        return $this;
    }

    /**
     * Set the HX-Trigger-After-Settle header.
     */
    public function triggerAfterSettle(string|array $events): self
    {
        $this->headers['HX-Trigger-After-Settle'] = is_array($events) ? json_encode($events) : $events;

        return $this;
    }

    /**
     * Set the HX-Push-Url header.
     */
    public function pushUrl(string|bool $url = true): self
    {
        $this->headers['HX-Push-Url'] = is_bool($url) ? ($url ? 'true' : 'false') : $url;

        return $this;
    }

    /**
     * Set the HX-Redirect header.
     */
    public function redirect(string $url): self
    {
        $this->headers['HX-Redirect'] = $url;

        return $this;
    }

    /**
     * Set the HX-Refresh header.
     */
    public function refresh(bool $refresh = true): self
    {
        $this->headers['HX-Refresh'] = $refresh ? 'true' : 'false';

        return $this;
    }

    /**
     * Set the HX-Replace-Url header.
     */
    public function replaceUrl(?string $url = null): self
    {
        $this->headers['HX-Replace-Url'] = $url ?? 'true';

        return $this;
    }

    public function toast(
        string $type,
        string $text,
        ?string $altText = '',
    ) {
        $data = [
            'toast' => [
                'type' => $type,
                'text' => $text,
                'altText' => $altText,
            ],
        ];

        $headerName = 'HX-Trigger';

        // if trigger is present in headers array
        if (isset($this->headers[$headerName])) {
            $existing = $this->headers[$headerName];

            if (is_string($existing)) {
                $existing = [$existing => null];
            } else {
                $existing = json_decode($this->headers[$headerName], true);
            }

            $existing = array_merge($existing, $data);

            $this->headers[$headerName] = json_encode($existing);

            return $this;
        }

        $this->headers[$headerName] = json_encode($data);

        return $this;
    }

    /**
     * Apply headers to response.
     */
    public function apply(Response $response): Response
    {
        // convert HX-Trigger to HX-Trigger-After-Swap if retarget or reswap is present
        if ((isset($this->headers['HX-Retarget']) || isset($this->headers['HX-Reswap']))
            && isset($this->headers['HX-Trigger'])) {

            $this->headers['HX-Trigger-After-Swap'] = $this->headers['HX-Trigger'];
            unset($this->headers['HX-Trigger']);
        }

        // convert toast to toast_after_redirect if redirect or refresh is present
        if ((isset($this->headers['HX-Redirect']) || isset($this->headers['HX-Refresh']))
            && isset($this->headers['HX-Trigger'])) {

            $triggerData = json_decode($this->headers['HX-Trigger'], true);
            if ($triggerData && isset($triggerData['toast'])) {
                $triggerData['toast_after_redirect'] = $triggerData['toast'];
                unset($triggerData['toast']);
                $this->headers['HX-Trigger'] = json_encode($triggerData);
            }
        }

        foreach ($this->headers as $name => $value) {
            $response->header($name, $value);
        }

        return $response;
    }

    /**
     * Apply all headers to view response
     */
    public function response(mixed $render = null): Response
    {
        return $this->apply(response($render));
    }
}
