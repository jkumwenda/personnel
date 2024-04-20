<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\PersonnelCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoursePersonnelCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pharmacistCategory = PersonnelCategory::where('name', 'Pharmacist')->first();
        $pharmacyTechnicianCategory = PersonnelCategory::where('name', 'Pharmacy Technician')->first();
        $pharmacyAssistantCategory = PersonnelCategory::where('name', 'Pharmacy Assistant')->first();

        $pharmacyLaw = Course::where('name', 'Pharmacy Law and Ethics')->first();
        $pharmacology = Course::where('name', 'Pharmacology')->first();
        $pharmacyPractice = Course::where('name', 'Pharmacy Practice')->first();
        $clinicalPharmacy = Course::where('name', 'Clinical Pharmacy')->first();
        $pharmaceuticsAndPharmaceuticalCalculation = Course::where('name', 'Pharmaceutics and Pharmaceutical Calculation')->first();
        $medicinesAndAlliedSubstances = Course::where('name', 'Medicines and Allied Substances')->first();
        $pharmacologyAndPharmacyPractice = Course::where('name', 'Pharmacology and Pharmacy Practice')->first();
        $oralExams = Course::where('name', 'Oral Exams')->first();

        // Assign courses to categories
        $pharmacistCategory->courses()->attach([$pharmacyLaw->id, $pharmacology->id, $pharmacyPractice->id, $clinicalPharmacy->id, $pharmaceuticsAndPharmaceuticalCalculation->id, $medicinesAndAlliedSubstances->id, $oralExams->id]);
        $pharmacyTechnicianCategory->courses()->attach([$pharmacyLaw->id, $pharmacologyAndPharmacyPractice->id, $pharmaceuticsAndPharmaceuticalCalculation->id, $medicinesAndAlliedSubstances->id]);
        $pharmacyAssistantCategory->courses()->attach([$pharmacyLaw->id, $pharmacologyAndPharmacyPractice->id, $pharmaceuticsAndPharmaceuticalCalculation->id, $medicinesAndAlliedSubstances->id]);

        // Unassign courses from categories
//        $pharmacistCategory->courses()->detach([$pharmacyLaw->id, $pharmacology->id, $pharmacyPractice->id, $clinicalPharmacy->id, $pharmaceuticsAndPharmaceuticalCalculation->id, $medicinesAndAlliedSubstances->id, $oralExams->id]);
    }
}
