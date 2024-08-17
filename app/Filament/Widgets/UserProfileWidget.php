<?php

namespace App\Filament\Widgets;

use Filament\Forms;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class UserProfileWidget extends Widget implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.widgets.user-profile-widget';

    public $name;
    public $email;

    public $password;
    public function mount(): void
    {
        $this->form->fill([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name'),
            TextInput::make('email')
                ->label('Email'),
            TextInput::make('password')
                ->label('Password')
                ->password(),

        ];
    }

    public function save()
    {
        $data = $this->form->getState();

        // Validate the password field
        if (!empty($data['password'])) {
            // Only update password if it's not empty
            Auth::user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']), // Hash the password
            ]);
        } else {
            // Update user without changing the password
            Auth::user()->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
        }

        // Send notification
        Notification::make()
            ->title('Profile Updated')
            ->success()
            ->send();
    }



}
