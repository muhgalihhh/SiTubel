<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use App\Models\Team;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Filament\Navigation\NavigationItem;
use App\Filament\Resources\UserResource;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\PegawaiResource;
use App\Filament\Pages\Tenancy\RegisterTeam;
use App\Filament\Resources\UnitKerjaResource;
use App\Filament\Resources\IzinSeleksiResource;
use Illuminate\Session\Middleware\StartSession;
use App\Filament\Resources\TugasBelajarResource;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Resources\PengajuanIzinSeleksiResource;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\DaftarPengajuanTugasBelajarResource;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop(true)
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Teal,
            ])
            ->brandName('SiTubel')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->font('nunito')
            ->widgets([
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->databaseNotifications()
            ->renderHook(
                'panels::body.end',
                fn(): View => view('filament.footer')
            )
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make('Dashboard')
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->url('/admin')
                                ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard')),
                        ]),

                    NavigationGroup::make('Peran dan Izin')
                        ->items([
                            NavigationItem::make('Roles')
                                ->icon('heroicon-o-users')
                                ->isActiveWhen(fn(): bool => request()->routeIs([
                                    'filament.admin.resources.roles.index',
                                    'filament.admin.resources.roles.create',
                                    'filament.admin.resources.roles.view',
                                    'filament.admin.resources.roles.edit',
                                ]))
                                ->url(fn(): string => '/admin/roles'),
                            NavigationItem::make('Permissions')
                                ->icon('heroicon-o-lock-closed')
                                ->isActiveWhen(fn(): bool => request()->routeIs([
                                    'filament.admin.resources.permissions.index',
                                    'filament.admin.resources.permissions.create',
                                    'filament.admin.resources.permissions.view',
                                    'filament.admin.resources.permissions.edit',
                                ]))
                                ->url(fn(): string => '/admin/permissions'),

                        ]),
                    NavigationGroup::make('User')
                        ->items([
                            ...UserResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Konfigurasi')
                        ->items([
                            ...PegawaiResource::getNavigationItems(),
                            ...UnitKerjaResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Pengajuan Tugas Belajar')
                        ->items([
                            ...PengajuanIzinSeleksiResource::getNavigationItems(),
                            ...DaftarPengajuanTugasBelajarResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Tugas Belajar')
                        ->items([
                            ...IzinSeleksiResource::getNavigationItems(),
                            ...TugasBelajarResource::getNavigationItems(),
                        ]),
                ]);
            });
    }
}
