<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoiningLetter extends Model
{
    protected $table = 'tbl_joining_letters'; // Correct table name if it's different
    protected $fillable = ['employee_id', 'letter_content', 'joining_date', 'pdf_file']; // Added pdf_file
    
    // Relationship back to employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
