<?php

namespace App\Providers;

use App\Models\License;
use App\Models\RegisteredPersonnel;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Application;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            $staffCount = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'superadmin')
                    ->orWhere('name', 'Inspector')
                    ->orWhere('name', 'Chief Inspector')
                    ->orWhere('name', 'DG')
                    ->orWhere('name', 'BC')
                    ->orWhere('name', 'PRO');
            })->count();

            $personnelCount = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'personnel');
            })->count();


            $pharmacistsCount = User::where('personnel_category_id', 1)->get()->count();
            $pharmacyTechniciansCount = User::where('personnel_category_id', 3)->get()->count();
            $pharmacyAssistantsCount = User::where('personnel_category_id', 2)->get()->count();

            $totalApplicationsCount = Application::count();
            $pendingApplicationsCount = Application::where('application_status', 'pending')->count();
            $approvedApplicationsCount = Application::where('application_status', 'approved')->count();
            $inReviewApplicationsCount = Application::where('application_status', 'in-review')->count();
            $rejectedApplicationsCount = Application::where('application_status', 'rejected')->count();

            $totalActiveLicenses = License::where('is_revoked', false)->where('expiry_date', '>', now())->count();
            $totalExpiredLicenses = License::where('expiry_date', '<', now())->count();
            $totalRevokedLicenses = License::where('is_revoked', true)->count();
            $totalLicenses = License::count();

            // Pharmacist Licenses
            $pharmacists_active_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', false)
                    ->where('expiry_date', '>', now())
                    ->where('personnel_category_id', 1);
            })->count();
            $pharmacists_expired_licenses = User::whereHas('license', function ($query) {
                $query->where('expiry_date', '<', now())
                    ->where('personnel_category_id', 1);
            })->count();
            $pharmacists_revoked_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', true)
                    ->where('personnel_category_id', 1);
            })->count();

            // Pharmacy Technician Licenses
            $pharmacy_technicians_active_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', false)
                    ->where('expiry_date', '>', now())
                    ->where('personnel_category_id', 3);
            })->count();
            $pharmacy_technicians_expired_licenses = User::whereHas('license', function ($query) {
                $query->where('expiry_date', '<', now())
                    ->where('personnel_category_id', 3);
            })->count();
            $pharmacy_technicians_revoked_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', true)
                    ->where('personnel_category_id', 3);
            })->count();

            // Pharmacy Assistant Licenses
            $pharmacy_assistants_active_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', false)
                    ->where('expiry_date', '>', now())
                    ->where('personnel_category_id', 2);
            })->count();
            $pharmacy_assistants_expired_licenses = User::whereHas('license', function ($query) {
                $query->where('expiry_date', '<', now())
                    ->where('personnel_category_id', 2);
            })->count();
            $pharmacy_assistants_revoked_licenses = User::whereHas('license', function ($query) {
                $query->where('is_revoked', true)
                    ->where('personnel_category_id', 2);
            })->count();


            $view->with('pharmacistsCount', $pharmacistsCount);
            $view->with('pharmacyTechniciansCount', $pharmacyTechniciansCount);
            $view->with('pharmacyAssistantsCount', $pharmacyAssistantsCount);

            $view->with('staffCount', $staffCount);
            $view->with('personnelCount', $personnelCount);

            $view->with('totalApplicationsCount', $totalApplicationsCount);
            $view->with('pendingApplicationsCount', $pendingApplicationsCount);
            $view->with('approvedApplicationsCount', $approvedApplicationsCount);
            $view->with('inReviewApplicationsCount', $inReviewApplicationsCount);
            $view->with('rejectedApplicationsCount', $rejectedApplicationsCount);

            $view->with('totalActiveLicenses', $totalActiveLicenses);
            $view->with('totalExpiredLicenses', $totalExpiredLicenses);
            $view->with('totalRevokedLicenses', $totalRevokedLicenses);
            $view->with('totalLicenses', $totalLicenses);

            $view->with('pharmacists_active_licenses', $pharmacists_active_licenses);
            $view->with('pharmacists_expired_licenses', $pharmacists_expired_licenses);
            $view->with('pharmacists_revoked_licenses', $pharmacists_revoked_licenses);

            $view->with('pharmacy_technicians_active_licenses', $pharmacy_technicians_active_licenses);
            $view->with('pharmacy_technicians_expired_licenses', $pharmacy_technicians_expired_licenses);
            $view->with('pharmacy_technicians_revoked_licenses', $pharmacy_technicians_revoked_licenses);

            $view->with('pharmacy_assistants_active_licenses', $pharmacy_assistants_active_licenses);
            $view->with('pharmacy_assistants_expired_licenses', $pharmacy_assistants_expired_licenses);
            $view->with('pharmacy_assistants_revoked_licenses', $pharmacy_assistants_revoked_licenses);

        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
