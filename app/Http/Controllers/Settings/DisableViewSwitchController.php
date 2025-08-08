<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

final class DisableViewSwitchController
{
    public function __invoke()
    {
        $oppositeState = ! preferences()->get('disable_view_switch');

        preferences()->set('disable_view_switch', $oppositeState);

        return htmx()
            ->toast(type: 'success', text: $this->getMessage($oppositeState))
            ->refresh()
            ->response();
    }

    private function getMessage(bool $state): string
    {
        return $state
               ? 'Global view switch disabled!'
               : 'Global view switch enabled!';
    }
}
