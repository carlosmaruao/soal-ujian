<?php

use App\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //soal 1
        $soal1 = collect([
            'Menjadi penghubung perusahaan dengan masyarakat',
            'Menjadi seorang penyiar dan menyusun konten berita',
            'Menjadi pemasar produk perusahaan',
            'Menjadi pembuat/perancang konsep visual dan gambar produk',
            'Menjadi pembuat naskah cerita drama/film',
            'Menjadi juru bicara internasional',
            'Menjadi penggagas ide-ide / terobosan-terobosan baru',
            'Menjadi travel consultant'
        ]);

        $soal1->each(function ($c, $e) {
            Question::create([
                'category_id' => 1,
                'major_id' => $e + 1,
                'title' => $c,
                'slug' => Str::slug($c)
            ]);
        });

        //soal 2
        $soal2 = collect([
            'Pembuat iklan mobil',
            'Melakukan peliputan event penjualan mobil',
            'Mengatur strategi penjualan mobil',
            'Pembuat acara untuk menghibur sekaligus memperkuat kesadaran merk mobil',
            'Bekerjasama dengan perusahaan asing/luar untuk mendukung penjualan mobil ',
            'Menginformasikan kepada masyarakat mengenai event mobil yang akan diselenggarakan',
            'Pengembangan usaha perusahaan',
            'Mengadakan pameran mobil dengan bekerjasama dengan hotel-hotel ternama',
        ]);
        $soal2->each(function ($c, $e) {
            Question::create([
                'category_id' => 2,
                'major_id' => $e + 1,
                'title' => $c,
                'slug' => Str::slug($c)
            ]);
        });
        //soal 3
        $soal3 = collect([
            'Buku tentang membentuk citra diri dan produk perusahaan',
            'Buku jurnalistik (perkembangan media digital)',
            'Buku strategi memasarkan  dan mengembangkan produk',
            'Buku yang memiliki gambar-gambar ilustrasi yang menarik ',
            'Buku yang memiliki gambar-gambar ilustrasi yang menarik ',
            'Buku tentang hubungan antar lembaga/ egara dalam dan luar negeri',
            'Buku kiat sukses mengembangkan ide dan inovasi',
            'Buku tentang traveling dan pariwisata',
        ]);
        $soal3->each(function ($c, $e) {
            Question::create([
                'category_id' => 3,
                'major_id' => $e + 1,
                'title' => $c,
                'slug' => Str::slug($c)
            ]);
        });
        //soal 4
        $soal4 = collect([
            'Humas dari Perusahaan',
            'News Anchor/pembaca berita di TV',
            'Kreator Pemasaran Produk',
            'Ilustrator/ pembuat ilustrasi dan merancang konsep kreatif (digital)',
            'Pembuat Konsep /Konseptor/Kreator Event Pertunjukan',
            'Diplomat',
            'Entrepreneurship/pengusaha',
            'Travel Consultant',
        ]);
        $soal4->each(function ($c, $e) {
            Question::create([
                'category_id' => 4,
                'major_id' => $e + 1,
                'title' => $c,
                'slug' => Str::slug($c)
            ]);
        });
        //soal 5
        $soal5 = collect([
            'Sebagai Pembawa Acara /MC',
            'Sebagai Tim Peliputan dan Mengolah informasi melalui media',
            'Sebagai Panitia yang menyusun konsep strategi pemasaran produk/jasa',
            'Tim kreatif yang merancang desain poster, spanduk acara dan mahir menggunakan platform digital untuk display rancangan design',
            'Sebagai Pemain dan penyusun konsep pertunjukan atau Seni',
            'Sebagai tim yang membina hubungan dengan lembaga international, interpreter dan terkait isu diplomatik',
            'Sebagai Inovator berbagai ide kreatif dari acara yang dilaksanakan',
            'Sebagai tim yang mengakomodir kebutuhan tamu undangan,terkait transportasi, penginapan (hotel), dll.',
        ]);
        $soal5->each(function ($c, $e) {
            Question::create([
                'category_id' => 5,
                'major_id' => $e + 1,
                'title' => $c,
                'slug' => Str::slug($c)
            ]);
        });
    }
}
