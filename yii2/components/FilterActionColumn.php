<?php

namespace app\components;

use yii\grid\ActionColumn;
use yii\bootstrap\Html;

class FilterActionColumn extends ActionColumn
{
    const LINK_INDEX = 'LINK_TO_INDEX_ACTION_WITH_NO_PARAMS';
    const LINK_INDEX_CONFIRM = 'LINK_TO_INDEX_ACTION_WITH_NO_PARAMS_WITH_CONFIRM';

    /**
     * May be:
     * - html ready representation of content
     * - a closure to generate the content
     * - a predefined content generation keyword (i.e. LINK_INDEX)
     */
    public $filter;

    /**
     * Provide mechanism to render content in filter cell
     */
    protected function renderFilterCellContent()
    {
        if (empty($this->filter)) {
            return null;
        } elseif ($this->filter === self::LINK_INDEX) {
            return Html::a(Html::icon('repeat'), ['index'], ['class' => 'btn text-warning']);
        } elseif ($this->filter === self::LINK_INDEX_CONFIRM) {
            return Html::a(Html::icon('repeat'), ['index'], ['class' => 'btn text-warning', 'data-confirm' => \Yii::t('app', 'Go back to index?')]);
        } elseif ($this->filter instanceof \Closure) {
            return call_user_func($this->filter);
        } else {
            return $this->filter;
        }
    }
}
