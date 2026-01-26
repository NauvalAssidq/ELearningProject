<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BankSoal;
use App\Models\User;

class BankSoalSeeder extends Seeder
{
    /**
     * Seed the Bank Soal with placement test questions.
     */
    public function run(): void
    {
        // Get the teacher user
        $teacher = User::where('email', 'teacher@techalearn.com')->first();

        if (!$teacher) {
            $this->command->error('Teacher user not found! Run php artisan db:seed first.');
            return;
        }

        $questions = [
            // EASY Questions (Pemula level)
            [
                'question_text' => 'Apa kepanjangan dari HTML?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => 'Hyper Text Markup Language', 'correct' => true],
                    ['text' => 'High Tech Modern Language', 'correct' => false],
                    ['text' => 'Hyper Transfer Markup Language', 'correct' => false],
                    ['text' => 'Home Tool Markup Language', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Tag HTML mana yang digunakan untuk membuat paragraf?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => '<p>', 'correct' => true],
                    ['text' => '<paragraph>', 'correct' => false],
                    ['text' => '<text>', 'correct' => false],
                    ['text' => '<para>', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa kepanjangan dari CSS?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => 'Cascading Style Sheets', 'correct' => true],
                    ['text' => 'Computer Style Sheets', 'correct' => false],
                    ['text' => 'Creative Style System', 'correct' => false],
                    ['text' => 'Colorful Style Sheets', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Property CSS mana yang digunakan untuk mengubah warna teks?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => 'color', 'correct' => true],
                    ['text' => 'text-color', 'correct' => false],
                    ['text' => 'font-color', 'correct' => false],
                    ['text' => 'text-style', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Tag HTML mana yang digunakan untuk menampilkan gambar?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => '<img>', 'correct' => true],
                    ['text' => '<image>', 'correct' => false],
                    ['text' => '<picture>', 'correct' => false],
                    ['text' => '<photo>', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Cara yang benar untuk menambahkan komentar di HTML adalah?',
                'difficulty' => 'easy',
                'options' => [
                    ['text' => '<!-- komentar -->', 'correct' => true],
                    ['text' => '// komentar', 'correct' => false],
                    ['text' => '/* komentar */', 'correct' => false],
                    ['text' => '# komentar', 'correct' => false],
                ],
            ],

            // MEDIUM Questions (Menengah level)
            [
                'question_text' => 'Dalam JavaScript, cara yang benar untuk mendeklarasikan variabel adalah?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'let nama = "John";', 'correct' => true],
                    ['text' => 'variable nama = "John";', 'correct' => false],
                    ['text' => 'v nama = "John";', 'correct' => false],
                    ['text' => 'string nama = "John";', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Property CSS mana yang digunakan untuk membuat tata letak flexbox?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'display: flex;', 'correct' => true],
                    ['text' => 'layout: flex;', 'correct' => false],
                    ['text' => 'flex: display;', 'correct' => false],
                    ['text' => 'flexbox: true;', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa fungsi dari document.getElementById() di JavaScript?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'Mengambil elemen HTML berdasarkan ID', 'correct' => true],
                    ['text' => 'Membuat ID baru untuk elemen', 'correct' => false],
                    ['text' => 'Menghapus elemen dengan ID tertentu', 'correct' => false],
                    ['text' => 'Mengubah ID dari elemen', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'CSS Box Model terdiri dari?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'Content, Padding, Border, Margin', 'correct' => true],
                    ['text' => 'Header, Body, Footer, Sidebar', 'correct' => false],
                    ['text' => 'Top, Right, Bottom, Left', 'correct' => false],
                    ['text' => 'Width, Height, Color, Position', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa output dari console.log(typeof []);?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'object', 'correct' => true],
                    ['text' => 'array', 'correct' => false],
                    ['text' => 'list', 'correct' => false],
                    ['text' => 'undefined', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Method array mana yang menambahkan elemen di akhir array?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => 'push()', 'correct' => true],
                    ['text' => 'add()', 'correct' => false],
                    ['text' => 'append()', 'correct' => false],
                    ['text' => 'insert()', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa perbedaan utama antara == dan === di JavaScript?',
                'difficulty' => 'medium',
                'options' => [
                    ['text' => '=== memeriksa nilai dan tipe data', 'correct' => true],
                    ['text' => 'Tidak ada perbedaan', 'correct' => false],
                    ['text' => '== lebih cepat dari ===', 'correct' => false],
                    ['text' => '=== hanya untuk string', 'correct' => false],
                ],
            ],

            // HARD Questions (Mahir level)
            [
                'question_text' => 'Apa yang dimaksud dengan "closure" di JavaScript?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Fungsi yang memiliki akses ke variabel di scope luarnya', 'correct' => true],
                    ['text' => 'Fungsi yang tidak mengembalikan nilai', 'correct' => false],
                    ['text' => 'Variabel yang tidak bisa diubah', 'correct' => false],
                    ['text' => 'Method untuk menutup browser', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa itu Event Bubbling di JavaScript?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Event merambat dari elemen target ke elemen parent', 'correct' => true],
                    ['text' => 'Event membuat animasi gelembung', 'correct' => false],
                    ['text' => 'Event yang terjadi berulang kali', 'correct' => false],
                    ['text' => 'Event yang tidak bisa dihentikan', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa fungsi async/await di JavaScript?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Menangani operasi asynchronous dengan sintaks synchronous', 'correct' => true],
                    ['text' => 'Membuat kode berjalan lebih cepat', 'correct' => false],
                    ['text' => 'Menunggu user input', 'correct' => false],
                    ['text' => 'Menghentikan eksekusi program', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa itu CSS Grid?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Sistem layout 2 dimensi untuk membuat grid rows dan columns', 'correct' => true],
                    ['text' => 'Framework CSS untuk responsif', 'correct' => false],
                    ['text' => 'Animasi untuk membuat grid', 'correct' => false],
                    ['text' => 'Plugin untuk Chrome DevTools', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa yang dimaksud dengan hoisting di JavaScript?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Deklarasi variabel dan fungsi dipindahkan ke atas scope', 'correct' => true],
                    ['text' => 'Proses mengoptimasi kode', 'correct' => false],
                    ['text' => 'Menaikkan performa website', 'correct' => false],
                    ['text' => 'Teknik untuk mengangkat elemen DOM', 'correct' => false],
                ],
            ],
            [
                'question_text' => 'Apa itu REST API?',
                'difficulty' => 'hard',
                'options' => [
                    ['text' => 'Arsitektur untuk komunikasi client-server menggunakan HTTP methods', 'correct' => true],
                    ['text' => 'Database untuk menyimpan data', 'correct' => false],
                    ['text' => 'Bahasa pemrograman untuk backend', 'correct' => false],
                    ['text' => 'Framework JavaScript', 'correct' => false],
                ],
            ],
        ];

        $count = 0;
        foreach ($questions as $questionData) {
            $question = BankSoal::create([
                'question_text' => $questionData['question_text'],
                'difficulty' => $questionData['difficulty'],
                'created_by' => $teacher->id,
                'is_active' => true,
            ]);

            foreach ($questionData['options'] as $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['text'],
                    'is_correct' => $optionData['correct'],
                ]);
            }
            $count++;
        }

        $this->command->info("Bank Soal seeded with {$count} questions!");
        $this->command->info('- Easy: 6 questions');
        $this->command->info('- Medium: 7 questions');
        $this->command->info('- Hard: 6 questions');
    }
}
