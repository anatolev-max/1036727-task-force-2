<?php

namespace app\services;

use app\models\Review;
use app\models\forms\CompleteForm;

class ReviewService
{
    /**
     * @param CompleteForm $model
     * @return void
     */
    public function create(CompleteForm $model): void
    {
        $review = new Review();
        $review->attributes = $model->attributes;

        $review->save();
    }
}
