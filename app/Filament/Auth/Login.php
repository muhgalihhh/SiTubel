<?php

// App\Filament\Auth\Login.php

namespace App\Filament\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends AuthLogin
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getLoginFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ])->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label('NIP')
            ->placeholder('Masukkan NIP')
            ->required()
            ->autofocus()
            ->disableAutocomplete();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        // Fetch the user based on NIP and ensure they have the 'pegawai' role
        $user = \App\Models\User::whereHas('pegawai', function ($query) use ($data) {
            $query->where('NIP', $data['login']);
        })->whereHas('roles', function ($query) {
            $query->where('name', 'pegawai');
        })->first();

        // Check if the user exists and has the 'pegawai' role
        if (!$user) {
            Notification::make()
                ->title('NIP atau Password salah dan Anda, atau mungkin tidak memiliki akses sebagai pegawai.')
                ->danger()
                ->send();
            throw ValidationException::withMessages([
                'login' => ['NIP tidak ditemukan atau Anda tidak memiliki akses sebagai pegawai.'],
            ]);
        }

        Notification::make()
            ->title('Berhasil Login.')
            ->success()
            ->send();

        return [
            'email' => $user->email, // Assuming email is used for login in auth
            'password' => $data['password'],
        ];
    }

    protected function attemptLogin(): bool
    {
        $credentials = $this->getCredentialsFromFormData($this->form->getState());
        if (!Auth::attempt($credentials, $this->form->getState()['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'login' => ['NIP atau password salah.'],
            ]);
        }

        return true;
    }
}