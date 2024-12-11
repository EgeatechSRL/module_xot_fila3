<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\Xot\Filament\Actions\Header;

// Header actions must be an instance of Filament\Actions\Action, or Filament\Actions\ActionGroup.
// use Filament\Tables\Actions\Action;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Modules\Xot\Actions\String\SanitizeAction;

class SanitizeFieldsHeaderAction extends Action
{
    public array $fields = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->translateLabel()
            ->label('')
            ->tooltip('sanitize')
            ->icon('heroicon-o-shield-exclamation')
            ->action(function (ListRecords $livewire) {
                $resource = $livewire->getResource();
                $modelClass = $resource::getModel();
                $rows = $modelClass::get();
                $c = 0;
                foreach ($rows as $row) {
                    $save = false;
                    foreach ($this->fields as $field) {
                        $item = $row->{$field};
                        $string = app(SanitizeAction::class)->execute($item);
                        if ($string != $item) {
                            $row->{$field} = $string;
                            $save = true;
                            ++$c;
                        }
                    }
                    if ($save) {
                        $row->save();
                    }
                }
                Notification::make()
                    ->title(trans_choice('xot::actions.sanitize-fields-header.notification', $c))
                    ->success()
                    ->send()
                ;
            });
    }

    public function setFields(array $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public static function getDefaultName(): ?string
    {
        return 'sanitize-fields-header';
    }
}
