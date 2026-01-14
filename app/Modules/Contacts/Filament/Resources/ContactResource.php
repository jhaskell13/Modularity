<?php

namespace App\Modules\Contacts\Filament\Resources;

use App\Modules\Contacts\Models\Contact;
use App\Modules\Contacts\Filament\Resources\Pages\CreateContact;
use App\Modules\Contacts\Filament\Resources\Pages\EditContact;
use App\Modules\Contacts\Filament\Resources\Pages\ListContacts;
use App\Modules\Contacts\Filament\Resources\Schemas\ContactForm;
use App\Modules\Contacts\Filament\Resources\Tables\ContactsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static string|UnitEnum|null $navigationGroup = 'Forms';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Contacts';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ContactForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContactsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContacts::route('/'),
            'create' => CreateContact::route('/create'),
            'edit' => EditContact::route('/{record}/edit'),
        ];
    }
}
