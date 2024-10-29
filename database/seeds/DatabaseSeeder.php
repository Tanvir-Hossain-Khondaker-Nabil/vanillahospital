<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         // All Table Truncate Before DB Seed

//        $this->call(AllTableTruncateSeeder::class);
//
////       // Start Seeding
//
//        $this->call(MembershipTableSeeder::class);
//        $this->call(CountryTableSeeder::class);
//        $this->call(DivisionDistrictUpazillaUnionTableSeeder::class);
//        $this->call(MemberTableSeeder::class);
//        $this->call(RolesTableSeeder::class);
//        $this->call(PermissionTableSeeder::class);
//        $this->call(UsersTableSeeder::class);
//        $this->call(PaymentMethodTableSeeder::class);
//        $this->call(TransactionCategoriesTableSeeder::class);
//        $this->call(AccountClassTableSeeder::class);
    //    $this->call(AccountTypesTableSeeder::class);
//        $this->call(CompanyTableSeeder::class);
//        $this->call(CashAndBankTableSeeder::class);
//        $this->call(SharerTableSeeder::class);
//        $this->call(FiscalYearTableSeeder::class);
//        $this->call(SettingsTableSeeder::class);
//        $this->call(DeliverySystemTableSeeder::class);
//        $this->call(AreaTableSeeder::class);
//        $this->call(UnitTableSeeder::class);
//        $this->call(CategoryTableSeeder::class);
//        $this->call(BanksTableSeeder::class);
//        $this->call(ReconciliationAccountTypeSeeder::class);
//        $this->call(DocumentTypeSeeder::class);
//        $this->call(CreateNewPackage::class);
//          $this->call(ItemSeed::class);
//          $this->call(PharmacyItemTableSeeder::class);
//          $this->call(EmployeInfoTableSeeder::class);

          $this->call(EmployeeTableSeeder::class);
//          $this->call(CreateAttendanceSeeder::class);


    }
}