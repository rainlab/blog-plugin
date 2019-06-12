<?php

return [
    'plugin' => [
        'name' => 'Blog',
        'description' => 'Sağlam blog platformu.',
    ],
    'blog' => [
        'menu_label' => 'Blog',
        'menu_description' => 'Blog Gönderilerini Yönet',
        'posts' => 'Gönderiler',
        'create_post' => 'Blog gönderisi',
        'categories' => 'Kategoriler',
        'create_category' => 'Blog kategorisi',
        'tab' => 'Blog',
        'access_posts' => 'Gönderileri yönetebilsin',
        'access_categories' => 'Blog kategorilerini yönetebilsin',
        'access_other_posts' => 'Diğer kullanıcıların gönderilerini yönetebilsin',
        'access_import_export' => 'Gönderileri içeri/dışarı aktarabilsin',
        'access_publish' => 'Gönderi yayınlayabilsin',
        'delete_confirm' => 'Emin misiniz?',
        'chart_published' => 'Yayınlandı',
        'chart_drafts' => 'Taslaklar',
        'chart_total' => 'Toplam',
    ],
    'posts' => [
        'list_title' => 'Blog gönderilerini yönet',
        'filter_category' => 'Kategori',
        'filter_published' => 'Yayınlanan',
        'filter_date' => 'Tarih',
        'new_post' => 'Yeni gönderi',
        'export_post' => 'Gönderileri dışarı aktar',
        'import_post' => 'Gönderileri içeri aktar',
    ],
    'post' => [
        'title' => 'Başlık',
        'title_placeholder' => 'Yeni gönderi başlığı',
        'content' => 'İçerik',
        'content_html' => 'HTML İçeriği',
        'slug' => 'Kısa URL',
        'slug_placeholder' => 'yeni-gonderi-basligi',
        'categories' => 'Kategoriler',
        'author_email' => 'Yazar E-mail',
        'created' => 'Oluşturuldu',
        'created_date' => 'Oluşturulma tarihi',
        'updated' => 'Güncellendi',
        'updated_date' => 'Güncellenme tarihi',
        'published' => 'Yayınlandı',
        'published_date' => 'Yayınlanma tarihi',
        'published_validation' => 'Lütfen yayınlama tarihini belirtiniz',
        'tab_edit' => 'Düzenle',
        'tab_categories' => 'Kategoriler',
        'categories_comment' => 'Gönderinin ait olduğu kategorileri seçiniz',
        'categories_placeholder' => 'Kategori yok, öncelikle bir kategori oluşturmalısınız!',
        'tab_manage' => 'Yönet',
        'published_on' => 'Yayınlandı',
        'excerpt' => 'Alıntı',
        'summary' => 'Özet',
        'featured_images' => 'Öne Çıkan Görseller',
        'delete_confirm' => 'Bu yazıyı silmek istiyor musunuz?',
        'delete_success' => 'Gönderi(ler) silindi.',
        'close_confirm' => 'Gönderi kaydedilmedi.',
        'return_to_posts' => 'Gönderi listesine dön',
    ],
    'categories' => [
        'list_title' => 'Blog kategorilerini yönet',
        'new_category' => 'Yeni kategori',
        'uncategorized' => 'Kategorisiz',
    ],
    'category' => [
        'name' => 'İsim',
        'name_placeholder' => 'Yeni kategori adı',
        'description' => 'Açıklama',
        'slug' => 'Kısa URL',
        'slug_placeholder' => 'yeni-kategori-basligi',
        'posts' => 'Gönderiler',
        'delete_confirm' => 'Bu kategoriyi silmek istiyor musunuz?',
        'delete_success' => 'Kategori(ler) silindi.',
        'return_to_categories' => 'Kategori listesine dön',
        'reorder' => 'Kategorileri yeniden sırala',
    ],
    'menuitem' => [
        'blog_category' => 'Blog kategorisi',
        'all_blog_categories' => 'Tüm blog kategorileri',
        'blog_post' => 'Blog gönderisi',
        'all_blog_posts' => 'Tüm blog gönderileri',
        'category_blog_posts' => 'Blog kategori gönderileri',
    ],
    'settings' => [
        'category_title' => 'Kategori Listesi',
        'category_description' => 'Kategorilerin listesini sayfada göster.',
        'category_slug' => 'Kategori Kısa URL',
        'category_slug_description' => 'Verilen kısa URLi kullanarak blog kategorisini görüntüle. Bu özellik şu anki aktif kategoriyi işaretlemek için varsayılan kısmi bileşeni tarafından kullanılır',
        'category_display_empty' => 'Boş kategorileri göster',
        'category_display_empty_description' => 'Herhangi bir gönderi olmayan kategorileri göster.',
        'category_page' => 'Kategori sayfası',
        'category_page_description' => 'Kategori bağlantıları için kategori sayfası dosyasının adı. Bu özellik varsayılan kısmi bileşeni tarafından kullanılır.',
        'post_title' => 'Gönderi',
        'post_description' => 'Sayfada bir blog gönderisi gösterir.',
        'post_slug' => 'Gönderi Kısa URL',
        'post_slug_description' => 'Verilen kısa URL ile blog gönderisine bakın.',
        'post_category' => 'Kategori sayfası',
        'post_category_description' => 'Kategori bağlantıları için kategori sayfası dosyasının adı. Bu özellik varsayılan kısmi bileşeni tarafından kullanılır.',
        'posts_title' => 'Gönderi listesi',
        'posts_description' => 'Sayfada son blog gönderilerinin listesini gösterir.',
        'posts_pagination' => 'Sayfa numarası',
        'posts_pagination_description' => 'Bu değer kullanıcının hangi sayfada olduğunu belirlemek için kullanılır.',
        'posts_filter' => 'Kategori filtresi',
        'posts_filter_description' => 'Gönderileri filtrelemek için kategori kısa URLsi ya da URL parametresi girin. Tüm gönderiler için boş bırakın.',
        'posts_per_page' => 'Sayfa başına gönderi',
        'posts_per_page_validation' => 'Sayfa başına gönderi için geçersiz format',
        'posts_no_posts' => 'Gönderi mesajı yok',
        'posts_no_posts_description' => 'Eğer bir gönderi yoksa gönderi listesinde görüntülenecek mesaj. Bu özellik varsayılan kısmi bileşeni tarafından kullanılır.',
        'posts_no_posts_default' => 'Gönderi yok',
        'posts_order' => 'Gönderi Sırası',
        'posts_order_description' => 'Gönderilerin sıralama türü',
        'posts_category' => 'Kategori sayfası',
        'posts_category_description' => '"Yayınlanan" kategori bağlantıları için kategori sayfası dosyasının adı. Bu özellik varsayılan kısmi bileşeni tarafından kullanılır.',
        'posts_post' => 'Gönderi sayfası',
        'posts_post_description' => '"Daha fazla bilgi edinin" bağlantıları için gönderi sayfası dosyasının adı. Bu özellik varsayılan kısmi bileşeni tarafından kullanılır.',
        'posts_except_post' => 'Hariç tutulacak gönderi',
        'posts_except_post_description' => 'Hariç tutmak istediğiniz gönderinin ID/URL ini veya ID/URL içeren bir değişken girin. Birden çok gönderi belirtmek için virgülle ayrılmış liste kullanabilirsiniz.',
        'posts_except_categories' => 'Hariç tutulacak kategoriler',
        'posts_except_categories_description' => 'Hariç tutmak istediğiniz kategori listesini içeren virgülle ayrılmış bir kategori listesi veya listeyi içeren bir değişken girin.',
        'rssfeed_blog' => 'Blog sayfası',
        'rssfeed_blog_description' => 'Linkleri üretmek için ana blog sayfasının adı. Bu özellik, varsayılan kısmi bileşeni tarafından kullanılır.',
        'rssfeed_title' => 'RSS Beslemesi',
        'rssfeed_description' => 'Blog içerisindeki gönderileri veren RSS beslemesi oluşturur.',
        'group_links' => 'Linkler',
        'group_exceptions' => 'Hariç olanlar',
    ],
    'sorting' => [
        'title_asc' => 'Başlık (a-z)',
        'title_desc' => 'Başlık (z-a)',
        'created_asc' => 'Oluşturulma (yeniden eskiye)',
        'created_desc' => 'Oluşturulma (eskiden yeniye)',
        'updated_asc' => 'Güncellenme (yeniden eskiye)',
        'updated_desc' => 'Güncellenme (eskiden yeniye)',
        'published_asc' => 'Yayınlanma (yeniden eskiye)',
        'published_desc' => 'Yayınlanma (eskiden yeniye)',
        'random' => 'Rastgele',
    ],
    'import' => [
        'update_existing_label' => 'Mevcut gönderileri güncelle',
        'update_existing_comment' => 'Tam olarak aynı ID, başlık veya kısa URL içeren gönderileri güncellemek için bu kutuyu işaretleyin.',
        'auto_create_categories_label' => 'İçe aktarma dosyasında bulunan kategorileri oluştur',
        'auto_create_categories_comment' => 'Bu özelliği kullanmak için Kategoriler sütununu eşleştirmelisiniz, aksi takdirde aşağıdaki öğelerden kullanılacak varsayılan kategorileri seçmelisiniz.',
        'categories_label' => 'Kategoriler',
        'categories_comment' => 'İçe aktarılan gönderilerin ait olacağı kategorileri seçin (isteğe bağlı).',
        'default_author_label' => 'Varsayılan gönderi yazarı (isteğe bağlı)',
        'default_author_comment' => 'İçe aktarma, Yazar E-postası sütunuyla eşleşirse mevcut bir yazarı gönderi için kullanmaya çalışır, aksi takdirde yukarıda belirtilen yazar seçilir.',
        'default_author_placeholder' => '-- yazar seçin --',
    ],
];
