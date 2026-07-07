<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        Institution::updateOrCreate(
            ['slug' => 'daarul-huffadz-balik-papan'],
            [
                'name' => 'Pondok Pesantren Daarul Huffadz Balikpapan',
                'motto' => 'Mencetak Generasi Qur\'ani, Berakhlak Mulia dan Berwawasan Global',
                'tagline' => 'Berdiri sejak 2015 di Balikpapan, kami mencetak santri mandiri, hafizh Qur\'an, berakhlak mulia, dengan jaminan 2 ijazah resmi (Negara & Pondok).',


                'vision' => implode("\n", [
                    'Menjadikan Pondok Pesantren Daarul Huffadz sebagai pusat pendidikan Islam yang unggul,',
                    'berkualitas, dan berdaya saing global dengan tetap berpegang teguh pada nilai-nilai Ahlussunnah Wal Jamaah.',
                ]),

                'mission' => implode("\n", [
                    '1. Menyelenggarakan pendidikan yang berorientasi pada pembentukan karakter santri yang beriman, bertakwa, dan berakhlak mulia.',
                    '2. Mengembangkan kurikulum yang mengintegrasikan ilmu agama dan ilmu umum secara seimbang.',
                    '3. Menciptakan lingkungan pondok yang kondusif untuk pembinaan spiritual, intelektual, dan sosial santri.',
                    '4. Mempersiapkan santri yang hafal Al-Qur\'an dan mampu mengamalkannya dalam kehidupan sehari-hari.',
                    '5. Menjalin kerjasama dengan berbagai lembaga pendidikan dan masyarakat dalam rangka pengembangan dakwah dan pendidikan.',
                ]),

                'description' => 'Pondok Pesantren Daarul Huffadz adalah lembaga pendidikan Islam yang berfokus pada pembinaan santri dalam bidang tahfidz Al-Qur\'an, pemahaman agama, dan pengembangan karakter.',

                'history' => '<p>Pondok Pesantren Daarul Huffadz didirikan pada tahun 2015 dengan tujuan mencetak generasi muslim yang hafal Al-Qur\'an dan memiliki pemahaman agama yang mendalam. Berawal dari pengajian kecil di rumah pendiri, kini berkembang menjadi pondok pesantren yang modern dan terkemuka.</p>',

                'address' => 'Jl. Flamboyan RT 64 Perum Batu Ampar Lestari, Balikpapan',
                'email' => 'info@daarulhuffadzbalikpapan.com',
                'whatsapp' => '6288247957504',
                'whatsapp_2' => '6281216140764',

                'facebook' => 'https://facebook.com/daarulhuffadz',
                'instagram' => 'https://instagram.com/daarulhuffadz',
                'youtube' => 'https://youtube.com/@daarulhuffadz',
                'twitter' => 'https://twitter.com/daarulhuffadz',
                'tiktok' => 'https://tiktok.com/@daarulhuffadz',

                'website' => 'https://daarulhuffadzbalikpapan.com',

                'meta_title' => 'Pondok Pesantren Daarul Huffadz - Pendidikan Islam Unggul',
                'meta_description' => 'Pondok Pesantren Daarul Huffadz Balikpapan menyediakan pendidikan Islam yang unggul dengan program tahfidz Al-Qur\'an dan pembinaan karakter santri.',
                'meta_keywords' => 'pondok pesantren, tahfidz, Al-Qur\'an, pendidikan Islam, santri, pesantren modern',

                'established_year' => 2015,
                'accreditation' => 'Terakreditasi Unggul',
                'is_active' => true,


                'profile_video_url' => 'https://www.youtube.com/embed/ADoo8RD8sDI?rel=0&modestbranding=1',

                'total_students' => 350,
                'total_alumni' => 180,
                'total_teachers' => 32,
            ]
        );
    }
}
