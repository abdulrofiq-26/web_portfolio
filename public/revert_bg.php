<?php
$path = __DIR__ . '/../resources/views/portfolio.blade.php';
$content = file_get_contents($path);

// Replace back bg-lightBg to bg-slate-50
$content = preg_replace('/\bbg-lightBg\b/', 'bg-slate-50', $content);
// Replace back bg-lightCard to bg-white
$content = preg_replace('/\bbg-lightCard\b/', 'bg-white', $content);

// Update tailwind config block back to original
$config_regex = "/(function darkenHex.*?<\/script>)/s";
if (preg_match("/(function darkenHex[\s\S]*?)(tailwind\.config\s*=\s*{[\s\S]*?colors:\s*{)([\s\S]*?)(\},[\s\S]*?}\s*\n\s*<\/script>)/", $content, $matches)) {
    // Remove darkenHex function and replace colors
    $new_colors = "
                        darkBg: '#0f172a', // slate-900
                        darkCard: '#1e293b', // slate-800
                        accent: '{{ \$user->primary_color ?? \"#4f46e5\" }}', 
                        accentDark: '{{ \$user->secondary_color ?? \"#4338ca\" }}', ";
    
    $content = str_replace($matches[3], $new_colors, $content);
    $content = str_replace($matches[1], "", $content);
}

file_put_contents($path, $content);

$path_studio = __DIR__ . '/../resources/views/layouts/studio.blade.php';
$content = file_get_contents($path_studio);
if (preg_match("/(function darkenHex[\s\S]*?)(tailwind\.config\s*=\s*{[\s\S]*?colors:\s*{)([\s\S]*?)(\}[\s\S]*?}\s*\n\s*<\/script>)/", $content, $matches)) {
    $new_colors = "
                        darkBg: '#0b0f19', // Warna latar belakang gelap khas referensimu
                        cardBg: '#111827', // Warna kartu
                        accent: '{{ auth()->user()->primary_color ?? \"#6366f1\" }}', ";
    $content = str_replace($matches[3], $new_colors, $content);
    $content = str_replace($matches[1], "", $content);
    file_put_contents($path_studio, $content);
}

$path_auth = __DIR__ . '/../resources/views/layouts/auth.blade.php';
$content = file_get_contents($path_auth);
if (preg_match("/(function darkenHex[\s\S]*?)(tailwind\.config\s*=\s*{[\s\S]*?colors:\s*{)([\s\S]*?)(\}[\s\S]*?}\s*\n\s*<\/script>)/", $content, $matches)) {
    $new_colors = "
                        darkBg: '#0b0f19', // Warna latar belakang gelap
                        cardBg: '#111827', // Warna kartu
                        accent: '{{ \App\Models\User::first()->primary_color ?? \"#6366f1\" }}', ";
    $content = str_replace($matches[3], $new_colors, $content);
    $content = str_replace($matches[1], "", $content);
    file_put_contents($path_auth, $content);
}

echo "OK";
