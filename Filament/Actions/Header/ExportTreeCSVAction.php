<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions\Header;

use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;

class ExportTreeCSVAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();
        $this
            ->tooltip(__('xot::actions.export_xls'))
            ->icon('heroicon-o-arrow-down-tray')
            ->action(static function ($livewire, $record, $data) {

                try {
                    $tableFilters = [
                        'id' => $record->getKey(),
                    ];
                } catch (\Error $e) {
                    $tableFilters = [
                        'id' => '',
                    ];
                } 

                $templateRows = DB::table('asset_templates')
                    ->whereLike('id', $tableFilters['id'] . '%')
                    ->select(
                        'id as Identificativo',
                        'parent_id as Padre',
                        'name as Nome',
                        'asset_type_txt as Tipologia',
                        'brand as Marca',
                        'model as Modello',
                        DB::raw("CASE WHEN is_enabled = 1 THEN 'Sì' ELSE 'No' END AS 'Abilitato?'"),
                    )
                    ->orderBy('id', 'ASC')
                    ->get();

                if ($tableFilters['id'] === '') {
                    //all
                    $filename = "AssetTemplates_".date("Ymd_His").'.csv';
                } else {
                    //specific template
                    $filename = $tableFilters['id']."_".str_replace(" ", "_", $templateRows->first()->Nome).'_'.date("Ymd_His").'.csv';
                }

                return response()->streamDownload(function () use ($templateRows) {
                    $handle = fopen('php://output', 'w');

                    fputcsv($handle, array_keys((array)$templateRows[0]));

                    foreach ($templateRows as $row) {
                        fputcsv($handle, (array)$row);
                    }

                    fclose($handle);
                }, $filename);

            });
    }
}
