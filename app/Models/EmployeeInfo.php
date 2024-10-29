<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class EmployeeInfo extends Model
{
    use SoftDeletes;

    protected $table = 'employee_info';

    protected $guarded = [];

    protected $appends = ['employee_details', 'uc_full_name', 'employee_name_id'];

    public function getEmployeeDetailsAttribute()
    {
        return $this->first_name . " " . $this->last_name . " (" . $this->phone2 . ") ";
    }

    public function getUcFullNameAttribute()
    {
        return ucfirst($this->first_name . " " . $this->last_name);
    }

    public function getEmployeeNameIdAttribute()
    {
        return ucfirst($this->first_name . " " . $this->last_name) . "(" . $this->employeeID . ")";
    }


    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the bank.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }
    /**
     * Get the bank branches.
     */
    public function bank_branch()
    {
        return $this->belongsTo(BankBranch::class, 'bank_branch_id', 'id');
    }
    /**
     * Get the branches.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


    /**
     * Scope a query to only Company
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthCompany($query)
    {
        if (!Auth::user()->can(['super-admin']))
            $query = $query->where('company_id', Auth::user()->company_id);

        return $query;
    }

    /**
     * Get the User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    /**
     * Get the Designation.
     */
    public function designation()
    {
        return $this->belongsTo(Designation::class);

    }

    /**
     * Get the Department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);

    }

    /**
     * Get the shift.
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);

    }

    /**
     * Get the Division.
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'nationality', 'id');

    }

    /**
     * Get the Division.
     */
    public function division()
    {
        return $this->belongsTo(Division::class);

    }

    /**
     *
     * Get the upazilla.
     */
    public function upazilla()
    {
        return $this->belongsTo(Upazilla::class);

    }

    /**
     * Get the union.
     */
    public function union()
    {
        return $this->belongsTo(Union::class);

    }

    /**
     * Get the District.
     */
    public function district()
    {
        return $this->belongsTo(District::class);

    }

    /**
     * Get the District.
     */
    public function area()
    {
        return $this->belongsTo(Area::class);

    }

    /**
     * Get the Region.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);

    }

    /**
     * Get the Thana.
     */
    public function thana()
    {
        return $this->belongsTo(Thana::class);

    }

    public function getDocumentID($key){

        if ($key == "nid") {
            $document = DocumentType::where('name', 'National ID(NID)')->first();
        } elseif ($key == "cv") {
            $document = DocumentType::where('name', 'Resume/CV')->first();
        } elseif ($key == "passport_number") {
            $document = DocumentType::where('name', 'Passport')->first();
        } elseif ($key == "diving_license") {
            $document = DocumentType::where('name', 'Driving License')->first();
        } elseif ($key == "pr_number") {
            $document = DocumentType::where('name', 'Personal Residence(PR)')->first();
        } elseif ($key == "insurance_number") {
            $document = DocumentType::where('name', 'Insurance ID')->first();
        } elseif ($key == "visa_expire") {
            $document = DocumentType::where('name', 'Visa Copy')->first();
        } else {

            $fileName = normal_writing_format($key);
            $document = DocumentType::where('name', $fileName)->first();
        }

        return $document;
    }

    public function getFile($employee_id, $key)
    {
        $document = $this->getDocumentID($key);

        return EmployeeSubmittedDocument::where("employee_id", $employee_id)
            ->where('document_type_id', $document->id)
            ->first();

    }

    public function attached_file()
    {
        return $this->hasMany(EmployeeSubmittedDocument::class, 'employee_id', 'id');

    }


    public function sale_commissions()
    {
        return $this->hasMany(SaleCommission::class, 'employee_id', 'id');

    }

    public function total_sale_commissions()
    {
        return $this->hasMany(SaleCommission::class, 'employee_id', 'id')->sum('commission_amount');

    }

    public function paid_commissions()
    {
        return $this->hasMany(PaidCommission::class, 'employee_id', 'id');

    }

    public function total_paid_commissions()
    {
        return $this->hasMany(PaidCommission::class, 'employee_id', 'id')->sum('amount');

    }

    public static function get_join_years()
    {
        return EmployeeInfo::selectRaw('Year(join_date) as join_years')->groupBy('join_years')->pluck('join_years');
    }

    public static function get_dob_years()
    {
        return EmployeeInfo::selectRaw('Year(dob) as dob_years')->groupBy('dob_years')->pluck('dob_years');;
    }

    public static function get_passport_years()
    {
        return EmployeeInfo::selectRaw('Year(passport_expire) as years')->groupBy('years')->pluck('years');;
    }

    public static function get_visa_years()
    {
        return EmployeeInfo::selectRaw('Year(visa_expire) as years')->groupBy('years')->pluck('years');;
    }

    public static function get_pr_years()
    {
        return EmployeeInfo::selectRaw('Year(pr_expire) as years')->groupBy('years')->pluck('years');;
    }

    public static function get_driving_years()
    {
        return EmployeeInfo::selectRaw('Year(driving_expire) as years')->groupBy('years')->pluck('years');;
    }


}
