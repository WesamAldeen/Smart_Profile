<?php
require_once 'db.php';

$settings     = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$sections     = $pdo->query("SELECT * FROM sections WHERE is_active = 1 ORDER BY display_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$services     = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$portfolio    = $pdo->query("SELECT * FROM portfolio_items ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$skills       = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$stats        = $pdo->query("SELECT * FROM stats ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$primary_color = $settings['primary_color'] ?? '#4f46e5';

function formatUrl($url) {
    if (empty($url)) return '';
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "https://" . $url;
    }
    return $url;
}

function adjustBrightness($hex, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }
    $color_parts = str_split($hex, 2);
    $return = '#';
    foreach ($color_parts as $color) {
        $color   = hexdec($color);
        $color   = max(0, min(255, $color + $steps));
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }
    return $return;
}

$primary_hover = adjustBrightness($primary_color, -30);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($settings['site_title'] ?? 'البروفايل الذكي'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --primary-hover: <?php echo $primary_hover; ?>;
            --primary-shadow: <?php echo $primary_color; ?>66;
        }
    </style>
</head>
<body class="bg-[var(--bg-main)] text-[var(--text-main)] antialiased transition-colors duration-300">

    <!-- Header -->
    <header class="bg-[var(--bg-card)]/90 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-[var(--border-color)]">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <a href="index.php" class="flex items-center gap-3 text-xl md:text-2xl font-black">
                <?php if (($settings['logo_type'] ?? 'text') === 'image' && !empty($settings['logo_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($settings['logo_image']); ?>" alt="Logo" class="h-10 object-contain">
                <?php else: ?>
                    <span class="dynamic-text"><?php echo htmlspecialchars($settings['site_title'] ?? 'CodeExport'); ?></span>
                <?php endif; ?>
            </a>

            <nav class="hidden md:flex items-center gap-8 font-bold text-sm">
                <?php foreach ($sections as $sec): ?>
                    <?php if ($sec['section_key'] !== 'hero'): ?>
                        <a href="#<?php echo $sec['section_key']; ?>" class="hover:text-[var(--primary-color)] transition"><?php echo htmlspecialchars($sec['section_name']); ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>

            <div class="flex items-center gap-4">
                <button id="theme-toggle" class="p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:opacity-80 transition">
                    <i class="fa-solid fa-moon dark:hidden text-lg"></i>
                    <i class="fa-solid fa-sun hidden dark:block text-lg text-amber-400"></i>
                </button>

                <button id="mobile-menu-btn" class="md:hidden p-2.5 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-[var(--bg-card)] border-b border-[var(--border-color)] px-6 py-4 space-y-3">
            <?php foreach ($sections as $sec): ?>
                <?php if ($sec['section_key'] !== 'hero'): ?>
                    <a href="#<?php echo $sec['section_key']; ?>" class="block font-bold text-sm py-2 hover:text-[var(--primary-color)] transition border-b border-slate-100 dark:border-slate-800 last:border-0"><?php echo htmlspecialchars($sec['section_name']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </header>

    <!-- الأقسام -->
    <?php foreach ($sections as $sec): ?>
        
        <?php if ($sec['section_key'] === 'hero'): ?>
            <!-- قسم الهيرو بتصميم إحصائيات أفقي حديث للغاية وبدون حواف قديمة -->
            <section id="hero" class="py-16 md:py-24 relative overflow-hidden">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                        <div class="md:w-1/2 space-y-6 text-right">
                            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black leading-tight">
                                <?php echo htmlspecialchars($settings['hero_title'] ?? ''); ?>
                            </h1>
                            <p class="text-base md:text-lg text-[var(--text-muted)] leading-relaxed">
                                <?php echo htmlspecialchars($settings['hero_desc'] ?? ''); ?>
                            </p>
                            
                            <div class="pt-2">
                                <a href="#portfolio" class="btn-jelly gap-3 text-sm md:text-base">
                                    <span>استكشف أعمالنا</span>
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </div>

                            <!-- تصميم المهارات والإحصائيات تحت الهيرو بشكل شارات أنيقة متراصفة -->
                            <?php if (!empty($stats)): ?>
                                <div class="pt-6 flex flex-wrap items-center gap-4 border-t border-[var(--border-color)]">
                                    <?php foreach ($stats as $stat): ?>
                                        <div class="flex items-center gap-3 bg-[var(--bg-card)] px-4 py-2.5 rounded-2xl shadow-sm border border-[var(--border-color)]">
                                            <span class="dynamic-text text-lg"><i class="<?php echo htmlspecialchars($stat['icon_class']); ?>"></i></span>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-lg font-black dynamic-text"><?php echo htmlspecialchars($stat['stat_number']); ?></span>
                                                <span class="text-xs font-bold text-[var(--text-muted)]"><?php echo htmlspecialchars($stat['stat_title']); ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($settings['hero_image'])): ?>
                            <div class="md:w-1/2 flex justify-center">
                                <img src="uploads/<?php echo htmlspecialchars($settings['hero_image']); ?>" alt="Hero Image" class="rounded-3xl shadow-2xl max-w-md w-full object-cover h-[300px] md:h-[380px]">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'about'): ?>
            <section id="about" class="py-24 md:py-32 bg-[var(--bg-card)] border-y border-[var(--border-color)]">
                <div class="container mx-auto px-6 max-w-4xl text-center space-y-8">
                    <div class="space-y-3">
                        <h2 class="text-3xl md:text-5xl font-black tracking-tight">
                            <?php echo htmlspecialchars($settings['about_title'] ?? 'نبذة عني'); ?>
                        </h2>
                        <div class="w-20 h-1.5 dynamic-bg mx-auto rounded-full"></div>
                    </div>

                    <?php if (!empty($settings['about_subtitle'])): ?>
                        <h3 class="text-xl md:text-2xl font-extrabold dynamic-text">
                            <?php echo htmlspecialchars($settings['about_subtitle']); ?>
                        </h3>
                    <?php endif; ?>

                    <p class="text-[var(--text-muted)] leading-loose text-base md:text-lg whitespace-pre-line max-w-3xl mx-auto font-medium">
                        <?php echo htmlspecialchars($settings['about_text'] ?? ''); ?>
                    </p>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'skills'): ?>
            <!-- قسم المهارات يتكون من 4 أعمدة في السطر وتتجاوب تلقائياً بدون بوردرز متكلفة -->
            <section id="skills" class="py-20 md:py-28">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-2xl md:text-4xl font-black"><?php echo htmlspecialchars($sec['section_name']); ?></h2>
                        <div class="w-16 h-1.5 dynamic-bg mx-auto mt-4 rounded-full"></div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php foreach ($skills as $skill): ?>
                            <div class="soft-card p-5 space-y-3">
                                <div class="flex justify-between items-center font-bold text-sm">
                                    <span class="font-extrabold"><?php echo htmlspecialchars($skill['skill_name']); ?></span>
                                    <span class="dynamic-text font-black"><?php echo (int)$skill['percentage']; ?>%</span>
                                </div>
                                <div class="w-full h-2.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full dynamic-bg rounded-full transition-all duration-1000" style="width: <?php echo (int)$skill['percentage']; ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'services'): ?>
            <!-- قسم الخدمات بدون بوردر ملون على الصورة أو البطاقة -->
            <section id="services" class="py-20 md:py-28 bg-[var(--bg-card)]">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-2xl md:text-4xl font-black"><?php echo htmlspecialchars($sec['section_name']); ?></h2>
                        <div class="w-16 h-1.5 dynamic-bg mx-auto mt-4 rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($services as $srv): ?>
                            <div class="hover-lift p-8 flex flex-col">
                                <div class="flex items-center gap-4 mb-4">
                                    <?php if (!empty($srv['image'])): ?>
                                        <div class="w-14 h-14 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-100 dark:bg-slate-800">
                                            <img src="uploads/<?php echo htmlspecialchars($srv['image']); ?>" alt="Service" class="w-full h-full object-cover">
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="text-xl font-extrabold"><?php echo htmlspecialchars($srv['title']); ?></h3>
                                </div>
                                <p class="text-[var(--text-muted)] text-sm leading-relaxed"><?php echo htmlspecialchars($srv['description']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'portfolio'): ?>
            <section id="portfolio" class="py-20 md:py-28">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-2xl md:text-4xl font-black"><?php echo htmlspecialchars($sec['section_name']); ?></h2>
                        <div class="w-16 h-1.5 dynamic-bg mx-auto mt-4 rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($portfolio as $item): ?>
                            <div class="portfolio-card h-80">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="Project" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-500">بدون صورة</div>
                                <?php endif; ?>
                                
                                <div class="portfolio-overlay">
                                    <h3 class="text-xl font-black text-white mb-2"><?php echo htmlspecialchars($item['title']); ?></h3>
                                    <p class="text-slate-300 text-xs mb-5 line-clamp-3 leading-relaxed"><?php echo htmlspecialchars($item['description']); ?></p>
                                    <?php if (!empty($item['project_url'])): ?>
                                        <a href="<?php echo htmlspecialchars(formatUrl($item['project_url'])); ?>" target="_blank" class="btn-jelly gap-2 text-xs self-start !py-2 !px-4">
                                            <span>رابط المشروع</span>
                                            <i class="fa-solid fa-arrow-left"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'testimonials'): ?>
            <!-- قسم آراء العملاء بدون أي بوردرز ملونة على الصورة -->
            <section id="testimonials" class="py-20 md:py-28 bg-[var(--bg-card)]">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="text-2xl md:text-4xl font-black"><?php echo htmlspecialchars($sec['section_name']); ?></h2>
                        <div class="w-16 h-1.5 dynamic-bg mx-auto mt-4 rounded-full"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($testimonials as $t): ?>
                            <div class="hover-lift p-6 flex flex-col justify-between">
                                <div class="flex items-center gap-4 border-b border-[var(--border-color)] pb-4 mb-4">
                                    <?php if (!empty($t['client_image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($t['client_image']); ?>" alt="Client" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
                                    <?php else: ?>
                                        <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center font-bold text-slate-500 flex-shrink-0"><i class="fa-solid fa-user"></i></div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="font-extrabold text-base"><?php echo htmlspecialchars($t['client_name']); ?></h4>
                                        <span class="text-xs text-[var(--text-muted)] font-medium"><?php echo htmlspecialchars($t['client_role']); ?></span>
                                    </div>
                                </div>
                                <p class="text-[var(--text-muted)] italic text-sm leading-relaxed">"<?php echo htmlspecialchars($t['feedback']); ?>"</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

        <?php elseif ($sec['section_key'] === 'contact'): ?>
            <section id="contact" class="py-20 bg-slate-900 text-white text-center">
                <div class="container mx-auto px-6 max-w-2xl space-y-8">
                    <h2 class="text-3xl md:text-4xl font-black"><?php echo htmlspecialchars($sec['section_name']); ?></h2>
                    <p class="text-slate-400 text-sm md:text-base">تواصل معنا مباشرة عبر القنوات التالية لأجل استفسارك أو مشروعك القادم.</p>
                    
                    <div class="flex justify-center items-center gap-5 pt-2">
                        <?php if (!empty($settings['whatsapp'])): ?>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9+]/', '', $settings['whatsapp']); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center hover:bg-emerald-500 hover:text-white transition text-xl shadow-lg"><i class="fa-brands fa-whatsapp"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['linkedin'])): ?>
                            <a href="<?php echo htmlspecialchars(formatUrl($settings['linkedin'])); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center hover:bg-blue-500 hover:text-white transition text-xl shadow-lg"><i class="fa-brands fa-linkedin-in"></i></a>
                        <?php endif; ?>
                        <?php if (!empty($settings['telegram'])): ?>
                            <a href="<?php echo htmlspecialchars(formatUrl($settings['telegram'])); ?>" target="_blank" class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center hover:bg-sky-500 hover:text-white transition text-xl shadow-lg"><i class="fa-brands fa-telegram"></i></a>
                        <?php endif; ?>
                    </div>

                    <div class="border-t border-slate-800 pt-8 mt-12">
                        <p class="text-xs md:text-sm text-slate-500"><?php echo htmlspecialchars($settings['footer_text'] ?? 'جميع الحقوق محفوظة'); ?> &copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_title']); ?></p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    <?php endforeach; ?>

    <script src="script.js"></script>
</body>
</html>