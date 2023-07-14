<?php

namespace Database\Seeders;

use App\Models\Ailment;
use App\Models\COE;
use App\Models\GeopoliticalZone;
use App\Models\IdentificationDocument;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\State;
use App\Models\User;
use App\Models\Wallet;
use Database\Factories\RoleFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        COE::create([
            'id' => 1,
            'coe_id_cap'=>"ABV",
            'serial_number' => 9504,
            'coe_name' => 'National Hospital Abuja (NHA)',
            'coe_type' => 'Federal',
            'coe_address' => "265 Independence Avenue, Central Business District, FCT",
            'state_id' => 37,
        ]);
        COE::create([
            'id' => 2,
            'coe_id_cap'=>"FTHG",
                    'serial_number' => 3584,
                    'coe_name' => 'Federal Teaching Hospital Gombe (FTH)',
                    'coe_type' => 'Federal',
                    'coe_address' => "Federal teaching hospital road, Gombe",
                    'state_id' => 12,
                ]);
        COE::create([
            'id' => 3,
            'coe_id_cap'=>"ZAR",
                    'serial_number' => 0404,
                    'coe_name' => 'Ahmadu Bello University Teaching Hospital (ABUTH)',
                    'coe_type' => 'Federal',
                    'coe_address' => "Shika, Zaria - Sokoto Road, Zaria",
                    'state_id' => 23,
                ]);
        COE::create([
            'id' => 4,
            'coe_id_cap'=>"UBTH",
            'serial_number' => 0445,
            'coe_name' => 'University of Benin Teaching Hospital (UBTH)',
            'coe_type' => 'Federal',
            'coe_address' => "Ugbowo, Lagos - Benin Expressway, Benin City",
            'state_id' => 18,
        ]);
        COE::create([
            'id' => 5,
            'coe_id_cap'=>"UNTH",
            'serial_number' => 1404,
            'coe_name' => 'University of Nigeria Teaching Hospital (UNTH)',
            'coe_type' => 'Federal',
            'coe_address' => "Ikuku-Ozala, Enugu-Portharcourt Expressway",
            'state_id' => 1,
        ]);
        COE::create([
            'id' => 6,
            'coe_id_cap'=>"UCTH",
            'serial_number' => 5904,
            'coe_name' => 'University of Calabar Teaching Hospital (UCTH)',
            'coe_type' => 'Federal',
            'coe_address' => "Court Road, Duke Town",
            'state_id' => 22,
        ]);
        COE::create([
            'id' => 7,
            'coe_id_cap'=>"UCH",
            'serial_number' => 0304,
            'coe_name' => 'University College Hospital (UCH)',
            'coe_type' => 'Federal',
            'coe_address' => "Queen Elizabeth Road, Oritamefa, Ibadan",
            'state_id' => 7,
        ]);


        /* START SERVICE CATEGORIES */
        ServiceCategory::create([
            'category_code' => 'RCN',
            'category_name' => 'Registration and Consultancy',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'LAB',
            'category_name' => 'Laboratory Services',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'RAD1',
            'category_name' => 'Radiological Services',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'MED',
            'category_name' => 'Medications',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'ADM',
            'category_name' => 'Admission Fees',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'RAD2',
            'category_name' => 'Radiotherapy Services',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'SUP',
            'category_name' => 'Surgical Procudures',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'PAI',
            'category_name' => 'Pathological Investigations',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'NUM',
            'category_name' => 'Nuclear Medicine',
            'created_by' => 1
        ]);
        
        ServiceCategory::create([
            'category_code' => 'OPS',
            'category_name' => 'Other Procedures/Supportive Care',
            'created_by' => 1
        ]);
        /* END SERVICE CATEGORIES */

        

        Service::create([
            'service_code' => "REG",
            'service_name' => 'Registration',
            'service_category_id' => 1,
            'price' => 37890,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CON",
            'service_name' => 'Consultation',
            'service_category_id' => 1,
            'price' => 4700,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "FBC",
            'service_name' => 'Full Blood Count and Differentials',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);        

        Service::create([
            'service_code' => "URE",
            'service_name' => 'Urea and Electrolytes',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);

        Service::create([
            'service_code' => "SCT",
            'service_name' => 'Serum Creatinine',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);        


        Service::create([
            'service_code' => "RVS",
            'service_name' => 'RVS',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);

        Service::create([
            'service_code' => "LFT",
            'service_name' => 'Liver Function Tests',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);

        Service::create([
            'service_code' => "SPT",
            'service_name' => 'Serum Protein',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);

        Service::create([
            'service_code' => "SCP",
            'service_name' => 'Serum Calcium and Phosphate',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PSA",
            'service_name' => 'PSA',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "STT",
            'service_name' => 'Serum Testoterone',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CA1",
            'service_name' => 'CA15-3',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "ECH",
            'service_name' => 'ECHO',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "ECG",
            'service_name' => 'ECG',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "24C",
            'service_name' => '24 Hrs Creatinine Clearance',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CLP",
            'service_name' => 'Clotting Profile',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BMCS",
            'service_name' => 'Blood m/c/s',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "UMCS",
            'service_name' => 'Urine m/c/s',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "SMCS",
            'service_name' => 'Swab m/c/s',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BMB",
            'service_name' => 'Bone Marrow Biopsy',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OTH1",
            'service_name' => 'Others',
            'service_category_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CXP1",
            'service_name' => 'Chest XRay (PA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CXP2",
            'service_name' => 'Chest XRay (PA & LA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "XLX1",
            'service_name' => 'Xray Lumboscaral Spine (PA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "XLS2",
            'service_name' => 'Xray Lumboscaral Spine (PA & LA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "XSK1",
            'service_name' => 'Xray Skul (PA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "XSK2",
            'service_name' => 'Xray Skul (PA & LA)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PXO",
            'service_name' => 'Plain Xray of Other Sites (Per Single Field)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "USP",
            'service_name' => 'Ultrasound Pelvis',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "USA",
            'service_name' => 'Ultrasound Abdomen',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BUS",
            'service_name' => 'Breast Ultrasound (Single/Both)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "USS",
            'service_name' => 'Ultrasound of Specific Sites',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "TRU",
            'service_name' => 'TRUSS',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MAM",
            'service_name' => 'Mammography',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MRI",
            'service_name' => 'MRI (Pelvis/Abdomen/Brain/Spines/Other Sites)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CTS",
            'service_name' => 'CT Scan (Brain/Chest/Abdomen/Pelvis/Spines/Others)',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "IRA",
            'service_name' => 'Intervention Radiology',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "IVU",
            'service_name' => 'IVU',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "USG",
            'service_name' => 'Ultrasound Guided Biopsy',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "DPS",
            'service_name' => 'Doppler Scan',
            'service_category_id' => 3,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PMC",
            'service_name' => 'Pre-medication for Chemotherapy',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "STD",
            'service_name' => 'Steroids',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CHD",
            'service_name' => 'Chemotherapy Drug',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "HOT",
            'service_name' => 'Hormonal Therapy (Antagonost/Agonists of Estrogens & Androgens)
            ',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BTT",
            'service_name' => 'Bone Targetted Therapy',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "TIT",
            'service_name' => 'Targeted Immunotherapy',
            'service_category_id' => 4,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "FRE",
            'service_name' => 'Targeted Immunotherapy',
            'service_category_id' => 5,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "WPW",
            'service_name' => 'Ward Per Week',
            'service_category_id' => 5,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CTN",
            'service_name' => 'CT Simulation',
            'service_category_id' => 6,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "TTP",
            'service_name' => 'Teletherapy',
            'service_category_id' => 6,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BRT",
            'service_name' => 'Branchytherapy (HDR Cervix & Prostate)',
            'service_category_id' => 6,
            'price' => rand(5000,100000),
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MSU",
            'service_name' => 'Minor Surgery (FNAC/FNAB in Clinic)',
            'service_category_id' => 7,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MDS",
            'service_name' => 'Morderate Surgery (Tru cut biopsy/TURP/TRUSS & Biopsy)',
            'service_category_id' => 7,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MJS",
            'service_name' => 'Major Surgery (Prostatectomy, Mastectomy, TAH +BSO)',
            'service_category_id' => 7,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BSP",
            'service_name' => 'Bedside Procedure',
            'service_category_id' => 7,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "DPE",
            'service_name' => 'Drainage of Pleural Effusion, Ascites, Pyometria etc)',
            'service_category_id' => 7,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CYT",
            'service_name' => 'Cytology',
            'service_category_id' => 8,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "HIT",
            'service_name' => 'Histology',
            'service_category_id' => 8,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "IHC",
            'service_name' => 'Immunohistochemistry',
            'service_category_id' => 8,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OTH2",
            'service_name' => 'Others',
            'service_category_id' => 8,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "BSC",
            'service_name' => 'Bone Scan',
            'service_category_id' => 9,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PET",
            'service_name' => 'PET Scan',
            'service_category_id' => 9,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OTH3",
            'service_name' => 'Others',
            'service_category_id' => 9,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "NTR",
            'service_name' => 'Nutritional Rehabilitation',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "ATB",
            'service_name' => 'Antibiotics',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "AGS",
            'service_name' => 'Analgesics',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "SDT",
            'service_name' => 'Sedatives',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "ATD",
            'service_name' => 'Antithrombotic Drugs',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "TPS",
            'service_name' => 'Transport Support',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PSE",
            'service_name' => 'Physiotherapy Sessions',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "ICU",
            'service_name' => 'ICU Care',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OXT",
            'service_name' => 'Oxygen Therapy',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MGC",
            'service_name' => 'Management of Comorbidities (DM, HT, PUD, etc)',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MGC2",
            'service_name' => 'Management of Complications',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OTH4",
            'service_name' => 'Others',
            'service_category_id' => 10,
            'price' => 16384,
            'created_by' => 1
        ]);

        /* START SUB SERVICES */
        Service::create([
            'service_code' => "CVT",
            'service_name' => 'Clinic Visit',
            'service_category_id' => 1,
            'parent_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "CAF",
            'service_name' => 'Chemotherapy Admission Fee',
            'service_category_id' => 1,
            'parent_id' => 2,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "GOM",
            'service_name' => 'Granisetron',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "HYD",
            'service_name' => 'Hydrocortisone',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "DEX",
            'service_name' => 'Dexametasone',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "MAX",
            'service_name' => 'Maxolone',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OPZ",
            'service_name' => 'Omeprazole',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "RAN",
            'service_name' => 'Ranitidine',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "GCS",
            'service_name' => 'G-SCF',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "LOP",
            'service_name' => 'Loperamide',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "OTH5",
            'service_name' => 'Others',
            'service_category_id' => 4,
            'parent_id' => 42,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "RAD",
            'service_name' => 'Radical',
            'service_category_id' => 6,
            'parent_id' => 51,
            'price' => 16384,
            'created_by' => 1
        ]);
        Service::create([
            'service_code' => "PAL",
            'service_name' => 'Palliative',
            'service_category_id' => 4,
            'parent_id' => 51,
            'price' => 16384,
            'created_by' => 1
        ]);


        /* CREATE GEO ZONES */
        // foreach(Config::get('geodata') as $geozone => $states){
        //     $geo = GeopoliticalZone::create([
        //         'geopolitical_zone' => $geozone,
        //     ]);
        //     $geo_zone = GeopoliticalZone::where('geopolitical_zone',$geozone)->first();
        //     foreach($states as $state => $lgas){
        //         State::create([
        //             'state' => $state,
        //             'geopolitical_zone_id' => $geo_zone->id
        //         ]);
        //     }
        // }


        // Role::create(['role' => 'chf_admin', 'description' => 'chf_admin'])->permissions()->attach([1,2,4,5,7,8,6,10,15,13]);
        // Role::create(['role' => 'coe_admin', 'description' => 'chf_admin'])->permissions()->attach([2,6,9,5,7,8]);
        // Role::create(['role' => 'coe_staff', 'description' => 'chf_admin'])->permissions()->attach([3,4,5,8,9,10]);
        // Role::create(['role' => 'super_admin', 'description' => 'chf_admin'])->permissions()->attach([1,2,3,4,5,6,7,8,9]);
        // Role::create(['role' => 'patient', 'description' => 'chf_admin'])->permissions()->attach([1,4,5,6,2]);

        $ailment_types = ["cervical Cancer",'Prostate Cancer',"Breast Cancer"];

        foreach ($ailment_types as $ailment) {
            Ailment::create(['ailment_type' => $ailment, 'ailment_stage' => 1]);
            Ailment::create(['ailment_type' => $ailment, 'ailment_stage' => 2]);
            Ailment::create(['ailment_type' => $ailment, 'ailment_stage' => 3]);
            Ailment::create(['ailment_type' => $ailment, 'ailment_stage' => 4]);
        }

        $user = User::create([
            'first_name' => 'Chinedu',
            'last_name' => 'Ukpe',
            'date_of_birth' => '1990-01-21',
            'gender' => 'male',
            'phone_number' => '08038080619',
            'email' => 'chinedu_ukpe@outlook.com',
            'coe_id' => 1,
            'password' => Hash::make('pandora007'),
            'email_verified_at' => now(),
        ]);
        $user->patient()->create([
            'ailment_id' => 1,
            'coe_id' => 1,
            'yearly_income' => 24895,
            'identification_id' => 1,
            'identification_number' => 4894803344,
        ]);
        // $user->roles()->attach([1,2,3]);
        $user->wallet()->create([
            'is_coe' => 1,
            'coe_id'=>1
        ]);

        foreach (Config::get('permissions') as $permission) {
            Permission::create(['permission' => $permission]);
        }

        IdentificationDocument::create([
            'identification_type' => 'National ID'
        ]);

        IdentificationDocument::create([
            'identification_type' => "Voter's Card"
        ]);
        IdentificationDocument::create([
            'identification_type' => "Driving Licence"
        ]);
        IdentificationDocument::create([
            'identification_type' => "International Passport"
        ]);

        // \App\Models\User::factory(50)->hasRoles(3)->hasWallet(1)->hasPatient(1)->create();
        \App\Models\User::factory(50)->hasWallet(1)->hasPatient(1)->create();
        // \App\Models\Role::factory(4)->create();;
        // \App\Models\Permission::factory(15)->create();
        // \App\Models\UserPermission::factory(25)->create();
        // \App\Models\COE::factory(10)->create();

        Permission::create([
            'permission' => 'APPLY_FUND',
        ]);

        Permission::create([
            'permission' => 'UPDATE_PROFILE',
        ]);
        Permission::create([
            'permission' => 'LOGOUT',
        ]);
        Permission::create([
            'permission' => 'VIEW_TRANSACTION',
        ]);
        // END BASIC PERMISSIONS
        Permission::create([
            'permission' => 'VIEW_ALL_TRANSACTION',
        ]);
        Permission::create([
            'permission' => 'UPDATE_TRANSACTION',
        ]);
        Permission::create([
            'permission' => 'DELETE_TRANSACTION',
        ]);
        Permission::create([
            'permission' => 'VIEW_APPLICATION',
        ]);
        Permission::create([
            'permission' => 'APPROVE_APPLICATION',
        ]);
        Permission::create([
            'permission' => 'DELETE_APPLICATION',
        ]);

        Role::create([
            'role' => 'Patient' 
        ]);

        Role::create([
            'role' => 'COE Staff' 
        ]);

        Role::create([
            'role' => 'COE Admin' 
        ]);

        Role::create([
            'role' => 'CHF Admin' 
        ]);

        Role::create([
            'role' => 'Super Admin' 
        ]);

        Role::create([
            'role' => 'CHF Help Desk' 
        ]);

        Role::create([
            'role' => 'CHF Auditors' 
        ]);

        Role::create([
            'role' => 'CHF Approvals' 
        ]);



        Role::find(1)->permissions()->attach([1,2,3,4,39,40]);
        Role::find(2)->permissions()->attach([2,3,4,6,7,19,39,40]);
        Role::find(3)->permissions()->attach([2,3,4,5,19,32,37,39,40,41]);
        Role::find(4)->permissions()->attach([2,3,4,5,19,39,40,41,46,45,42,41,37,32,27,26,25,24,14]);
        Role::find(5)->permissions()->attach(range(1,50));
        Role::find(5)->permissions()->detach([1,6,25,26,46]);


        User::find(3)->update(['coe_id' => 1]);
        User::find(1)->roles()->attach([5]);
        User::find(2)->roles()->attach([2]);
        User::find(3)->roles()->attach([3]);
        User::find(4)->roles()->attach([4]);
        User::find(5)->roles()->attach([1]);
        User::find(3)->wallet()->update(['balance' => 2000000]);

    }
}
