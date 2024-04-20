<?php

use App\Http\Controllers\ApplicationController;
    use App\Http\Controllers\AuditTrailController;
    use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CourseController;
    use App\Http\Controllers\ExamConfigController;
    use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamResultController;
    use App\Http\Controllers\LicenceConfigController;
    use App\Http\Controllers\LicenseController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentFeeController;
use App\Http\Controllers\RegisteredPersonnelController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScreenController;
    use App\Http\Controllers\SystemBackupController;
    use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

        if(auth()->user()) {
            return redirect()->route('home');
        }

        return redirect()->route('login');
});

Route::get('/license/original/{user_id}', [LicenseController::class, 'originalLicenseTemplate'])->name('license.original');
Route::get('/send-mail', [MailController::class, 'sendMail']);

Auth::routes(['verify' => true]);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/create', [UserController::class, 'store'])->name('users.create');
    Route::get('/users/{user_id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{id}/edit_profile', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/password/change', [UserProfileController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [UserProfileController::class, 'changePassword'])->name('password.update');


    Route::get('/user/account', [UserProfileController::class, 'index'])->name('user.account');
    Route::post('/user/account/update/profile', [UserProfileController::class, 'updateProfileImage'])->name('profile.image.update');
    Route::post('/user/account/update/profile/admin', [UserProfileController::class, 'updateProfileImageAdmin'])->name('profile.image.update.admin');


    Route::group(['middleware' => ['role:personnel']], function () {
        Route::get('/applications/index', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/list', [ApplicationController::class, 'list'])->name('applications.list');
        Route::get('/applications/apply', [ApplicationController::class, 'apply'])->name('applications.apply');
        Route::post('/applications/store', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::get('/applications/{id}/edit', [ApplicationController::class, 'edit'])->name('applications.edit');
        Route::put('/applications/{id}/update', [ApplicationController::class, 'update'])->name('applications.update');
        Route::get('/applications/{id}/cancel', [ApplicationController::class, 'cancelApplication'])->name('applications.cancel');

        // Registered Personnel
        Route::get('/personnel/register/exam/{user_id}', [RegisteredPersonnelController::class, 'index'])->name('personnel.register_exam');
        Route::post('/personnel/register/exam/', [RegisteredPersonnelController::class, 'store'])->name('personnel.exam.register');

        // Payments
        Route::get('/payments/make_payment/{application_id}/{category_id}', [PaymentController::class, 'makePayment'])->name('payments.make_payment');

        // Exams
        Route::get('/exams/mysubjects', [CourseController::class, 'registeredSubjects'])->name('mysubjects');

        // Exams Results
        Route::get('/exams/myresults', [ExamResultController::class, 'myresults'])->name('results.myresults');

        // Licence
        Route::get('/license/my-license/{user_id}', [LicenseController::class, 'myLicence'])->name('license.mylicense');

    });

    // Configurations
    Route::get('/configs', function (){
        return view('configs.index');
    })->name('configs.index');

    Route::get('/configs/licence', [LicenceConfigController::class, 'index'])->name('configs.licence');
    Route::post('/configs/licence/configure', [LicenceConfigController::class, 'store'])->name('configs.licence.configure');

    Route::get('/configs/exam', [ExamConfigController::class, 'index'])->name('configs.exam');
    Route::post('/configs/exam/configure', [ExamConfigController::class, 'store'])->name('configs.exam.configure');

    Route::get('trails/', [AuditTrailController::class, 'index'])->name('trails.index');


    Route::get('/license/provisional/{user_id}', [LicenseController::class, 'provisionalLicenseTemplate'])->name('license.provisional.personnel');

    // Notifications
    Route::get('/notifications/{user_id}', [NotificationController::class, 'index'])->name('notifications.index');


    Route::get('/configs/roles', [RoleController::class, 'index'])->name('configs.roles');
    Route::get('/configs/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/configs/roles/store', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/configs/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/configs/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/configs/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/configs/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/configs/roles/{id}/permissions', [RoleController::class, 'showPermissions'])->name('roles.permissions');
    Route::get('/configs/backups', [SystemBackupController::class, 'backup'])->name('configs.backup');

    Route::get('/applications', [ApplicationController::class, 'listView'])->name('applications.listview');
    Route::get('/applications/{id}/screen', [ApplicationController::class, 'detailView'])->name('applications.detailview');
    Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/screen/store', [ScreenController::class, 'store'])->name('applications.screen.store');
    Route::delete('/applications/screen/{id}', [ScreenController::class, 'destroy'])->name('applications.screen.destroy');
    Route::get('/applications/screen/approvals', [ScreenController::class, 'index'])->name('applications.screen.approvals');
    Route::get('/applications/screen/approved', [ScreenController::class, 'approvedList'])->name('applications.screen.approved');
    Route::get('/applications/screen/approve/{id}', [ScreenController::class, 'approveApplication'])->name('applications.screen.approve');
    Route::get('/applications/screen/rejected', [ScreenController::class, 'rejectedList'])->name('applications.screen.rejected');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    // Payment Categories
    Route::get('/payments/categories', [PaymentCategoryController::class, 'index'])->name('payments.categories');
    Route::get('/payments/categories/create', [PaymentCategoryController::class, 'create'])->name('payments.categories.create');
    Route::get('/payments/categories/{id}/edit', [PaymentCategoryController::class, 'edit'])->name('payments.categories.edit');
    Route::put('/payments/categories/{id}/update', [PaymentCategoryController::class, 'update'])->name('payments.categories.update');
    Route::delete('/payments/categories/{id}', [PaymentCategoryController::class, 'destroy'])->name('payments.categories.destroy');

    // Payment Fees
    Route::get('/payments/fees', [PaymentFeeController::class, 'index'])->name('payments.fees');
    Route::get('/payments/fees/create', [PaymentFeeController::class, 'create'])->name('payments.fees.create');
    Route::get('/payments/fees/{id}/edit', [PaymentFeeController::class, 'edit'])->name('payments.fees.edit');
    Route::put('/payments/fees/{id}/update', [PaymentFeeController::class, 'update'])->name('payments.fees.update');
    Route::delete('/payments/fees/{id}', [PaymentFeeController::class, 'destroy'])->name('payments.fees.destroy');

    // Exams
    Route::group(['middleware' => ['role:superadmin|Chief Inspector|Inspector|PRO|DG|BC']], function () {
        Route::get('/exams/list', [ExamController::class, 'index'])->name('exams.list');
        Route::post('/exams/list/create', [ExamController::class, 'store'])->name('exams.create');
        Route::delete('/exams/exam/{id}/destroy', [ExamController::class, 'destroy'])->name('exam.destroy');
        Route::get('/exams/courses', [CourseController::class, 'index'])->name('exams.courses');
        Route::get('/exams/courses/{id}', [CourseController::class, 'show'])->name('course.show');
        Route::post('/exams/courses/create', [CourseController::class, 'store'])->name('courses.create');
        Route::post('/exams/courses/update', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/exams/courses/{id}/destroy', [CourseController::class, 'destroy'])->name('course.destroy');
        Route::get('/exams/courses/{course_id}/detach/{personnel_category_id}', [CourseController::class, 'unassignSubjectFromCategory'])->name('course.detach');
        Route::post('/exams/courses/attach/', [CourseController::class, 'assignSubjectToCategory'])->name('course.attach');
        Route::get('/exams/subjects', [CourseController::class, 'subjects'])->name('exams.subjects');
        Route::get('/search', [CourseController::class, 'search'])->name('search');
        Route::get('/exams/upload-view', [ExamController::class, 'uploadView'])->name('exams.upload-view');
        Route::get('/exams/upload-view/personnel/{id}', [ExamController::class, 'uploadViewPersonnel'])->name('exams.upload-view.personnel');
        Route::get('/exams/upload/personnel/results/{exam_id}/{user_id}', [ExamController::class, 'uploadViewPersonnelResults'])->name('exams.upload.personnel.results');
        Route::get('/results/template/{exam_id}', [ExamController::class, 'template'])->name('results.template');
        Route::get('/exams/results/{exam_id}', [ExamResultController::class, 'resultsList'])->name('results.list');


        // Exams Results
        Route::post('/exams/results/store/{user_id}/{exam_id}', [ExamResultController::class, 'store'])->name('results.store');
        Route::get('/exams/results/update/{user_id}/{exam_id}', [ExamResultController::class, 'edit'])->name('results.edit');
        Route::post('/exams/results/update/{user_id}/{exam_id}', [ExamResultController::class, 'update'])->name('results.update');
        Route::get('/exams/edit/{id}', [ExamController::class, 'edit'])->name('exam.edit');
        Route::post('/exams/update/{id}', [ExamController::class, 'update'])->name('exam.update');
        Route::post('/exams/bulk/', [ExamResultController::class, 'bulkUpload'])->name('exam.bulk');
        Route::get('/exams/view/grades/{user_id}/{exam_id}', [ExamResultController::class, 'viewGrades'])->name('exam.view.grades');

        // Send Exam for approval
        Route::get('/exams/send/{id}', [ExamController::class, 'sendForApproval'])->name('exam.send');

        // Finalize Exam
        Route::get('/exams/finalize/{id}', [ExamController::class, 'finalize'])->name('exam.finalize');

        //Publish Exam
        Route::get('/exams/publish/{id}', [ExamController::class, 'publish'])->name('exam.publish');

        // License
        Route::get('/license/provisional/view/all', [LicenseController::class, 'index'])->name('license.all-provisional');
        Route::get('/license/original/view/all', [LicenseController::class, 'originalList'])->name('license.all-original');
        Route::post('/license/revoke', [LicenseController::class, 'revokeLicense'])->name('license.revoke');
        Route::get('/license/reinstate/{id}', [LicenseController::class, 'reinstateLicense'])->name('license.reinstate');

    });
});
