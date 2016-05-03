<?php

return [
    'plugin' => [
        'name' => 'Blog',
        'description' => 'Une plateforme de blog robuste.'
    ],
    'blog' => [
        'menu_label' => 'Blog',
        'menu_description' => 'Gestion d\'articles de blog',
        'posts' => 'Articles',
        'create_post' => 'article de blog',
        'categories' => 'Catégories',
        'create_category' => 'catégorie d\'articles',
        'tab' => 'Blog',
        'access_posts' => 'Gérer les articles',
        'access_categories' => 'Gérer les catégories',
        'access_other_posts' => 'Gérer les articles d\'autres utilisateurs',
        'delete_confirm' => 'Êtes-vous certain(e) de vouloir supprimer ?',
        'chart_published' => 'Publié',
        'chart_drafts' => 'Brouillons',
        'chart_total' => 'Total'
    ],
    'posts' => [
        'list_title' => 'Gérer les articles du blog',
        'category' => 'Catégorie',
        'hide_published' => 'Masquer la publication',
        'new_post' => 'Nouvel article'
    ],
    'post' => [
        'title' => 'Titre',
        'title_placeholder' => 'Titre du nouvel article',
        'slug' => 'Adresse',
        'slug_placeholder' => 'adresse-du-nouvel-article',
        'categories' => 'Catégories',
        'created' => 'Créé',
        'updated' => 'Mis a jour',
        'published' => 'Publié',
        'published_validation' => 'Précisez s\'il vous plait la date de publication',
        'tab_edit' => 'Editer',
        'tab_categories' => 'Catégories',
        'categories_comment' => 'Sélectionnez les catégories auxquelles l\'article est lié',
        'categories_placeholder' => 'Il n\'y a pas encore de catégorie, mais vous pouvez en créer une !',
        'tab_manage' => 'Gestion',
        'published_on' => 'Publié le',
        'excerpt' => 'Résumé',
        'featured_images' => 'Image de sélection',
        'delete_confirm' => 'Souhaitez-vous vraiment supprimer cet article?',
        'close_confirm' => 'L\'article n\'est pas enregistré.',
        'return_to_posts' => 'Retour à la liste des articles'
    ],
    'categories' => [
        'list_title' => 'Gérer les catégories',
        'new_category' => 'Nouvelle catégorie',
        'uncategorized' => 'Sans catégorie'
    ],
    'category' => [
        'name' => 'Nom',
        'name_placeholder' => 'Nom de la nouvelle catégorie',
        'slug' => 'Adresse',
        'slug_placeholder' => 'adresse-de-la-nouvelle-catégorie',
        'posts' => 'Articles',
        'delete_confirm' => 'Souhaitez-vous vraiment supprimer cette catégorie ?',
        'return_to_categories' => 'Retour à la liste des catégories'
    ],
    'settings' => [
        'category_title' => 'Liste des catégories',
        'category_description' => 'Afficher une liste des catégories sur la page.',
        'category_slug' => 'Adresse de la catégorie',
        'category_slug_description' => 'Adresse d\'accès à la catégorie. Cette propriété est utilisée par le composant partiel par défaut pour marquer la catégorie courante comme active.',
        'category_display_empty' => 'Afficher les catégories vides.',
        'category_display_empty_description' => 'Afficher les catégories qui n\'ont aucun article relié.',
        'category_page' => 'Page des catégories',
        'category_page_description' => 'Nom de la page des catégories pour les liens de catégories. Cette propriété est utilisée par le composant partiel par défaut.',
        'post_title' => 'Article',
        'post_description' => 'Affiche un article de blog sur la page.',
        'post_slug' => 'Adresse de l\'article',
        'post_slug_description' => 'Adresse d\'accès à la catégorie l\'article.',
        'post_category' => 'Page des catégories',
        'post_category_description' => 'Nom de la page des catégories pour les liens de catégories. Cette propriété est utilisée par le composant partiel par défaut.',
        'posts_title' => 'Liste d\'articles',
        'posts_description' => 'Affiche une liste des derniers articles de blog sur la page.',
        'posts_pagination' => 'Numéro de page',
        'posts_pagination_description' => 'Cette valeur est utilisée pour déterminer a quelle pqge l\'utilisateur se trouve.',
        'posts_filter' => 'Filtre des catégories',
        'posts_filter_description' => 'Entrez une adresse de catégorie ou un paramètre d\'URL pour filter les articles. Laissez vide pour afficher tous les articles.',
        'posts_per_page_validation' => 'Format d\'articles par page incorrect',
        'posts_no_posts' => 'Message en l\'absence d\'articles',
        'posts_no_posts_description' => 'Message a afficher dans la liste d\'articles lorsqu\'il n\'y a aucun article. Cette propriété est utilisée par le composant partiel par défaut.',
        'posts_order' => 'Ordre des articles',
        'posts_order_description' => 'Attribut selon lequel les articles seront ordonnés',
        'posts_category' => 'Page des catégorie',
        'posts_category_description' => 'Nom de la page des catégorie pour les liens de catégories "Publié dans". Cette propriété est utilisée par le composant partiel par défaut.',
        'posts_post' => 'Page d\'article',
        'posts_post_description' => 'Nom de la page d\'articles pour les liens "En savoir plus". Cette propriété est utilisée par le composant partiel par défaut.'
    ]
];
