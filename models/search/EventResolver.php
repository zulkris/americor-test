<?php
declare(strict_types=1);

namespace app\models\search;

use app\models\search\HistorySearchEvents\RenderableEvent;
use Closure;

class EventResolver
{
    private $events;

    /**
     * @param RenderableEvent[] $renderableEvents
     * @return self
     */
    public static function create(array $renderableEvents): self
    {
        $me = new self();
        $me->events = $renderableEvents;

        return $me;
    }

    public function createRenderEventFunc(HistorySearch $model): Closure
    {
        return function($model) {
            /** @var RenderableEvent $event */
            foreach ($this->events as $event) {
                if ($event->isApplicable($model)) {
                    return $event->createView($model);
                }
            }

            throw new UnapplicableEventException('please create RenderableEvent for model');
        };
    }

    private function __construct()
    {
    }
}