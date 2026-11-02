<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // title, author, category, published?, date
        $rows = [
            ['Autumn semester registration opens',                  'Nino Beraia',        'announcements', 1, '2026-08-25'],
            ['New robotics laboratory opens in Building B',          'Nika Beridze',       'announcements', 1, '2026-09-02'],
            ['Library opening hours extended until midnight',        'Tamar Chkheidze',    'announcements', 1, '2026-09-10'],
            ['Scholarship applications close on 30 November',        'Nino Beraia',        'announcements', 1, '2026-10-01'],
            ['Campus wifi upgrade this weekend',                     'Davit Lomidze',      'announcements', 1, '2026-10-12'],
            ['Exam timetable for the winter session',                'Tamar Chkheidze',    'announcements', 0, null],

            ['Robotics team publishes a paper on swarm navigation',  'Nika Beridze',       'research',      1, '2026-09-15'],
            ['Grant awarded for renewable energy research',          'Ana Khutsishvili',   'research',      1, '2026-09-22'],
            ['Students present machine learning work in Tbilisi',    'Giorgi Maisuradze',  'research',      1, '2026-10-05'],
            ['New laboratory for materials science',                 'Ana Khutsishvili',   'research',      1, '2026-10-18'],
            ['Call for undergraduate research assistants',           'Giorgi Maisuradze',  'research',      0, null],

            ['Student council elections: how to stand',              'Salome Kapanadze',   'student-life',  1, '2026-09-05'],
            ['Chess club meets every Thursday',                      'Luka Tsiklauri',     'student-life',  1, '2026-09-18'],
            ['New menu in the student cafeteria',                    'Salome Kapanadze',   'student-life',  1, '2026-09-28'],
            ['Volunteer programme for the winter',                   'Mariam Gelashvili',  'student-life',  1, '2026-10-09'],
            ['Photography club exhibition in the main hall',         'Luka Tsiklauri',     'student-life',  0, null],

            ['Open day for school pupils on 14 November',            'Mariam Gelashvili',  'events',        1, '2026-10-02'],
            ['Guest lecture on web security',                        'Davit Lomidze',      'events',        1, '2026-10-15'],
            ['Robotics competition hosted at KIU',                   'Nika Beridze',       'events',        1, '2026-10-24'],
            ['Winter concert in the main auditorium',                'Salome Kapanadze',   'events',        0, null],
        ];

        foreach ($rows as $row) {
            $body = $row[0] . ".\n\n"
                  . 'This is the full text of the article. In a real newsroom an '
                  . 'editor would write several paragraphs here. One is enough for '
                  . 'this lab, and it is long enough to show the difference between '
                  . 'the short excerpt on the list page and the full body on the '
                  . 'article page.';

            Post::create([
                'title'        => $row[0],
                'author'       => $row[1],
                'category'     => $row[2],
                'excerpt'      => Str::limit($body, 110),
                'body'         => $body,
                'is_published' => (bool) $row[3],
                'published_at' => $row[4],
            ]);
        }
    }
}
