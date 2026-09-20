<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Item;
use App\Models\ItemPricingRule;
use Illuminate\Support\Facades\DB;

class PharmacyDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $departments = [
                'المعامل' => Department::firstOrCreate(['name' => 'المعامل']),
                'وحدة القياسات الدقيقة' => Department::firstOrCreate(['name' => 'وحدة القياسات الدقيقة']),
                'وحدة بحوث التقنية الحيوية' => Department::firstOrCreate(['name' => 'وحده بحوث التقنية الحيوية والدلالات المرضية']),
            ];

           
            $labItems = [
                ['name' => 'X-ray film', 'type' => 'sale', 'rules' => [['unit' => 'PIECE 4*8 Cm', 'price' => 25]]],
                ['name' => 'TEMED', 'type' => 'sale', 'rules' => [['unit' => '10 µl', 'price' => 30]]],
                ['name' => 'PVDF membrane', 'type' => 'sale', 'rules' => [['unit' => 'PIECE 4*8 Cm', 'price' => 25]]],
                ['name' => 'Normal saline', 'type' => 'sale', 'rules' => [['unit' => '100 ml', 'price' => 20]]],
                ['name' => 'Phosphate buffer - any pH', 'type' => 'sale', 'rules' => [['unit' => '100 ml', 'price' => 40]]],
                ['name' => 'Phosphate buffered Saline (PBS- any pH)', 'type' => 'sale', 'rules' => [['unit' => '100 ml', 'price' => 50]]],
                ['name' => '4-10 % Formalin', 'type' => 'sale', 'rules' => [['unit' => '10 ml', 'price' => 20]]],
                ['name' => 'Sodium Hydroxide 0.4N', 'type' => 'sale', 'rules' => [['unit' => '100ml', 'price' => 30]]],
                ['name' => 'Methanol -HPLC', 'type' => 'sale', 'rules' => [['unit' => '5ml', 'price' => 10]]],
                ['name' => 'Acetonitrile -HPLC', 'type' => 'sale', 'rules' => [['unit' => '5 ml', 'price' => 10]]],
                ['name' => 'Gloves', 'type' => 'sale', 'rules' => [['unit' => 'Pair', 'price' => 5]]],
                ['name' => 'Eppendorfs', 'type' => 'sale', 'rules' => [['unit' => '10 units', 'price' => 10]]],
                ['name' => 'Pipette tips (yellow)', 'type' => 'sale', 'rules' => [['unit' => '10 tips', 'price' => 5]]],
                ['name' => 'Pipette tips (blue)', 'type' => 'sale', 'rules' => [['unit' => '10 tips', 'price' => 5]]],
                ['name' => 'Falcon tubes 15ml', 'type' => 'sale', 'rules' => [['unit' => 'Tube', 'price' => 5]]],
                ['name' => 'Falcon tubes 50 ml', 'type' => 'sale', 'rules' => [['unit' => 'Tube', 'price' => 6]]],
                ['name' => 'Microtitre plate', 'type' => 'sale', 'rules' => [['unit' => 'Plate', 'price' => 30]]],
                ['name' => 'Total phenolic content F-C reagent', 'type' => 'rental', 'rules' => [['unit' => 'Sample', 'price' => 200, 'condition' => '+st.curve']]],
                ['name' => 'Total protein direct method using nanodrop', 'type' => 'rental', 'rules' => [['unit' => 'Sample', 'price' => 30]]],
            ];

            $this->seedItems($departments['المعامل']->id, $labItems);

            
            $bioTechItems = [
                ['name' => 'Spectrophotometer(cuvette)', 'brand' => 'JENWAY-PRC-UK', 'model' => 'GENOVA', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 150, 'min' => 0.5]]],
                ['name' => 'Multi-Mode Microplate Reader (ELISA)', 'brand' => 'Biotek -USA', 'model' => 'SYNERGY HT', 'type' => 'rental', 'rules' => [['unit' => 'Run - Absorbance', 'price' => 100], ['unit' => 'Run - Floursance', 'price' => 150]]],
                ['name' => 'Microvolume spectrophotometer (Nanodrop)', 'brand' => 'UK -Cole Parmer', 'model' => '747501', 'type' => 'rental', 'rules' => [['unit' => 'One sample', 'price' => 30]]],
                ['name' => 'Vertical gel electrophoresis', 'brand' => 'Cleaver scientific and Lab net international - Tiwan', 'model' => 'Cleaver scientific with ENDURU power supply EO303', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 100, 'min' => 0.5]]],
                ['name' => 'Horizontal gel electrophoresis', 'brand' => 'Cleaver Scientific - UK', 'model' => 'CS 300 - V', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 100, 'min' => 0.5]]],
                ['name' => 'iBright Imaging System', 'brand' => 'Invitrogen by Therm-scientific -Singapore', 'model' => 'CL750', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 130, 'min' => 0.5]]],
                ['name' => 'Real-Time PCR System', 'brand' => 'Agilent Technologies - Germany', 'model' => 'Stratagene Mx3000P', 'type' => 'rental', 'rules' => [['unit' => 'Run', 'price' => 400]]],
                ['name' => 'Centrifuge (Eppindorf /cooling)', 'brand' => 'Hettich Zentrifugen-Germany', 'model' => 'MIKRO 12-24', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 100]]],
                ['name' => 'Centrifuge cooling', 'brand' => 'Hermle - Germany', 'model' => 'Z326K', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 100, 'min' => 0.5]]],
                ['name' => 'T-shaker (incubator)', 'brand' => 'Euro Clone- China', 'model' => 'T-shaker', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Floor-standing Cryostat', 'brand' => 'Nikon Healthcare Business – Microscope Solutions - Germany', 'model' => 'SLEE mev', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50], ['unit' => 'day', 'price' => 200]]],
                ['name' => 'Ultra-low temperature freezer -80°C', 'brand' => 'Binder - Germany', 'model' => 'UFV 500-UL', 'type' => 'rental', 'rules' => [['unit' => 'box/ month', 'price' => 80]]],
                ['name' => 'Fridge', 'brand' => 'Toshiba - Egypt', 'model' => 'Toshiba', 'type' => 'rental', 'rules' => [['unit' => 'box /month', 'price' => 50]]],
                ['name' => 'Laboratory freezer -20°C', 'brand' => 'Frimed - Italy', 'model' => 'CV6', 'type' => 'rental', 'rules' => [['unit' => 'box / month', 'price' => 50]]],
                ['name' => 'Optical microscope', 'brand' => 'Optika microscopes - ITALY', 'model' => 'B-350', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 80]]],
                ['name' => 'Fluorescence Microscope', 'brand' => 'Leica - USA', 'model' => 'DM500', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 200]]],
                ['name' => 'Ultra sonic cleaner', 'brand' => 'Germany-Memmert GmbH + Co. KG', 'model' => 'DSD 150A1Q', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50, 'min' => 0.5]]],
                ['name' => 'Homogenizer', 'brand' => 'Cole Parmer', 'model' => 'Lab gen 7', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 80]]],
                ['name' => 'pH-meter 1', 'brand' => 'Jenway - UK', 'model' => 'Jenway 3510', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 40], ['unit' => 'one temporary use', 'price' => 10]]],
                ['name' => 'Ice maker', 'brand' => 'Whirlpool - Italy', 'model' => 'AGH327 40Kg - SCI 30', 'type' => 'sale', 'rules' => [['unit' => '1L jar', 'price' => 25]]],
                ['name' => 'Double water distillator', 'brand' => 'Bibby Scientific- England', 'model' => 'Stuart A4000D Aquatron Water Still', 'type' => 'sale', 'rules' => [['unit' => 'L', 'price' => 25]]],
                ['name' => 'Very ultra-pure Deionized water', 'brand' => 'Thermo Scientific - Sweden', 'model' => 'Smart 2 pure', 'type' => 'sale', 'rules' => [['unit' => 'L', 'price' => 30]]],
                ['name' => 'Oven 1', 'brand' => 'Egypt', 'model' => 'PS.3A', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 20]]],
                ['name' => 'Hotplate and stirrer', 'brand' => 'ISR-KOREA', 'model' => 'ISHS-180', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 80, 'min' => 0.5]]],
                ['name' => 'Sensitive balance 1', 'brand' => 'Ohaus - Canada', 'model' => 'MCT 500- Diamond', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Sensitive balance 2', 'brand' => 'Sartorius - Germany', 'model' => 'TE214 S', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Analytical balance 1', 'brand' => 'METTLER TOLEDO -SWITZERLAND', 'model' => 'AB545', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Analytical balance 2', 'brand' => 'ADAM -UK', 'model' => 'PW214', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Microwave oven', 'brand' => 'Samsung', 'model' => 'MW83Z', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Vortex mixer 1', 'brand' => 'Velp scientifica', 'model' => 'RX3', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50]]],
                ['name' => 'Lab use', 'type' => 'rental', 'rules' => [['unit' => 'day', 'price' => 150], ['unit' => 'week', 'price' => 600], ['unit' => 'month', 'price' => 1500]]],
            ];

            $this->seedItems($departments['وحدة بحوث التقنية الحيوية']->id, $bioTechItems);

           
            $precisionItems = [
                ['name' => 'HPLC -PDA 1', 'brand' => 'Agilent technologies -(USA)', 'model' => 'Agilent 1200 series', 'type' => 'rental', 'rules' => [['unit' => 'sample', 'price' => 150], ['unit' => 'hr', 'price' => 450, 'min' => 0.5], ['unit' => 'one day', 'price' => 1500]]],
                ['name' => 'HPLC -PDA 2', 'brand' => 'Thermo scientific - (USA)', 'model' => 'Finnigan Surveyor PDA Plus Detector', 'type' => 'rental', 'rules' => [['unit' => 'sample', 'price' => 150], ['unit' => 'hr', 'price' => 450, 'min' => 0.5], ['unit' => 'one day', 'price' => 1500]]],
                ['name' => 'Double beam Spectrophotometer', 'brand' => 'Jenway-UK', 'model' => 'Jenway 6800 UV/VIS double beam', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 150], ['unit' => 'day', 'price' => 400]]],
                ['name' => 'Gas chromatography', 'brand' => 'Thermo scientific -(USA)', 'model' => 'K0733B82', 'type' => 'rental', 'rules' => [['unit' => 'sample', 'price' => 75]]],
                ['name' => 'Elemental analyzer', 'brand' => 'Thermo scientific -(Italy)', 'model' => 'FLASH-2000', 'type' => 'rental', 'rules' => [['unit' => 'sample (C,H)', 'price' => 150], ['unit' => 'sample (C,H,N)', 'price' => 250]]],
                ['name' => 'Cooling centrifuge', 'brand' => 'Germany - Hermle', 'model' => 'Z300 K', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 100, 'min' => 0.5]]],
                ['name' => 'Centrifuge room temperature', 'brand' => 'Hettich Zentrifugen-Germany', 'model' => 'Universal 320/320R', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 80, 'min' => 0.5]]],
                ['name' => 'Vacuum freeze dryer (Lyophilizer)', 'brand' => 'Taisite- China', 'model' => 'LY -10N series-Taisite', 'type' => 'rental', 'rules' => [
                    ['unit' => 'hr', 'price' => 100, 'condition' => 'Sample volume up to 100 ml (1-5 hrs)'],
                    ['unit' => 'hr', 'price' => 80, 'condition' => 'Sample volume up to 100 ml (6-10 hrs)'],
                    ['unit' => 'hr', 'price' => 60, 'condition' => 'Sample volume up to 100 ml (>10 hrs)'],
                    ['unit' => 'hr', 'price' => 200, 'condition' => 'Sample volume > 100 - 500 ml (1-5 hrs)'],
                    ['unit' => 'hr', 'price' => 160, 'condition' => 'Sample volume > 100 - 500 ml (6-10 hrs)'],
                    ['unit' => 'hr', 'price' => 120, 'condition' => 'Sample volume > 100 - 500 ml (>10 hrs)'],
                    ['unit' => 'hr', 'price' => 300, 'condition' => 'Sample volume > 500 ml - 2 L (1-5 hrs)'],
                    ['unit' => 'hr', 'price' => 250, 'condition' => 'Sample volume > 500 ml - 2 L (6-10 hrs)'],
                    ['unit' => 'hr', 'price' => 200, 'condition' => 'Sample volume > 500 ml - 2 L (>10 hrs)'],
                ]],
                ['name' => 'Fume Cupboard', 'brand' => 'Lab Tech - Korea', 'model' => 'LEH-120SCI/ LEH-150SCI/LEH-180SCI', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50, 'min' => 0.5], ['unit' => 'day', 'price' => 150, 'condition' => 'maximum 6hrs'], ['unit' => 'overnight', 'price' => 200]]],
                ['name' => 'Rotary vacuum concentrator with pump', 'brand' => 'IKA- Germany', 'model' => '2XZ-2', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 80, 'min' => 0.5], ['unit' => 'day', 'price' => 300]]],
                ['name' => 'Evaporator / concentrator N2', 'brand' => 'Cole Parmer instrument company - USA', 'model' => 'Cole –Parmer Z1800', 'type' => 'sale', 'rules' => [['unit' => 'ml', 'price' => 20]]],
                ['name' => 'Laboratory centrifuge with vacuum system', 'brand' => 'Accu lab - USA', 'model' => 'CE-2 6X50V', 'type' => 'rental', 'rules' => [['unit' => 'Run', 'price' => 100]]],
                ['name' => 'Electric balance', 'brand' => 'Sartorius - Germany', 'model' => 'TE2145', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50, 'min' => 0.5]]],
                ['name' => 'Portable pH/mV/°C meter', 'brand' => 'HANA instruments - Romania', 'model' => 'HI 8314', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 40], ['unit' => 'one temporary use', 'price' => 10]]],
                ['name' => 'pH-meter 2', 'brand' => 'Jenway - UK', 'model' => '3510', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 40], ['unit' => 'one temporary use', 'price' => 10]]],
                ['name' => 'Oven 2', 'brand' => '3A company - Egypt', 'model' => '3A – A202', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50, 'min' => 0.5]]],
                ['name' => 'vortex mixer 2', 'brand' => 'Labnet International - USA', 'model' => 'S0200', 'type' => 'rental', 'rules' => [['unit' => 'hr', 'price' => 50, 'min' => 0.5]]],
                ['name' => 'Lab use', 'type' => 'rental', 'rules' => [['unit' => 'day', 'price' => 150], ['unit' => 'week', 'price' => 600], ['unit' => 'month', 'price' => 1500]]],
            ];

            $this->seedItems($departments['وحدة القياسات الدقيقة']->id, $precisionItems);
        });
    }

    private function seedItems(int $departmentId, array $items): void
    {
        foreach ($items as $itemData) {
            $item = Item::firstOrCreate([
                'department_id' => $departmentId,
                'name' => $itemData['name'],
                'type' => $itemData['type'],
                'brand' => $itemData['brand'] ?? null,
                'model' => $itemData['model'] ?? null,
                'stock_quantity' => $itemData['type'] === 'sale' ? 100 : 1, 
            ]);

            foreach ($itemData['rules'] as $rule) {
                ItemPricingRule::firstOrCreate([
                    'item_id' => $item->id,
                    'unit_type' => $rule['unit'],
                    'price' => $rule['price'],
                    'min_duration' => $rule['min'] ?? null,
                    'condition_text' => $rule['condition'] ?? null,
                ]);
            }
        }
    }
}