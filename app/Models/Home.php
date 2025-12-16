<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $table = 'home_pages';

    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_description',
        'hero_button_text',
        'hero_button_link',

        // STATS SECTION
        'stats_title1',
        'stats_value1',
        'stats_title2',
        'stats_value2',
        'stats_title3',
        'stats_value3',
        'stats_title4',
        'stats_value4',

        // ABOUT SECTION
        'about_title',
        'about_description',

        'about_card1_title',
        'about_card1_text',
        'about_card2_title',
        'about_card2_text',
        'about_card3_title',
        'about_card3_text',

        'about_button_text',
        'about_button_link',

        // BEST SELLER SECTION
        'bestseller_title',
        'bestseller_description',

        // GALLERY SECTION
        'gallery_title',
        'gallery_description',

        // FAQ SECTION
        'faq1_question',
        'faq1_answer',
        'faq2_question',
        'faq2_answer',
        'faq3_question',
        'faq3_answer',
        'faq4_question',
        'faq4_answer'
    ];
}
