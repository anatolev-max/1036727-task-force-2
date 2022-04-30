<?php

namespace app\services;

use app\models\Category;

class CategoryService
{
    /**
     * @return Category[]
     */
    public function findAll(): array
    {
        return Category::find()->all();
    }

    /**
     * @param string $innerName
     * @return ?int
     */
    public function getId(string $innerName): ?int
    {
        return Category::findOne(['inner_name' => $innerName])?->id;
    }
}
