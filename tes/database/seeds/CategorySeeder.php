<?php

use App\Models\Admin\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pertanyaan = collect([
            'Urutkan tugas yang paling anda senangi!',
            'Jika anda diberikan kesempatan untuk bekerja di perusahaan automotive, urutkan bidang dari yang paling anda senangi',
            'Jika anda ke toko buku, urutkan buku apa yang akan anda beli!',
            'Jika anda diberi kesempatan untuk memilih profesi di bawah ini,',
            'Apabila anda terlibat dalam suatu acara kampus,',
        ]);
        $pertanyaan->each(function ($c) {
            Category::create([
                'name' => $c,
                'slug' => Str::slug($c)
            ]);
        });
    }
}
