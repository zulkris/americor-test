<?php
declare(strict_types=1);

namespace app\models\search\HistorySearchEvents;

use app\models\History;

interface RenderableEvent
{
    public function isApplicable(History $model): bool;

    public function createView(History $model): string;
}