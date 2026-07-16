<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_name',               'value' => "Assam Nurses' Midwives' & Health Visitors' Council", 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline',             'value' => 'Official Website of ANMC — Government of Assam',     'type' => 'text', 'group' => 'general', 'label' => 'Tagline'],
            ['key' => 'established_year',         'value' => '1944',                                               'type' => 'text', 'group' => 'general', 'label' => 'Established Year'],
            // Contact
            ['key' => 'contact_address',          'value' => 'Assam Nurses\' Midwives\' & Health Visitors\' Council, Anandaram Borooah Road, Uzanbazar, Guwahati, Assam — 781 001', 'type' => 'text', 'group' => 'contact', 'label' => 'Office Address'],
            ['key' => 'contact_phone',            'value' => '+91-361-2730XXX',                                   'type' => 'text', 'group' => 'contact', 'label' => 'Phone'],
            ['key' => 'contact_email',            'value' => 'anmc@assam.gov.in',                                 'type' => 'text', 'group' => 'contact', 'label' => 'General Email'],
            ['key' => 'contact_registrar_email',  'value' => 'registrar@anmc.assam.gov.in',                       'type' => 'text', 'group' => 'contact', 'label' => 'Registrar Email'],
            ['key' => 'office_hours',             'value' => 'Monday to Friday: 10:00 AM – 5:00 PM',             'type' => 'text', 'group' => 'contact', 'label' => 'Office Hours'],
            ['key' => 'contact_map_embed',        'value' => '',                                                  'type' => 'text', 'group' => 'contact', 'label' => 'Google Maps Embed URL'],
            // Social
            ['key' => 'social_facebook',          'value' => '',                                                  'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'social_twitter',           'value' => '',                                                  'type' => 'text', 'group' => 'social', 'label' => 'Twitter URL'],
            // External Links
            ['key' => 'link_inc',                 'value' => 'https://www.indiannursingcouncil.org',             'type' => 'text', 'group' => 'links', 'label' => 'Indian Nursing Council'],
            ['key' => 'link_gov_assam',           'value' => 'https://assam.gov.in',                             'type' => 'text', 'group' => 'links', 'label' => 'Govt of Assam'],
            ['key' => 'link_dhs_assam',           'value' => 'https://dhs.assam.gov.in',                         'type' => 'text', 'group' => 'links', 'label' => 'DHS Assam'],
            ['key' => 'link_dme_assam',           'value' => 'https://dme.assam.gov.in',                         'type' => 'text', 'group' => 'links', 'label' => 'DME Assam'],
            ['key' => 'link_ssuhs',               'value' => 'https://www.ssuhs.in',                             'type' => 'text', 'group' => 'links', 'label' => 'SSUHS'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
