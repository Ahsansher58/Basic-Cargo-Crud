<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\InventoryAndStockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\GlobalTagsController;
use App\Http\Controllers\Settings\PaymentTermsController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\FinancialYearController;
use App\Http\Controllers\Settings\MenuModuleController;
use App\Http\Controllers\Settings\VouchersMasterController;
use App\Http\Controllers\Settings\VoucherTermsController;
use App\Http\Controllers\Settings\VoucherTypeTermsController;
use App\Http\Controllers\Settings\VoucherTypesController;
use App\Http\Controllers\Settings\VoucherSeriesController;
use App\Http\Controllers\Settings\SoftwareBrandingController;
use App\Http\Controllers\Settings\MasterSettingsController;
use App\Http\Controllers\Settings\AdvanceSettingsController;
use App\Http\Controllers\Settings\OrganizationDepartmentController;
use App\Http\Controllers\Settings\SettingLanguageController;
use App\Http\Controllers\Settings\DateFormatsController;
use App\Http\Controllers\Settings\CurrenciesController;
use App\Http\Controllers\Settings\TimeFormatController;
use App\Http\Controllers\Settings\TimezonesController;
use App\Http\Controllers\Settings\ClearanceSettingsController;
use App\Http\Controllers\Settings\DeliveryTypeController;
use App\Http\Controllers\Settings\ShippingRiskController;
use App\Http\Controllers\Settings\TaxPaidByController;
use App\Http\Controllers\Settings\FuelTypeController;
use App\Http\Controllers\Settings\TransportationModeController;
use App\Http\Controllers\Settings\RateCalculationTypeController;
use App\Http\Controllers\Settings\AdditionalChargesController;
use App\Http\Controllers\Settings\ContainerSizeController;
use App\Http\Controllers\Settings\ContainerTypeController;
use App\Http\Controllers\Settings\CargoTypesController;
use App\Http\Controllers\Settings\ContainerReturnStatusesController;
use App\Http\Controllers\Settings\CustomClearanceStatusController;
use App\Http\Controllers\StatesController;

    /********************** Payment Term ****************************/
    Route::resource('payment-terms', PaymentTermsController::class);
   Route::group(['prefix' => 'payment-terms'], function () {
      Route::get('/list', [PaymentTermsController::class, 'list']);
      Route::any('/delete', [PaymentTermsController::class, 'destroy'])->name('payment-terms.destroy');
      Route::any('/changestatus', [PaymentTermsController::class, 'changeStatus'])->name('payment-terms.changestatus');
      Route::any('/changedefault', [PaymentTermsController::class, 'changedefault'])->name('payment-terms.changedefault');
   });

   /**************************** Roles Route*********************************/
   Route::resource('roles', RoleController::class);
   Route::group(['prefix' => 'roles'], function () {
      Route::get('list', [RoleController::class, 'list']);
      Route::get('edit-role/{id}', [RoleController::class, 'editRole'])->name('roles.edit-role');
      Route::any('update-role/{id}', [RoleController::class, 'updateRole'])->name('roles.update-role');
      Route::post('delete/', [RoleController::class, 'destroy'])->name('roles.destroy');
   });

   /******************************* Financial Year Routes *************************************/
   Route::resource('financialyear', FinancialYearController::class);
   Route::group(['prefix' => 'financialyear'], function () {
      Route::any('/changestatus', [FinancialYearController::class, 'changestatus'])->name('financialyear.changestatus');
      Route::match(['GET', 'POST'], '/delete', [FinancialYearController::class, 'delete'])->name('financialyear.destroy');
      Route::any('/changedefault', [FinancialYearController::class, 'changedefault'])->name('financialyear.changedefault');
   });

   /********************************** Menu Models *********************************************/
   Route::resource('modules', MenuModuleController::class);
   Route::group(['prefix' => 'modules'], function () {
      Route::any('/changestatus', [MenuModuleController::class, 'changestatus'])->name('modules.changestatus');
      Route::any('/delete', [MenuModuleController::class, 'destroy'])->name('modules.destroy');
      Route::any('/info/{id}', [MenuModuleController::class, 'moduleInfo'])->name('modules.info');
      Route::any('/infoStore/{id}', [MenuModuleController::class, 'infoStore'])->name('modules.infoStore');
      Route::any('/help-text/{id}', [MenuModuleController::class, 'helpText'])->name('modules.help-text');
   });

   /********************** Vouchers Master ****************************/
   Route::resource('vouchers-master', VouchersMasterController::class);
   Route::prefix('/vouchers-master/')->group(function () {
        Route::any('/delete',[VouchersMasterController::class,'destroy'])->name('vouchers-master.destroy');
        Route::any('/change-status',[VouchersMasterController::class,'changeStatus'])->name('vouchers-master.changestatus');
        Route::any('/change-tax-calculation',[VouchersMasterController::class,'changeTaxCalculation'])->name('vouchers-master.changetaxcalculation');
   });
   Route::get('voucher-master-settings/{id}/{type_id}', [VouchersMasterController::class, 'voucherMasterSettings'])->name('voucher-master-settings');
   Route::post('voucher-master-settings/{id}/{type_id}', [VouchersMasterController::class, 'storeVoucherMasterSettings'])->name('voucher-master-settings');

    /********************** Voucher Terms ****************************/
    // Route::resource('voucher-terms', VoucherTermsController::class);
    Route::prefix('/voucher-terms/')->group(function () {
        Route::get('/{id?}',[VoucherTermsController::class,'index'])->name('voucher-terms.index');
        Route::get('/{id?}/create',[VoucherTermsController::class,'create'])->name('voucher-terms.create');
        Route::patch('/{id?}/store',[VoucherTermsController::class,'store'])->name('voucher-terms.store');
        Route::get('/{id}/edit',[VoucherTermsController::class,'edit'])->name('voucher-terms.edit');
        Route::patch('/{id}/update',[VoucherTermsController::class,'update'])->name('voucher-terms.update');
        Route::any('/delete',[VoucherTermsController::class,'destroy'])->name('voucher-terms.destroy');
        Route::any('/change-status',[VoucherTermsController::class,'changeStatus'])->name('voucher-terms.changestatus');
        Route::any('/change-default',[VoucherTermsController::class,'changedefault'])->name('voucher-terms.changedefault');
    });

    /********************** Vouchers Types ****************************/
    Route::resource('voucher-types', VoucherTypesController::class);
    Route::prefix('/voucher-types/')->group(function () {
        Route::any('/delete',[VoucherTypesController::class,'destroy'])->name('voucher-types.destroy');
        Route::any('/change-status',[VoucherTypesController::class,'changeStatus'])->name('voucher-types.changestatus');
        Route::any('/change-default',[VoucherTypesController::class,'changeDefault'])->name('voucher-types.changedefault');
        Route::any('/change-tax-applicable',[VoucherTypesController::class,'changeTaxApplicable'])->name('voucher-types.change-tax-applicable');
        Route::any('/change-show-hsn',[VoucherTypesController::class,'changeShowHsn'])->name('voucher-types.change-show-hsn');
    });

    Route::any('voucher-master-types/{id}',[VoucherTypesController::class,'index'])->name('voucher-master-types');


    /********************** Vouchers Series ****************************/
    Route::resource('voucher-series', VoucherSeriesController::class);

    Route::prefix('/voucher-series/')->group(function () {
        Route::any('/delete',[VoucherSeriesController::class,'destroy'])->name('voucher-series.destroy');
        Route::any('/change-status',[VoucherSeriesController::class,'changeStatus'])->name('voucher-series.changestatus');
     
    });


                      /********************** Clearance Settings ****************************/

     Route::get('/clearance-settings/{id}', [ClearanceSettingsController::class, 'clearanceSettingsIndex'])->name('clearance-settings.index');
     Route::get('/create-clearance-settings/{id}', [ClearanceSettingsController::class, 'createClearanceSettings'])->name('clearance-settings.create');
     Route::post('/store-clearance-settings/{id}', [ClearanceSettingsController::class, 'storeClearanceSettings'])->name('store-clearance-settings');
     Route::any('/delete-clearance-settings/{id}', [ClearanceSettingsController::class, 'deleteClearanceSettings'])->name('clearance-settings.destroy');






   /********************************** Software Branding ***********************************/
   Route::resource('software-branding', SoftwareBrandingController::class);

   /********************************** Master Settings ***********************************/
   Route::resource('master-settings', MasterSettingsController::class);
   Route::any('master-settings/changestatus', [MasterSettingsController::class, 'changestatus'])->name('master-settings.changestatus');
   Route::any('master-settings/delete', [MasterSettingsController::class, 'destroy'])->name('master-settings.destroy');
   Route::get('refresh-master-settings', [MasterSettingsController::class, 'refreshSettings'])->name('refresh-master-settings');
   // Tags
   Route::resource('global-tags', GlobalTagsController::class);
   Route::any('global-tags/status', [GlobalTagsController::class, 'changeStatus'])->name('global-tags.changeStatus');
   Route::any('global-tags/delete', [GlobalTagsController::class, 'destroy'])->name('global-tags.destroy');

   /********************************** Advance Settings ***********************************/
   Route::resource('advance-settings', AdvanceSettingsController::class);

   /********************************** Organization Department **********************************/
   Route::resource('organization-department', OrganizationDepartmentController::class);
   Route::group(['prefix' => 'organization-department'], function () {
      Route::any('/delete', [OrganizationDepartmentController::class, 'destroy'])->name('organization-department.destroy');
      Route::any('/changeStatus', [OrganizationDepartmentController::class, 'changeStatus'])->name('organization-department.changestatus');
      Route::any('/changedefault', [OrganizationDepartmentController::class, 'changedefault'])->name('organization-department.changedefault');
   });
      /********************** setting language ****************************/
    Route::resource('setting-languages', SettingLanguageController::class);
    Route::group(['prefix' => 'setting-languages'], function () {
       Route::any('/changedefault', [SettingLanguageController::class, 'changedefault'])->name('setting-languages.changedefault');
    });
      /********************** Date Format ****************************/
    Route::resource('date-formats', DateFormatsController::class);
    Route::group(['prefix' => 'date-formats'], function () {
       Route::any('/changedefault', [DateFormatsController::class, 'changedefault'])->name('date-formats.changedefault');
    });
      /********************** Time Format ****************************/
    Route::resource('time-formats', TimeFormatController::class);
    Route::group(['prefix' => 'time-formats'], function () {
       Route::any('/changedefault', [TimeFormatController::class, 'changedefault'])->name('time-formats.changedefault');
    });
      /********************** TimeZone ****************************/
    Route::resource('timezones', TimezonesController::class);
    Route::group(['prefix' => 'timezones'], function () {
       Route::any('/changedefault', [TimezonesController::class, 'changedefault'])->name('timezones.changedefault');
    });

   /********************** Currencies ****************************/
   Route::resource('currencies', CurrenciesController::class);
   Route::group(['prefix' => 'currencies'], function () {
      Route::any('/changedefault', [CurrenciesController::class, 'changedefault'])->name('currencies.changedefault');
   });

  /********************** Voucher Terms ****************************/
  // Route::resource('voucher-terms', VoucherTypeTermsController::class);
  Route::prefix('/voucher-type-terms/')->group(function () {
      Route::get('/{id?}',[VoucherTypeTermsController::class,'index'])->name('voucher-type-terms.index');
      Route::get('/{id?}/create',[VoucherTypeTermsController::class,'create'])->name('voucher-type-terms.create');
      Route::patch('/{id?}/store',[VoucherTypeTermsController::class,'store'])->name('voucher-type-terms.store');
      Route::get('/{id}/edit',[VoucherTypeTermsController::class,'edit'])->name('voucher-type-terms.edit');
      Route::patch('/{id}/update',[VoucherTypeTermsController::class,'update'])->name('voucher-type-terms.update');
      Route::any('/delete',[VoucherTypeTermsController::class,'destroy'])->name('voucher-type-terms.destroy');
      Route::any('/change-status',[VoucherTypeTermsController::class,'changeStatus'])->name('voucher-type-terms.changestatus');
      Route::any('/change-default',[VoucherTypeTermsController::class,'changedefault'])->name('voucher-type-terms.changedefault');
  });
   /********************************** City **********************************/
   Route::resource('cities', CityController::class);
   Route::group(['prefix' => 'cities'], function () {
      Route::any('/delete', [CityController::class, 'destroy'])->name('cities.destroy');
      Route::any('/change-status',[CityController::class,'changeStatus'])->name('cities.changestatus');
   });
   /********************************** States **********************************/
   Route::resource('states', StatesController::class);
   Route::group(['prefix' => 'states'], function () {
      Route::any('/delete', [StatesController::class, 'destroy'])->name('states.destroy');
      Route::any('/change-status',[StatesController::class,'changeStatus'])->name('states.changestatus');
   });
     /********************** Country ****************************/
     Route::resource('countries', CountriesController::class);
     Route::group(['prefix' => 'countries'], function () {
        Route::any('/change-status', [CountriesController::class, 'changeStatus'])->name('countries.changestatus');
    });

   /********************** Delivery Type ****************************/
     Route::resource('delivery-types', DeliveryTypeController::class);
    Route::group(['prefix' => 'delivery-types'], function () {
        Route::any('/change-status', [DeliveryTypeController::class, 'changeStatus'])->name('delivery-types.changestatus');
        Route::any('/delete', [DeliveryTypeController::class, 'destroy'])->name('delivery-types.destroy');
    });

      /********************** Tax Paid By ****************************/
     Route::resource('tax-paid-by', TaxPaidByController::class);
    Route::group(['prefix' => 'tax-paid-by'], function () {
        Route::any('/change-status', [TaxPaidByController::class, 'changeStatus'])->name('tax-paid-by.changestatus');
        Route::any('/change-default', [TaxPaidByController::class, 'changeDefault'])->name('tax-paid-by.changedefualt');
        Route::any('/reverse-charge', [TaxPaidByController::class, 'reverseCharge'])->name('tax-paid-by.reversecharge');
        Route::any('/delete', [TaxPaidByController::class, 'destroy'])->name('tax-paid-by.destroy');
    });

    /********************** Transportation Mode ****************************/
     Route::resource('transportation-mode', TransportationModeController::class);
     Route::group(['prefix' => 'transportation-mode'], function () {
        Route::any('/change-status', [TransportationModeController::class, 'changeStatus'])->name('transportation-mode.changestatus');
        Route::any('/change-default', [TransportationModeController::class, 'changeDefault'])->name('transportation-mode.changedefualt');
        Route::any('/delete', [TransportationModeController::class, 'destroy'])->name('transportation-mode.destroy');
    });

     /********************** Transportation Mode ****************************/
     Route::resource('rate-calculation-type', RateCalculationTypeController::class);
     Route::group(['prefix' => 'rate-calculation-type'], function () {
        Route::any('/change-status', [RateCalculationTypeController::class, 'changeStatus'])->name('rate-calculation-type.changestatus');
        Route::any('/change-default', [RateCalculationTypeController::class, 'changeDefault'])->name('rate-calculation-type.changedefualt');
        Route::any('/delete', [RateCalculationTypeController::class, 'destroy'])->name('rate-calculation-type.destroy');
    });

     /********************** Shipping Risk ****************************/
     Route::resource('shipping-risk', ShippingRiskController::class);
    Route::group(['prefix' => 'shipping-risk'], function () {
        Route::any('/change-status', [ShippingRiskController::class, 'changeStatus'])->name('shipping-risk.changestatus');
        Route::any('/delete', [ShippingRiskController::class, 'destroy'])->name('shipping-risk.destroy');
    });

     /********************** Additional Charges ****************************/
     Route::resource('additional-charge', AdditionalChargesController::class);
    Route::group(['prefix' => 'additional-charge'], function () {
        Route::any('/change-status', [AdditionalChargesController::class, 'changeStatus'])->name('additional-charge.changestatus');
        Route::any('/is-mandatory', [AdditionalChargesController::class, 'isMandatory'])->name('additional-charge.ismandatory');
        Route::any('/is-expense', [AdditionalChargesController::class, 'isExpense'])->name('additional-charge.isexpense');
        Route::any('/delete', [AdditionalChargesController::class, 'destroy'])->name('additional-charge.destroy');
    });


      /********************** Fuel Type  ****************************/
     Route::resource('fuel-type', FuelTypeController::class);
    Route::group(['prefix' => 'fuel-type'], function () {
        Route::any('/change-status', [FuelTypeController::class, 'changeStatus'])->name('fuel-type.changestatus');
        Route::any('/change-default', [FuelTypeController::class, 'changeDefault'])->name('fuel-type.changedefualt');
        Route::any('/delete', [FuelTypeController::class, 'destroy'])->name('fuel-type.destroy');
    });

      /********************** Container Size ****************************/
     Route::resource('container-size', ContainerSizeController::class);
     Route::group(['prefix' => 'container-size'], function () {
        Route::any('/change-status', [ContainerSizeController::class, 'changeStatus'])->name('container-size.changestatus');
        Route::any('/change-default', [ContainerSizeController::class, 'changeDefault'])->name('container-size.changedefualt');
        Route::any('/delete', [ContainerSizeController::class, 'destroy'])->name('container-size.destroy');
    });

     /********************** Container Type ****************************/
     Route::resource('container-type', ContainerTypeController::class);
     Route::group(['prefix' => 'container-type'], function () {
        Route::any('/change-status', [ContainerTypeController::class, 'changeStatus'])->name('container-type.changestatus');
        Route::any('/change-default', [ContainerTypeController::class, 'changeDefault'])->name('container-type.changedefualt');
        Route::any('/delete', [ContainerTypeController::class, 'destroy'])->name('container-type.destroy');
    });

     /********************** Cargo Types ****************************/
     Route::resource('cargo-types', CargoTypesController::class);
     Route::group(['prefix' => 'cargo-types'], function () {
        Route::any('/change-status', [CargoTypesController::class, 'changeStatus'])->name('cargo-types.changestatus');
        Route::any('/change-default', [CargoTypesController::class, 'changeDefault'])->name('cargo-types.changedefualt');
        Route::any('/delete', [CargoTypesController::class, 'destroy'])->name('cargo-types.destroy');
    });


     /********************** Container Return Status ****************************/
     Route::resource('container-return-status', ContainerReturnStatusesController::class);
     Route::group(['prefix' => 'container-return-status'], function () {
        Route::any('/change-status', [ContainerReturnStatusesController::class, 'changeStatus'])->name('container-return-status.changestatus');
        Route::any('/change-default', [ContainerReturnStatusesController::class, 'changeDefault'])->name('container-return-status.changedefualt');
        Route::any('/delete', [ContainerReturnStatusesController::class, 'destroy'])->name('container-return-status.destroy');
    });


     /********************** Custom Clearance Status ****************************/
     Route::resource('custom-clearance-status', CustomClearanceStatusController::class);
     Route::group(['prefix' => 'custom-clearance-status'], function () {
        Route::any('/change-status', [CustomClearanceStatusController::class, 'changeStatus'])->name('custom-clearance-status.changestatus');
        Route::any('/change-default', [CustomClearanceStatusController::class, 'changeDefault'])->name('custom-clearance-status.changedefualt');
        Route::any('/delete', [CustomClearanceStatusController::class, 'destroy'])->name('custom-clearance-status.destroy');
    });

