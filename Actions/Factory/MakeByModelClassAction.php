<?php

declare(strict_types=1);
/**
 * @see https://github.com/TheDoctor0/laravel-factory-generator. 24 days ago
 * @see https://github.com/mpociot/laravel-test-factory-helper  on 2 Mar 2020.
 * @see https://github.com/laravel-shift/factory-generator on 10 Aug.
 * @see https://dev.to/marcosgad/make-factory-more-organized-laravel-3c19.
 * @see https://medium.com/@yohan7788/seeders-and-faker-in-laravel-6806084a0c7.
 */

namespace Modules\Xot\Actions\Factory;

use Spatie\QueueableAction\QueueableAction;

class MakeByModelClassAction {
    use QueueableAction;

    /**
     * Undocumented function.
     *
     * @param class-string $modelClass
     *
     * @return void|bool
     */
    public function execute(string $modelClass) {
        $reflectionClass = new \ReflectionClass($modelClass);

        if (! $reflectionClass->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
            return false;
        }
        if (! $reflectionClass->IsInstantiable()) {
            // ignore abstract class or interface
            return false;
        }

        $model = app($modelClass);
        $dataFromTable = app(GetPropertiesFromTableByModelAction::class)->execute($model);
        $dataFromMethods = app(GetPropertiesFromMethodsByModelAction::class)->execute($model);

        dddx([
            'dataFromTable' => $dataFromTable,
            'dataFromMethods' => $dataFromMethods,
        ]);
    }
}
