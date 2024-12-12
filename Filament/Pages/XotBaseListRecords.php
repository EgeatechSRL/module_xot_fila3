<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Pages;

use Filament\Resources\Pages\ListRecords as FilamentListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Actions\Header\ExportXlsAction;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\TransTrait;
use Webmozart\Assert\Assert;

/**
 * Undocumented class.
 *
 * @property ?string $model
 */
abstract class XotBaseListRecords extends FilamentListRecords
{
    // use TransTrait; //gia' dentro HasXotTable
    use HasXotTable;

    /**
     * Summary of getResource.
     *
     * @return class-string
     */
    public static function getResource(): string
    {
        $resource = Str::of(static::class)->before('\Pages\\')->toString();
        Assert::classExists($resource);

        return $resource;
    }

    /*
    public TableLayoutEnum $layoutView = TableLayoutEnum::LIST;


    protected function getHeaderActions(): array
    {

        $anno=Arr::get($this->tableFilters,'anno.value');
        return [
            Actions\CreateAction::make(),
            app(CopyFromLastYearButton::class)->execute(Assenze::class,'anno',$anno),
        ];

        return [
            // app(ExportButton::class)->execute(),
            // app(ImportButton::class)->execute(),
            ExportXlsAction::make(),
        ];
    }
    */

    /*protected function paginateTableQuery(Builder $query): Paginator
    {
        // @phpstan-ignore method.notFound,return.type
        return $query->fastPaginate(('all' === $this->getTableRecordsPerPage()) ? $query->count() : $this->getTableRecordsPerPage());
    }*/
}
