<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            return;
        }

        $sampleContent = [
            'Board Game' => 'Peluncuran board game terbaru dengan mekanik inovatif yang menggabungkan strategi dan elemen hiburan. Game ini telah mendapat sambutan positif dari komunitas boardgame dan menjadi trending topic di berbagai forum gaming.',
            'Card Game' => 'Turnamen card game internasional menarik ribuan peserta dari seluruh dunia. Para pemain bersaing untuk meraih hadiah utama sambil menampilkan skill dan strategi mereka dalam memainkan card game legendaris.',
            'Tabletop RPG' => 'Komunitas Tabletop RPG di Indonesia terus berkembang dengan bermunculannya campaign baru yang kreatif. Pemain Indonesia menunjukkan dedikasi tinggi dalam mengeksplorasi dunia fantasi dan petualangan yang mendebarkan.',
            'Miniature Wargaming' => 'Kompetisi miniature wargaming regional menampilkan karyai painted miniatures yang memukau dari berbagai peserta. Seni melukis miniatur semakin diapresiasi sebagai bentuk ekspresi kreatif yang tinggi dalam komunitas gaming.',
            'Adventure Game' => 'Game petualangan terbaru menawarkan pengalaman immersive dengan grafis memukau dan cerita yang menarik. Para pemain dapat menjelajahi dunia luas dengan kebebasan penuh dan membuat pilihan yang mempengaruhi alur cerita.',
            'Strategy Game' => 'Strategi kompetitif dalam turnamen strategy game menunjukkan tingkat kompleksitas dan kedalaman gameplay yang luar biasa. Para pemain elite mempertunjukkan taktik canggih dan pemahaman mendalam tentang mekanik game.',
        ];

        foreach ($categories as $cat) {
            for ($i = 1; $i <= 2; $i++) {
                $title = $cat->nama_kategori . ': Berita Terkini ' . $i;
                $content = $sampleContent[$cat->nama_kategori] ?? 'Konten berita contoh untuk kategori ' . $cat->nama_kategori;
                
                Post::create([
                    'category_id' => $cat->id,
                    'title' => $title,
                    'slug' => \Illuminate\Support\Str::slug($title) . '-' . $i,
                    'image' => null,
                    'content' => $content,
                    'author' => 'Redaksi Portal Berita',
                    'published_at' => now()->subDays($i),
                ]);
            }
        }
    }
}
