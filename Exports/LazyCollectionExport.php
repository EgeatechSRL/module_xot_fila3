<?php

declare(strict_types=1);

namespace Modules\Xot\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\LazyCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromIterator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Modules\Xot\Actions\Collection\TransCollectionAction;

class LazyCollectionExport implements FromIterator, WithHeadings, ShouldQueue, WithMapping
{
    use Exportable;

    public array $headings;

    public ?string $transKey;
    public ?array $fields = null;

    public function __construct(public LazyCollection $collection, ?string $transKey = null, ?array $fields = null)
    {
        // $this->headings = count($headings) > 0 ? $headings : collect($collection->first())->keys()->toArray();

        $this->transKey = $transKey;
        $this->fields = $fields;
        // $this->headings = $headings->toArray();
    }

    /**
     * Undocumented function.
     *
     * @param Collection $item
     */
    public function map($item): array
    {
        $data = $item->only($this->fields);

        return $data->toArray();
        /*
        return [
            $item->id,
        ];
        */
    }

    public function getHead(): Collection
    {
        if (is_array($this->fields)) {
            return collect($this->fields);
        }

        /**
         * @var array
         */
        $head = $this->collection->first();

        return collect($head)->keys();
    }

    public function headings(): array
    {
        $headings = $this->getHead();
        $transKey = $this->transKey;
        $headings = app(TransCollectionAction::class)->execute($headings, $transKey);

        return $headings->toArray();
    }

    public function collection(): LazyCollection
    {
        return $this->collection;
    }

    /**
     * Returns an iterator for the current collection.
     */
    public function iterator(): \Iterator
    {
        /* @phpstan-ignore-next-line */
        return $this->collection->getIterator();
    }
}
