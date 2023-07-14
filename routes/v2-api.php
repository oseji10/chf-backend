<?php

use App\Http\Controllers\V2API\Auth\EmailVerificationController;
use App\Http\Controllers\V2API\Auth\PasswordResetController;
use App\Http\Controllers\V2API\COE\COEInvoiceController;
use App\Http\Controllers\V2API\Resources\ApplicationReviewController;
use App\Http\Controllers\V2API\Resources\COEController;
use App\Http\Controllers\V2API\Resources\PatientReferralController;
use App\Http\Controllers\V2API\Resources\PatientTransferRequestController;
use App\Http\Controllers\V2API\Resources\PrescriptionController;
use App\Http\Controllers\V2API\Resources\ServiceController;
use App\Http\Controllers\V2API\Resources\TransactionController;
use App\Http\Controllers\V2API\Resources\UserController;
use App\Http\Controllers\V2API\Resources\WalletTopupController;
use App\Http\Controllers\V2API\Secretariat\CHFStaffController;
use App\Http\Controllers\V2API\Secretariat\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* PROTECT THIS PART OF THE APP WITH APPLICATION GRANT */

Route::group(['middleware' => []], function () {

        Route::post('/auth/reset-user-password', [PasswordResetController::class, 'manualPasswordReset']);
        Route::post('/auth/resend-verification-email', [EmailVerificationController::class, 'sendEmailVerification']);
        Route::get('/chfstaff', [CHFStaffController::class, 'index']);

        Route::group(['middleware' => ['auth:api']], function () {
                Route::get('/me', [UserController::class, 'me']);
                Route::get('/users', [UserController::class, 'index']);
                Route::get('/users/{id}', [UserController::class, 'view']);
                Route::put('/users/{id}', [UserController::class, 'update']);


                Route::get('/transactions', [TransactionController::class, 'index']);
                Route::get('/transaction/search', [TransactionController::class, 'search']);

                Route::get('/coes', [COEController::class, 'index']);
                Route::get('/coes/analytics', [COEController::class, 'getCOEAnalytics']);
                Route::get('/coes/{id}', [COEController::class, 'view']);
                Route::get('/coes/{id}/patient-transfers', [COEController::class, 'patientTransfers']);
                Route::put('/coes/{id}/patient-transfers', [COEController::class, 'approveTransfer']);
                Route::get('/coes/{id}/patients/', [COEController::class, 'findAllCOEPatients']);
                Route::get('/coes/{id}/patients/{patient_id}', [COEController::class, 'findOneCOEPatient']);
                Route::get('/coes/{id}/staff', [COEController::class, 'staff']);
                Route::get('/application_review', [ApplicationReviewController::class, 'index']);
                Route::get('/application_review/{id}', [ApplicationReviewController::class, 'view']);

                Route::post('/patient-transfer', [PatientTransferRequestController::class, 'create']);

                Route::post('/prescription/create', [PrescriptionController::class, 'store']);
                Route::post('/prescription/fulfill', [PrescriptionController::class, 'fulfillPrescription']);
                Route::get('/prescription/patient/{patient_id}', [PrescriptionController::class, 'getPatientPrescriptions']);
                Route::get('/prescription/doctor-prescriptions', [PrescriptionController::class, 'getDoctorPrescriptions']);


                Route::get('/coes/{coe_id}/invoice', [COEInvoiceController::class, 'getHospitalInvoices']);
                Route::post('/coes/invoice', [COEInvoiceController::class, 'store']);
                Route::get('/invoices', [InvoiceController::class, 'getAllInvoices']);
                Route::post('/invoices/initiate', [InvoiceController::class, 'initiatePayment']);
                Route::post('/invoices/recommend', [InvoiceController::class, 'recommendPayment']);
                Route::post('/invoices/approve', [InvoiceController::class, 'approvePayment']);
                Route::post('/invoices/dfa_recommend', [InvoiceController::class, 'dfaRecommendPayment']);
                Route::post('/invoices/permsec_approve', [InvoiceController::class, 'permsecApprovePayment']);


                Route::get('/services', [ServiceController::class, 'index']);

                Route::post('/patient-referral', [PatientReferralController::class, 'store']);
                Route::get('/patient-referral/coe', [PatientReferralController::class, 'getCOEReferrals']);
                Route::get('/patient-referral/coe-staff', [PatientReferralController::class, 'getCOEStaffReferrals']);
                Route::patch('/patient-referral/assign-to-staff', [PatientReferralController::class, 'assignToStaff']);
                Route::patch('/patient-referral/attend-to-referral', [PatientReferralController::class, 'attendToReferral']);
                Route::patch('/patient-referral/approve', [PatientReferralController::class, 'approveReferral']);

                Route::post('/wallet-topup', [WalletTopupController::class, 'initiate']);
                Route::post('/wallet-topup/credit', [WalletTopupController::class, 'creditWallet']);
        });
});
