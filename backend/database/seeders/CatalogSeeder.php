<?php

namespace Database\Seeders;

use App\Models\Commodity;
use App\Models\CommodityCategory;
use App\Models\Packing;
use App\Models\Season;
use App\Models\Service;
use App\Models\Tariff;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Packings
        foreach ([
            ['Bag 50kg', 'BAG-50', 50],
            ['Bag 25kg', 'BAG-25', 25],
            ['Crate 20kg', 'CRT-20', 20],
            ['Carton 10kg', 'CTN-10', 10],
            ['Pallet 1MT', 'PLT-1000', 1000],
            ['Big Bag 1MT', 'JMB-1000', 1000],
        ] as [$name, $code, $kg]) {
            Packing::firstOrCreate(['code' => $code], ['name' => $name, 'default_weight_kg' => $kg]);
        }

        // Categories
        $cats = [
            'Vegetables' => ['icon' => 'carrot', 'order' => 1],
            'Fruits' => ['icon' => 'apple', 'order' => 2],
            'Dairy & Frozen' => ['icon' => 'snowflake', 'order' => 3],
            'Pharma' => ['icon' => 'pill', 'order' => 4],
            'Seeds & Spices' => ['icon' => 'sprout', 'order' => 5],
        ];
        foreach ($cats as $name => $meta) {
            CommodityCategory::firstOrCreate(
                ['name' => $name],
                ['sort_order' => $meta['order']]
            );
        }

        // Commodities
        $commodities = [
            ['Vegetables', 'Potato', 'potato', '0701', 0, 2, 4, 90, 95, 240,
                'Bulk potato cold storage at –1 °C to +4 °C with 90 % humidity for season-long preservation.',
                'Potato cold storage by Varni Agro Foods — temperature-controlled, FSSAI certified, India-wide network.'],
            ['Vegetables', 'Onion', 'onion', '0703', 0, 0, 4, 65, 75, 180,
                'Onion bulk storage with controlled temperature & low humidity to prevent sprouting.', null],
            ['Fruits', 'Apple', 'apple', '0808', 0, -1, 4, 85, 90, 180,
                'Controlled-atmosphere storage for Kashmir & Himachal apples — extends shelf life up to 8 months.', null],
            ['Fruits', 'Mango', 'mango', '0804', 0, 8, 13, 85, 90, 30,
                'Pre-cooling, ripening & cold storage for Alphonso, Kesar and Banganapalli mangoes.', null],
            ['Fruits', 'Banana', 'banana', '0803', 0, 13, 14, 85, 90, 21,
                'Ripening chambers and ethylene management for banana traders.', null],
            ['Dairy & Frozen', 'Frozen Peas', 'frozen-peas', '0710', 5, -20, -18, 0, 0, 365,
                'Blast freezing and IQF storage for green peas at –20 °C.', null],
            ['Dairy & Frozen', 'Dairy Products', 'dairy', '0401', 5, 2, 4, 0, 0, 30,
                'Milk, butter, paneer, ghee — temperature & hygiene-controlled cold storage.', null],
            ['Pharma', 'Pharma & Vaccines', 'pharma', '3002', 12, 2, 8, 0, 0, 365,
                'GDP-compliant pharma cold chain (2–8 °C) with 24×7 temperature monitoring.', null],
            ['Seeds & Spices', 'Seeds', 'seeds', '1209', 0, 5, 15, 50, 60, 365,
                'Low-humidity controlled chambers for high-value seed storage.', null],
        ];

        foreach ($commodities as [$cat, $name, $slug, $hsn, $gst, $tmin, $tmax, $hmin, $hmax, $shelf, $short, $seoDesc]) {
            $catId = CommodityCategory::where('name', $cat)->value('id');
            Commodity::updateOrCreate(['slug' => $slug], [
                'category_id' => $catId,
                'name' => $name,
                'hsn_code' => $hsn,
                'gst_percent' => $gst,
                'recommended_temp_min_c' => $tmin,
                'recommended_temp_max_c' => $tmax,
                'recommended_humidity_min' => $hmin,
                'recommended_humidity_max' => $hmax,
                'shelf_life_days' => $shelf,
                'short_description' => $short,
                'seo_title' => "$name Cold Storage — Varni Agro Foods",
                'seo_description' => $seoDesc ?? "Premium $name cold storage by Varni Agro Foods Pvt. Ltd. with FSSAI-grade hygiene and 24×7 temperature monitoring.",
                'is_published' => true,
                'is_featured' => in_array($slug, ['potato', 'apple', 'mango', 'pharma']),
            ]);
        }

        // Seasons
        Season::firstOrCreate(['code' => 'POT26'], [
            'name' => 'Potato Season 2026',
            'start_date' => '2026-02-01', 'end_date' => '2026-11-30', 'is_active' => true,
        ]);
        Season::firstOrCreate(['code' => 'GEN26'], [
            'name' => 'General 2026',
            'start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'is_active' => true,
        ]);

        // Tariffs
        $bag50 = Packing::where('code', 'BAG-50')->value('id');
        $potatoSeason = Season::where('code', 'POT26')->value('id');
        $genSeason = Season::where('code', 'GEN26')->value('id');

        $tariffData = [
            ['potato', $potatoSeason, $bag50, 'cold_room', 130, 'per_bag_per_month'],
            ['onion', $genSeason, $bag50, 'cold_room', 110, 'per_bag_per_month'],
            ['apple', $genSeason, null, 'ca', 6, 'per_mt_per_day'],
            ['mango', $genSeason, null, 'cold_room', 4, 'per_mt_per_day'],
            ['frozen-peas', $genSeason, null, 'freezer', 8, 'per_mt_per_day'],
            ['pharma', $genSeason, null, 'cold_room', 12, 'per_mt_per_day'],
        ];
        foreach ($tariffData as [$slug, $sid, $pid, $ctype, $rate, $basis]) {
            $cid = Commodity::where('slug', $slug)->value('id');
            if ($cid) {
                Tariff::updateOrCreate(
                    ['commodity_id' => $cid, 'season_id' => $sid, 'chamber_type' => $ctype, 'packing_id' => $pid],
                    ['rate' => $rate, 'basis' => $basis, 'min_charge' => 0, 'advance_percent' => 25, 'is_active' => true]
                );
            }
        }

        // Services
        $services = [
            ['Cold Storage', 'cold-storage', 'snowflake',
                'Temperature-controlled storage from –25 °C to +15 °C for fruits, vegetables, dairy and pharma.',
                'Multi-chamber, multi-temperature cold storage with 24×7 monitoring and FSSAI/HACCP compliance.'],
            ['Controlled Atmosphere (CA)', 'controlled-atmosphere', 'wind',
                'Oxygen, CO₂ and ethylene-controlled chambers — extends apple and pear shelf life up to 8 months.',
                null],
            ['Blast Freezing', 'blast-freezing', 'snowflake',
                'Rapid –40 °C blast freezing locks freshness for IQF vegetables and seafood.',
                null],
            ['Ripening Chambers', 'ripening-chambers', 'leaf',
                'Ethylene-managed ripening chambers for banana, mango and other climacteric fruits.',
                null],
            ['Logistics & Reefer Transport', 'reefer-transport', 'truck',
                'Door-to-door reefer trucks with GPS and temperature loggers.',
                null],
            ['Pre-cooling & Sorting', 'precooling-sorting', 'thermometer',
                'Forced-air pre-cooling, sorting, grading and re-bagging value-added services.',
                null],
        ];
        foreach ($services as [$name, $slug, $icon, $short, $body]) {
            Service::updateOrCreate(['slug' => $slug], [
                'name' => $name,
                'icon' => $icon,
                'short_description' => $short,
                'body_html' => $body ? "<p>{$body}</p>" : "<p>{$short}</p>",
                'seo_title' => "$name Service — Varni Agro Foods",
                'seo_description' => $short,
                'is_published' => true,
            ]);
        }

        // FAQs
        $faqs = [
            ['general', 'What is the temperature range Varni Agro Foods can offer?', 'Our facilities operate chambers from –25 °C up to +15 °C, including controlled-atmosphere and ripening rooms.'],
            ['general', 'Are your facilities FSSAI certified?', 'Yes, every Varni facility is FSSAI registered and HACCP audited annually.'],
            ['general', 'How is the rent calculated?', 'Rent is calculated either per bag per month, per metric tonne per day, or per season — based on the commodity, chamber and tariff agreed at booking.'],
            ['booking', 'How do I book storage space?', 'Use the "Get Quote" form on our website, call our 24×7 helpline, or self-book through the client portal once registered.'],
            ['booking', 'What documents are required?', 'KYC: Aadhaar/PAN for individuals; GST + PAN for firms. FSSAI is required if you trade food commodities.'],
            ['operations', 'Can I track my stock online?', 'Yes — log in to the client portal to see live stock, location, gate movements, invoices and temperature logs.'],
            ['payment', 'What payment methods do you accept?', 'Cash, cheque, NEFT/RTGS, UPI and online card/UPI via Razorpay.'],
        ];
        foreach ($faqs as [$grp, $q, $a]) {
            Faq::firstOrCreate(['group' => $grp, 'question' => $q], ['answer' => $a, 'is_published' => true]);
        }

        // Testimonials
        $tms = [
            ['Rameshbhai Patel', 'Director', 'Patel Agri Exports', 'Varni\'s Morbi facility has been our trusted partner for three potato seasons. Stock is always intact and bills are transparent.', 5],
            ['Dr. Anita Sharma', 'Supply Chain Head', 'NorthCare Pharma', 'The 2–8 °C pharma chamber and the 24×7 temperature dashboard give us real peace of mind for our vaccine stock.', 5],
            ['Suresh Reddy', 'Trader', 'Reddy Fruits', 'CA storage at Varni doubled the shelf life of my apple stock — I could sell well into the off-season at premium prices.', 5],
        ];
        foreach ($tms as [$author, $role, $company, $quote, $rating]) {
            Testimonial::firstOrCreate(['author' => $author], ['role' => $role, 'company' => $company, 'quote' => $quote, 'rating' => $rating, 'is_published' => true]);
        }
    }
}
