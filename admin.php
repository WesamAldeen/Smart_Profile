<?php
require_once 'db.php';

$msg = "";
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

function uploadFile($fileKey, $uploadDir) {
    if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
        $filename = uniqid('img_') . '.' . strtolower($ext);
        if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $uploadDir . $filename)) {
            return $filename;
        }
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_settings'])) {
        $site_title    = $_POST['site_title'] ?? '';
        $logo_type     = $_POST['logo_type'] ?? 'text';
        $hero_title    = $_POST['hero_title'] ?? '';
        $hero_desc     = $_POST['hero_desc'] ?? '';
        $primary_color = $_POST['primary_color'] ?? '#4f46e5';
        $whatsapp      = $_POST['whatsapp'] ?? '';
        $linkedin      = $_POST['linkedin'] ?? '';
        $telegram      = $_POST['telegram'] ?? '';
        $footer_text   = $_POST['footer_text'] ?? '';

        $about_title    = $_POST['about_title'] ?? 'نبذة عني';
        $about_subtitle = $_POST['about_subtitle'] ?? '';
        $about_text     = $_POST['about_text'] ?? '';

        $btn_style      = $_POST['btn_style'] ?? 'jelly';
        $btn_text_color = $_POST['btn_text_color'] ?? '#ffffff';
        $font_family    = $_POST['font_family'] ?? 'Tajawal';
        $card_theme     = $_POST['card_theme'] ?? 'soft';

        $logo_image = uploadFile('logo_image', $uploadDir);
        $hero_image = uploadFile('hero_image', $uploadDir);

        $curr = $pdo->query("SELECT logo_image, hero_image FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
        if (!$logo_image) $logo_image = $curr['logo_image'] ?? '';
        if (!$hero_image) $hero_image = $curr['hero_image'] ?? '';

        $stmt = $pdo->prepare("UPDATE settings SET 
            site_title = ?, logo_type = ?, logo_image = ?, hero_title = ?, hero_desc = ?, hero_image = ?, 
            primary_color = ?, whatsapp = ?, linkedin = ?, telegram = ?, footer_text = ?, 
            about_title = ?, about_subtitle = ?, about_text = ?, 
            btn_style = ?, btn_text_color = ?, font_family = ?, card_theme = ? 
            WHERE id = 1");
        $stmt->execute([
            $site_title, $logo_type, $logo_image, $hero_title, $hero_desc, $hero_image, 
            $primary_color, $whatsapp, $linkedin, $telegram, $footer_text, 
            $about_title, $about_subtitle, $about_text, 
            $btn_style, $btn_text_color, $font_family, $card_theme
        ]);
        $msg = "تم حفظ جميع الإعدادات والتنسيقات بنجاح!";
    }

    if (isset($_POST['update_sections'])) {
        foreach ($_POST['sections'] as $sec_id => $data) {
            $is_active = isset($data['is_active']) ? 1 : 0;
            $order     = (int)$data['display_order'];
            $stmt = $pdo->prepare("UPDATE sections SET is_active = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$is_active, $order, $sec_id]);
        }
        $msg = "تم تحديث ترتيب الأقسام بنجاح!";
    }

    if (isset($_POST['add_skill'])) {
        $name = $_POST['skill_name'];
        $perc = (int)$_POST['percentage'];
        $stmt = $pdo->prepare("INSERT INTO skills (skill_name, percentage) VALUES (?, ?)");
        $stmt->execute([$name, $perc]);
        $msg = "تمت إضافة المهارة!";
    }
    if (isset($_POST['delete_skill'])) {
        $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
        $stmt->execute([$_POST['skill_id']]);
        $msg = "تم حذف المهارة!";
    }

    if (isset($_POST['add_stat'])) {
        $num  = $_POST['stat_number'];
        $title= $_POST['stat_title'];
        $icon = $_POST['icon_type'];
        $stmt = $pdo->prepare("INSERT INTO stats (stat_number, stat_title, icon_class) VALUES (?, ?, ?)");
        $stmt->execute([$num, $title, $icon]);
        $msg = "تمت إضافة الإحصائية!";
    }
    if (isset($_POST['delete_stat'])) {
        $stmt = $pdo->prepare("DELETE FROM stats WHERE id = ?");
        $stmt->execute([$_POST['stat_id']]);
        $msg = "تم حذف الإحصائية!";
    }

    if (isset($_POST['add_service'])) {
        $title = $_POST['title'];
        $desc  = $_POST['description'];
        $img   = uploadFile('service_image', $uploadDir) ?? '';
        $stmt = $pdo->prepare("INSERT INTO services (title, description, image) VALUES (?, ?, ?)");
        $stmt->execute([$title, $desc, $img]);
        $msg = "تمت إضافة الخدمة!";
    }
    if (isset($_POST['delete_service'])) {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
        $stmt->execute([$_POST['service_id']]);
        $msg = "تم حذف الخدمة!";
    }

    if (isset($_POST['add_portfolio'])) {
        $title = $_POST['title'];
        $desc  = $_POST['description'];
        $url   = $_POST['project_url'];
        $img   = uploadFile('portfolio_image', $uploadDir) ?? '';
        $stmt = $pdo->prepare("INSERT INTO portfolio_items (title, description, image, project_url) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $desc, $img, $url]);
        $msg = "تمت إضافة العمل!";
    }
    if (isset($_POST['delete_portfolio'])) {
        $stmt = $pdo->prepare("DELETE FROM portfolio_items WHERE id = ?");
        $stmt->execute([$_POST['portfolio_id']]);
        $msg = "تم حذف العمل!";
    }

    if (isset($_POST['add_testimonial'])) {
        $name     = $_POST['client_name'];
        $role     = $_POST['client_role'];
        $feedback = $_POST['feedback'];
        $img      = uploadFile('client_image', $uploadDir) ?? '';
        $stmt = $pdo->prepare("INSERT INTO testimonials (client_name, client_role, client_image, feedback) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $role, $img, $feedback]);
        $msg = "تمت إضافة الرأي!";
    }
    if (isset($_POST['delete_testimonial'])) {
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$_POST['testimonial_id']]);
        $msg = "تم حذف الرأي!";
    }
}

$settings     = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch(PDO::FETCH_ASSOC);
$sections     = $pdo->query("SELECT * FROM sections ORDER BY display_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$services     = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$portfolio    = $pdo->query("SELECT * FROM portfolio_items ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$skills       = $pdo->query("SELECT * FROM skills ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$stats        = $pdo->query("SELECT * FROM stats ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$primary_color = $settings['primary_color'] ?? '#4f46e5';

function adjustBrightnessAdmin($hex, $steps) {
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

$primary_hover  = adjustBrightnessAdmin($primary_color, -30);
$btn_style_class= 'btn-style-' . ($settings['btn_style'] ?? 'jelly');
$active_font    = $settings['font_family'] ?? 'Tajawal';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم المباشرة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;700&family=Amiri:wght@400;700&family=Cairo:wght@400;700&family=Changa:wght@400;700&family=El+Messiri:wght@400;700&family=IBM+Plex+Sans+Arabic:wght@400;700&family=Marhey:wght@400;700&family=Noto+Kufi+Arabic:wght@400;700&family=Readex+Pro:wght@400;700&family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=4.0">
    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --primary-hover: <?php echo $primary_hover; ?>;
            --primary-shadow: <?php echo $primary_color; ?>66;
            --btn-text-color: <?php echo $settings['btn_text_color'] ?? '#ffffff'; ?>;
        }
        body {
            font-family: '<?php echo $active_font; ?>', sans-serif !important;
        }
    </style>
</head>
<body class="bg-[var(--bg-main)] text-[var(--text-main)] p-4 sm:p-6 transition-colors duration-300">

    <div class="max-w-5xl mx-auto space-y-6 sm:space-y-8">
        <!-- الهيدر -->
        <div class="soft-card p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h1 class="text-xl sm:text-2xl font-black text-center sm:text-right">لوحة التحكم المباشرة</h1>
            <div class="flex items-center gap-3 w-full sm:w-auto justify-center">
                <button id="theme-toggle" class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200">
                    <i class="fa-solid fa-moon dark:hidden"></i>
                    <i class="fa-solid fa-sun hidden dark:block text-amber-400"></i>
                </button>
                <a href="index.php" target="_blank" class="btn-custom <?php echo $btn_style_class; ?> text-xs !py-2.5 !px-4 w-full sm:w-auto text-center">
                    <span>معاينة الموقع</span>
                    <i class="fa-solid fa-external-link-alt mr-2"></i>
                </a>
            </div>
        </div>

        <?php if (!empty($msg)): ?>
            <div class="bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-400 text-emerald-700 dark:text-emerald-300 px-5 py-3 rounded-2xl font-bold text-sm">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <!-- الإعدادات العامة والتخصيص -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">الإعدادات العامة والتخصيص</h2>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                <div>
                    <label class="block font-bold text-xs mb-2">اسم الموقع</label>
                    <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">نوع اللوغو</label>
                    <select name="logo_type" class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] p-2.5 rounded-xl text-sm outline-none">
                        <option value="text" <?php echo ($settings['logo_type'] ?? '') === 'text' ? 'selected' : ''; ?>>نص</option>
                        <option value="image" <?php echo ($settings['logo_type'] ?? '') === 'image' ? 'selected' : ''; ?>>صورة</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">صورة اللوغو</label>
                    <input type="file" name="logo_image" class="w-full border border-[var(--border-color)] p-1.5 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">اللون الأساسي</label>
                    <input type="color" name="primary_color" value="<?php echo htmlspecialchars($settings['primary_color'] ?? '#4f46e5'); ?>" class="w-full h-10 rounded-xl border border-[var(--border-color)] cursor-pointer p-1 bg-transparent">
                </div>

                <!-- تخصيص المظهر المتقدم -->
                <div class="sm:col-span-2 pt-2 border-t border-[var(--border-color)]"><h3 class="font-bold text-sm dynamic-text">تخصيص المظهر (الأزرار، الخطوط، والأستايلات)</h3></div>
                
                <div>
                    <label class="block font-bold text-xs mb-2">شكل الأزرار</label>
                    <select name="btn_style" class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] p-2.5 rounded-xl text-sm outline-none">
                        <option value="jelly" <?php echo ($settings['btn_style'] ?? '') === 'jelly' ? 'selected' : ''; ?>>ثلاثي الأبعاد (Jelly / بارز)</option>
                        <option value="capsule" <?php echo ($settings['btn_style'] ?? '') === 'capsule' ? 'selected' : ''; ?>>كبسولة ثلاثية الأبعاد (3D Capsule)</option>
                        <option value="flat" <?php echo ($settings['btn_style'] ?? '') === 'flat' ? 'selected' : ''; ?>>مسطح عصري (Flat)</option>
                        <option value="outline" <?php echo ($settings['btn_style'] ?? '') === 'outline' ? 'selected' : ''; ?>>إطار خارجي شفاف (Outline)</option>
                        <option value="soft" <?php echo ($settings['btn_style'] ?? '') === 'soft' ? 'selected' : ''; ?>>ناعم مع ظل خفيف (Soft Shadow)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-2">لون خط الأزرار</label>
                    <input type="color" name="btn_text_color" value="<?php echo htmlspecialchars($settings['btn_text_color'] ?? '#ffffff'); ?>" class="w-full h-10 rounded-xl border border-[var(--border-color)] cursor-pointer p-1 bg-transparent">
                </div>

                <div>
                    <label class="block font-bold text-xs mb-2">نوع الخط العربي (10 خطوط)</label>
                    <select name="font_family" class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] p-2.5 rounded-xl text-sm outline-none">
                        <?php 
                        $fonts = [
                            'Tajawal' => 'تجوال (Tajawal)',
                            'Cairo' => 'كايرو (Cairo)',
                            'Amiri' => 'أميري كلاسيكي (Amiri)',
                            'Readex Pro' => 'ريادكس برو (Readex Pro)',
                            'Changa' => 'شانغا البارز (Changa)',
                            'El Messiri' => 'المسيري الفني (El Messiri)',
                            'IBM Plex Sans Arabic' => 'آي بي إم بلكس (IBM Plex)',
                            'Alexandria' => 'الإسكندرية (Alexandria)',
                            'Marhey' => 'مارهاي الحيوي (Marhey)',
                            'Noto Kufi Arabic' => 'نوتو كوفي (Noto Kufi)'
                        ];
                        foreach($fonts as $key => $name):
                            $selected = (($settings['font_family'] ?? 'Tajawal') === $key) ? 'selected' : '';
                            echo "<option value=\"$key\" $selected>$name</option>";
                        endforeach;
                        ?>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-xs mb-2">ستايل الأقسام والبطاقات</label>
                    <select name="card_theme" class="w-full border border-[var(--border-color)] bg-[var(--bg-card)] p-2.5 rounded-xl text-sm outline-none">
                        <option value="soft" <?php echo ($settings['card_theme'] ?? '') === 'soft' ? 'selected' : ''; ?>>1. الستايل الناعم البارز (Soft UI)</option>
                        <option value="glass" <?php echo ($settings['card_theme'] ?? '') === 'glass' ? 'selected' : ''; ?>>2. الستايل الزجاجي الشفاف (Glassmorphism)</option>
                        <option value="neon" <?php echo ($settings['card_theme'] ?? '') === 'neon' ? 'selected' : ''; ?>>3. الستايل النيون المتوهج (Neon Glow)</option>
                        <option value="flat" <?php echo ($settings['card_theme'] ?? '') === 'flat' ? 'selected' : ''; ?>>4. الستايل الحد الأدنى (Minimal Flat)</option>
                        <option value="elevation" <?php echo ($settings['card_theme'] ?? '') === 'elevation' ? 'selected' : ''; ?>>5. الستايل ثلاثي الأبعاد المرتفع (3D Elevated)</option>
                    </select>
                </div>

                <div class="sm:col-span-2 pt-2 border-t border-[var(--border-color)]"><h3 class="font-bold text-sm">قسم الهيرو</h3></div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-xs mb-2">عنوان الهيرو</label>
                    <input type="text" name="hero_title" value="<?php echo htmlspecialchars($settings['hero_title'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-xs mb-2">وصف الهيرو</label>
                    <textarea name="hero_desc" rows="2" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none"><?php echo htmlspecialchars($settings['hero_desc'] ?? ''); ?></textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-xs mb-2">صورة الهيرو</label>
                    <input type="file" name="hero_image" class="w-full border border-[var(--border-color)] p-1.5 rounded-xl text-xs">
                </div>

                <div class="sm:col-span-2 pt-2 border-t border-[var(--border-color)]"><h3 class="font-bold text-sm">قسم نبذة عني</h3></div>
                <div>
                    <label class="block font-bold text-xs mb-2">عنوان النبذة</label>
                    <input type="text" name="about_title" value="<?php echo htmlspecialchars($settings['about_title'] ?? 'نبذة عني'); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">العنوان الفرعي</label>
                    <input type="text" name="about_subtitle" value="<?php echo htmlspecialchars($settings['about_subtitle'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div class="sm:col-span-2">
                    <label class="block font-bold text-xs mb-2">تفاصيل النبذة</label>
                    <textarea name="about_text" rows="3" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none"><?php echo htmlspecialchars($settings['about_text'] ?? ''); ?></textarea>
                </div>

                <div class="sm:col-span-2 pt-2 border-t border-[var(--border-color)]"><h3 class="font-bold text-sm">التواصل والفوتر</h3></div>
                <div>
                    <label class="block font-bold text-xs mb-2">واتساب</label>
                    <input type="text" name="whatsapp" value="<?php echo htmlspecialchars($settings['whatsapp'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">لينكد إن</label>
                    <input type="text" name="linkedin" value="<?php echo htmlspecialchars($settings['linkedin'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">تليجرام</label>
                    <input type="text" name="telegram" value="<?php echo htmlspecialchars($settings['telegram'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>
                <div>
                    <label class="block font-bold text-xs mb-2">نص الفوتر</label>
                    <input type="text" name="footer_text" value="<?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?>" class="w-full border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                </div>

                <div class="sm:col-span-2 pt-3">
                    <button type="submit" name="update_settings" class="btn-custom <?php echo $btn_style_class; ?> text-sm w-full sm:w-auto">حفظ جميع الإعدادات والتنسيقات</button>
                </div>
            </form>
        </div>

        <!-- الأقسام -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إظهار وترتيب الأقسام</h2>
            <form method="POST" class="space-y-3">
                <?php foreach ($sections as $sec): ?>
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-[var(--border-color)] bg-slate-50/50 dark:bg-slate-800/50">
                        <span class="font-bold text-xs sm:text-sm"><?php echo htmlspecialchars($sec['section_name']); ?></span>
                        <div class="flex items-center gap-3 sm:gap-4">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs font-semibold">
                                <input type="checkbox" name="sections[<?php echo $sec['id']; ?>][is_active]" value="1" <?php echo $sec['is_active'] ? 'checked' : ''; ?> class="w-4 h-4 rounded">
                                <span>مفعل</span>
                            </label>
                            <input type="number" name="sections[<?php echo $sec['id']; ?>][display_order]" value="<?php echo $sec['display_order']; ?>" class="w-12 sm:w-14 border border-[var(--border-color)] bg-transparent p-1.5 rounded-lg text-center font-bold text-xs sm:text-sm">
                        </div>
                    </div>
                <?php endforeach; ?>
                <button type="submit" name="update_sections" class="btn-custom <?php echo $btn_style_class; ?> text-sm w-full sm:w-auto">حفظ ترتيب الأقسام</button>
            </form>
        </div>

        <!-- إدارة الإحصائيات -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إدارة إحصائيات الهيرو</h2>
            <form method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
                <input type="text" name="stat_number" placeholder="الرقم (مثال: +5)" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="text" name="stat_title" placeholder="العنوان (مثال: سنوات الخبرة)" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <select name="icon_type" class="border border-[var(--border-color)] bg-[var(--bg-card)] p-2.5 rounded-xl text-sm outline-none">
                    <option value="fa-solid fa-briefcase">حقيبة عمل / خبرة</option>
                    <option value="fa-solid fa-folder-open">مشاريع منجزة</option>
                    <option value="fa-solid fa-users">عملاء سعداء</option>
                    <option value="fa-solid fa-star">تقييمات ممتازة</option>
                    <option value="fa-solid fa-award">جوائز وإنجازات</option>
                </select>
                <button type="submit" name="add_stat" class="btn-custom <?php echo $btn_style_class; ?> text-sm sm:col-span-3 w-full">إضافة إحصائية</button>
            </form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <?php foreach ($stats as $st): ?>
                    <div class="flex items-center justify-between p-3.5 border border-[var(--border-color)] rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="text-lg dynamic-text"><i class="<?php echo htmlspecialchars($st['icon_class']); ?>"></i></span>
                            <div>
                                <h4 class="font-black text-sm"><?php echo htmlspecialchars($st['stat_number']); ?></h4>
                                <span class="text-xs text-[var(--text-muted)]"><?php echo htmlspecialchars($st['stat_title']); ?></span>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('حذف؟')">
                            <input type="hidden" name="stat_id" value="<?php echo $st['id']; ?>">
                            <button type="submit" name="delete_stat" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- المهارات -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إدارة المهارات ونسب التقدم</h2>
            <form method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <input type="text" name="skill_name" placeholder="اسم المهارة (مثال: PHP)" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="number" name="percentage" min="1" max="100" placeholder="النسبة المئوية (90)" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <button type="submit" name="add_skill" class="btn-custom <?php echo $btn_style_class; ?> text-sm sm:col-span-2 w-full">إضافة المهارة</button>
            </form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <?php foreach ($skills as $sk): ?>
                    <div class="flex items-center justify-between p-3.5 border border-[var(--border-color)] rounded-xl">
                        <div>
                            <h4 class="font-bold text-xs"><?php echo htmlspecialchars($sk['skill_name']); ?></h4>
                            <span class="text-xs dynamic-text font-bold"><?php echo (int)$sk['percentage']; ?>%</span>
                        </div>
                        <form method="POST" onsubmit="return confirm('حذف؟')">
                            <input type="hidden" name="skill_id" value="<?php echo $sk['id']; ?>">
                            <button type="submit" name="delete_skill" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- الخدمات -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إدارة الخدمات</h2>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <input type="text" name="title" placeholder="عنوان الخدمة" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="file" name="service_image" class="border border-[var(--border-color)] p-1.5 rounded-xl text-xs">
                <textarea name="description" placeholder="وصف الخدمة" rows="2" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none sm:col-span-2"></textarea>
                <button type="submit" name="add_service" class="btn-custom <?php echo $btn_style_class; ?> text-sm sm:col-span-2 w-full">إضافة الخدمة</button>
            </form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <?php foreach ($services as $srv): ?>
                    <div class="flex items-center justify-between p-3.5 border border-[var(--border-color)] rounded-xl">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($srv['image'])): ?>
                                <img src="uploads/<?php echo $srv['image']; ?>" class="w-10 h-10 rounded-lg object-cover">
                            <?php endif; ?>
                            <h4 class="font-bold text-xs"><?php echo htmlspecialchars($srv['title']); ?></h4>
                        </div>
                        <form method="POST" onsubmit="return confirm('حذف؟')">
                            <input type="hidden" name="service_id" value="<?php echo $srv['id']; ?>">
                            <button type="submit" name="delete_service" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- الأعمال -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إدارة الأعمال والمشاريع</h2>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <input type="text" name="title" placeholder="عنوان العمل" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="text" name="project_url" placeholder="رابط المشروع" class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="file" name="portfolio_image" class="border border-[var(--border-color)] p-1.5 rounded-xl text-xs sm:col-span-2">
                <textarea name="description" placeholder="وصف العمل" rows="2" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none sm:col-span-2"></textarea>
                <button type="submit" name="add_portfolio" class="btn-custom <?php echo $btn_style_class; ?> text-sm sm:col-span-2 w-full">إضافة العمل</button>
            </form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <?php foreach ($portfolio as $item): ?>
                    <div class="flex items-center justify-between p-3.5 border border-[var(--border-color)] rounded-xl">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($item['image'])): ?>
                                <img src="uploads/<?php echo $item['image']; ?>" class="w-10 h-10 rounded-lg object-cover">
                            <?php endif; ?>
                            <h4 class="font-bold text-xs"><?php echo htmlspecialchars($item['title']); ?></h4>
                        </div>
                        <form method="POST" onsubmit="return confirm('حذف؟')">
                            <input type="hidden" name="portfolio_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="delete_portfolio" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- آراء العملاء -->
        <div class="soft-card p-5 sm:p-8 space-y-6">
            <h2 class="text-base sm:text-lg font-bold border-b border-[var(--border-color)] pb-3">إدارة آراء العملاء</h2>
            <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <input type="text" name="client_name" placeholder="اسم العميل" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="text" name="client_role" placeholder="المسمى الوظيفي" class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none">
                <input type="file" name="client_image" class="border border-[var(--border-color)] p-1.5 rounded-xl text-xs sm:col-span-2">
                <textarea name="feedback" placeholder="رأي العميل..." rows="2" required class="border border-[var(--border-color)] bg-transparent p-2.5 rounded-xl text-sm outline-none sm:col-span-2"></textarea>
                <button type="submit" name="add_testimonial" class="btn-custom <?php echo $btn_style_class; ?> text-sm sm:col-span-2 w-full">إضافة الرأي</button>
            </form>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                <?php foreach ($testimonials as $t): ?>
                    <div class="flex items-center justify-between p-3.5 border border-[var(--border-color)] rounded-xl">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($t['client_image'])): ?>
                                <img src="uploads/<?php echo $t['client_image']; ?>" class="w-10 h-10 rounded-full object-cover">
                            <?php endif; ?>
                            <h4 class="font-bold text-xs"><?php echo htmlspecialchars($t['client_name']); ?></h4>
                        </div>
                        <form method="POST" onsubmit="return confirm('حذف؟')">
                            <input type="hidden" name="testimonial_id" value="<?php echo $t['id']; ?>">
                            <button type="submit" name="delete_testimonial" class="text-red-500 hover:text-red-700 p-2"><i class="fa-solid fa-trash text-sm"></i></button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script src="script.js"></script>
</body>
</html>