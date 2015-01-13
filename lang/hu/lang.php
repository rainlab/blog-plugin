<?php

return [
    'plugin' => [
        'name' => 'Blog',
        'description' => 'Teljeskörű blogkezelő alkalmazás.'
    ],
    'blog' => [
        'menu_label' => 'Blog',
        'menu_description' => 'A blog bejegyzések kezelése',
        'posts' => 'Bejegyzések',
        'create_post' => 'blogbejegyzés',
        'categories' => 'Kategóriák',
        'create_category' => 'blog kategória',
        'access_posts' => 'Blog bejegyzések kezelése',
        'access_categories' => 'Blog kategóriák kezelése',
        'access_other_posts' => 'Más felhasználók bejegyzéseinek kezelése',
        'delete_confirm' => 'Biztos benne?',
        'chart_published' => 'Közzétéve',
        'chart_drafts' => 'Piszkozatok',
        'chart_total' => 'Összesen'
    ],
    'posts' => [
        'list_title' => 'A blog bejegyzések kezelése',
        'category' => 'Kategória',
        'hide_published' => 'Közzétettek elrejtése',
        'new_post' => 'Új bejegyzés'
    ],
    'post' => [
        'title' => 'Cím',
        'title_placeholder' => 'Új bejegyzés címe',
        'slug' => 'Keresőbarát cím',
        'slug_placeholder' => 'uj-bejegyzes-keresobarat-cime',
        'categories' => 'Kategóriák',
        'created' => 'Létrehozva',
        'updated' => 'Frissítve',
        'published' => 'Közzétéve',
        'published_validation' => 'Adja meg a közzététel dátumát',
        'tab_edit' => 'Szerkesztés',
        'tab_categories' => 'Kategóriák',
        'categories_comment' => 'Jelölje be azokat a kategóriákat, melyekbe be akarja sorolni a blogbejegyzést',
        'categories_placeholder' => 'Nincsenek kategóriák, előbb létre kell hoznia egyet!',
        'tab_manage' => 'Kezelés',
        'published_on' => 'Közzététel dátuma',
        'excerpt' => 'Kivonat',
        'featured_images' => 'Kiemelt képek',
        'delete_confirm' => 'Valóban törölni akarja ezt a bejegyzést?',
        'close_confirm' => 'A bejegyzés nem került mentésre.',
        'return_to_posts' => 'Vissza a bejegyzéslistához'
    ],
    'categories' => [
        'list_title' => 'A blog kategóriák kezelése',
        'new_category' => 'Új kategória'
    ],
    'category' => [
        'name' => 'Név',
        'name_placeholder' => 'Új kategória neve',
        'slug' => 'Keresőbarát cím',
        'slug_placeholder' => 'uj-kategoria-keresobarat-neve',
        'posts' => 'Bejegyzések',
        'delete_confirm' => 'Valóban törölni akarja ezt a kategóriát?',
        'return_to_categories' => 'Vissza a blog kategória-listához'
    ],
    'settings' => [
        'category_title' => 'Blog kategória lista',
        'category_description' => 'A blog kategóriákat listázza ki a lapon.',
        'category_slug' => 'Keresőbarát cím paraméter neve',
        'category_slug_description' => 'Az URL cím útvonal paramétere a jelenlegi kategória keresőbarát címe alapján való kereséséhez. Az alapértelmezett komponensrész ezt a tulajdonságot használja a jelenleg aktív kategória megjelöléséhez.',
        'category_display_empty' => 'Üres kategóriák kijelzése',
        'category_display_empty_description' => 'Azon kategóriák megjelenítése, melyekben nincs egy bejegyzés sem.',
        'category_page' => 'Kategórialap',
        'category_page_description' => 'A kategóriahivatkozások kategórialap-fájljának neve. Az alapértelmezett komponensrész használja ezt a tulajdonságot.',
        'post_title' => 'Blogbejegyzés',
        'post_description' => 'Egy blogbejegyzést jelez ki a lapon.',
        'post_slug' => 'Keresőbarát cím paraméter neve',
        'post_slug_description' => 'Az URL cím útvonal paramétere a bejegyzés keresőbarát címe alapján való kereséséhez.',
        'post_category' => 'Kategórialap',
        'post_category_description' => 'A kategóriahivatkozások kategórialap-fájljának neve. Az alapértelmezett komponensrész használja ezt a tulajdonságot.',
        'posts_title' => 'Blog bejegyzések',
        'posts_description' => 'A legújabb blog bejegyzéseket listázza ki a lapon.',
        'posts_pagination' => 'Lapozósáv paraméter neve',
        'posts_pagination_description' => 'A lapozósáv lapjai által használt, várt paraméter neve.',
        'posts_filter' => 'Kategóriaszűrő',
        'posts_filter_description' => 'Adja meg egy kategória keresőbarát címét vagy URL cím paraméterét a bejegyzések szűréséhez. Hagyja üresen az összes bejegyzés megjelenítéséhez.',
        'posts_per_page' => 'Bejegyzések laponként',
        'posts_per_page_validation' => 'A laponkénti bejegyzések értéke érvénytelen formátumú',
        'posts_no_posts' => 'Nincsenek bejegyzések üzenet ',
        'posts_no_posts_description' => 'A blogbejegyzés-listában kijelezendő üzenet abban az esetben, ha nincsenek bejegyzések. Az alapértelmezett komponensrész használja ezt a tulajdonságot.',
        'posts_order' => 'Bejegyzések sorrendje',
        'posts_order_decription' => 'Attribútum, mely alapján rendezni kell a bejegyzéseket',
        'posts_category' => 'Kategórialap',
        'posts_category_description' => 'A "Kategória" kategóriahivatkozások kategórialap-fájljának neve. Az alapértelmezett komponensrész használja ezt a tulajdonságot.',
        'posts_post' => 'Bejegyzéslap',
        'posts_post_description' => 'A "Tovább olvasom" hivatkozások blog bejegyzés-lap fájljának neve. Az alapértelmezett komponensrész használja ezt a tulajdonságot.'
    ]
];
