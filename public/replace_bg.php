<?php
function darken_hex_js() {
    return "
        function darkenHex(hex, percent) {
            if(!hex) return '';
            hex = hex.replace(/^#/, '');
            if (hex.length === 3) hex = hex.split('').map(x => x + x).join('');
            let r = parseInt(hex.substring(0, 2), 16);
            let g = parseInt(hex.substring(2, 4), 16);
            let b = parseInt(hex.substring(4, 6), 16);
            
            r = Math.max(0, Math.floor(r * (1 - percent)));
            g = Math.max(0, Math.floor(g * (1 - percent)));
            b = Math.max(0, Math.floor(b * (1 - percent)));
            
            return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
        }
";
}

$path = __DIR__ . '/../resources/views/portfolio.blade.php';
$content = file_get_contents($path);

// Replace bg-slate-50 with bg-lightBg
$content = preg_replace('/\bbg-slate-50\b/', 'bg-lightBg', $content);
// Replace bg-white with bg-lightCard
$content = preg_replace('/\bbg-white\b/', 'bg-lightCard', $content);

// Update tailwind config block
$config_regex = "/(tailwind\.config\s*=\s*{[\s\S]*?colors:\s*{)([\s\S]*?)(\},[\s\S]*?}\s*\n\s*<\/script>)/";
if (preg_match($config_regex, $content, $matches)) {
    $new_colors = "
                        lightBg: '{{ \$user->bg_color ?? \"#f8fafc\" }}',
                        lightCard: '{{ \$user->bg_color_alt ?? \"#ffffff\" }}',
                        darkBg: darkenHex('{{ \$user->bg_color ?? \"#f8fafc\" }}', 0.8),
                        darkCard: darkenHex('{{ \$user->bg_color_alt ?? \"#ffffff\" }}', 0.8),
                        accent: '{{ \$user->primary_color ?? \"#4f46e5\" }}', 
                        accentDark: '{{ \$user->secondary_color ?? \"#4338ca\" }}', ";
    
    $content = str_replace($matches[2], $new_colors, $content);
    $content = str_replace("tailwind.config =", darken_hex_js() . "        tailwind.config =", $content);
}

file_put_contents($path, $content);

$path_studio = __DIR__ . '/../resources/views/layouts/studio.blade.php';
$content = file_get_contents($path_studio);
if (preg_match($config_regex, $content, $matches)) {
    $new_colors = "
                        darkBg: darkenHex('{{ auth()->user()->bg_color ?? \"#f8fafc\" }}', 0.8),
                        cardBg: darkenHex('{{ auth()->user()->bg_color_alt ?? \"#ffffff\" }}', 0.8),
                        accent: '{{ auth()->user()->primary_color ?? \"#6366f1\" }}', ";
    $content = str_replace($matches[2], $new_colors, $content);
    $content = str_replace("tailwind.config =", darken_hex_js() . "        tailwind.config =", $content);
    file_put_contents($path_studio, $content);
}

$path_auth = __DIR__ . '/../resources/views/layouts/auth.blade.php';
$content = file_get_contents($path_auth);
if (preg_match($config_regex, $content, $matches)) {
    $new_colors = "
                        darkBg: darkenHex('{{ \App\Models\User::first()->bg_color ?? \"#f8fafc\" }}', 0.8),
                        cardBg: darkenHex('{{ \App\Models\User::first()->bg_color_alt ?? \"#ffffff\" }}', 0.8),
                        accent: '{{ \App\Models\User::first()->primary_color ?? \"#6366f1\" }}', ";
    $content = str_replace($matches[2], $new_colors, $content);
    $content = str_replace("tailwind.config =", darken_hex_js() . "        tailwind.config =", $content);
    file_put_contents($path_auth, $content);
}

echo "OK";
