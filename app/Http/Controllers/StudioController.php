<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudioController extends Controller
{
    // Menampilkan halaman Studio
    public function index()
    {
        // Memuat user beserta data relasinya (pengalaman, proyek, skill)
        $user = Auth::user()->load(['experiences', 'projects', 'skills']);
        return view('studio.index', compact('user'));
    }

    // ================= HOME =================
    public function updateHome(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'headlines' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'home_bio' => 'nullable|string',
            'hero_experience_years' => 'nullable|string|max:50',
            'hero_icon' => 'nullable|string|max:255',
            'section_visibility' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('phone', 'home_bio', 'hero_experience_years', 'hero_icon');
        if ($request->has('headlines')) {
            $data['headlines'] = json_decode($request->headlines, true) ?? [];
        }
        if ($request->has('section_visibility')) {
            $data['section_visibility'] = json_decode($request->section_visibility, true) ?? [];
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('profiles', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Home section berhasil diperbarui!');
    }

    public function storeSocialMedia(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:255',
        ]);

        Auth::user()->socialMedias()->create($request->only('platform', 'url'));
        return back()->with('success', 'Social media berhasil ditambahkan!');
    }

    public function destroySocialMedia($id)
    {
        Auth::user()->socialMedias()->findOrFail($id)->delete();
        return back()->with('success', 'Social media berhasil dihapus!');
    }

    // ================= ABOUT =================
    public function updateAbout(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'location' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'cv_file' => 'nullable|mimes:pdf|max:5120', // Max 5MB PDF
        ]);

        $data = $request->only('name', 'email', 'location', 'availability', 'bio');

        if ($request->hasFile('cv_file')) {
            if ($user->cv_path && Storage::disk('public')->exists($user->cv_path)) {
                Storage::disk('public')->delete($user->cv_path);
            }
            $data['cv_path'] = $request->file('cv_file')->store('cvs', 'public');
        }

        $user->update($data);
        return back()->with('success', 'About section berhasil diperbarui!');
    }

    // ================= PENGALAMAN =================
    public function storeExperience(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'required|string',
        ]);

        Auth::user()->experiences()->create($request->all());
        return back()->with('success', 'Pengalaman berhasil ditambahkan!');
    }

    public function destroyExperience($id)
    {
        Auth::user()->experiences()->findOrFail($id)->delete();
        return back()->with('success', 'Pengalaman berhasil dihapus!');
    }

    // ================= PROYEK =================
    public function storeProject(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tech_stack' => 'required|string',
            'project_url' => 'nullable|url',
        ]);

        // Mengubah string pisah koma menjadi array ("Laravel, Python" -> ["Laravel", "Python"])
        $techArray = array_map('trim', explode(',', $request->tech_stack));

        Auth::user()->projects()->create([
            'title' => $request->title,
            'description' => $request->description,
            'tech_stack' => $techArray,
            'project_url' => $request->project_url,
        ]);

        return back()->with('success', 'Proyek berhasil ditambahkan!');
    }

    public function destroyProject($id)
    {
        Auth::user()->projects()->findOrFail($id)->delete();
        return back()->with('success', 'Proyek berhasil dihapus!');
    }

    // ================= KEAHLIAN (SKILL) =================
    public function storeSkill(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = config("skills.{$request->name}.category") ?? 'General';

        Auth::user()->skills()->create([
            'name' => $request->name,
            'category' => $category,
        ]);

        return back()->with('success', 'Keahlian berhasil ditambahkan!');
    }

    public function destroySkill($id)
    {
        Auth::user()->skills()->findOrFail($id)->delete();
        return back()->with('success', 'Keahlian berhasil dihapus!');
    }

    // ================= SYNC (BULK UPDATE) =================
    public function syncSocialMedia(Request $request)
    {
        $items = json_decode($request->payload, true) ?? [];
        
        $user = Auth::user();
        $user->socialMedias()->delete(); // clear existing
        
        foreach ($items as $item) {
            $user->socialMedias()->create([
                'platform' => $item['platform'],
                'url' => $item['url']
            ]);
        }
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Social Media berhasil diperbarui!']);
        }
        return back()->with('success', 'Social Media berhasil diperbarui!');
    }

    public function syncExperience(Request $request)
    {
        $items = json_decode($request->payload, true) ?? [];
        
        $user = Auth::user();
        $user->experiences()->delete();
        
        foreach ($items as $item) {
            $user->experiences()->create([
                'company_name' => $item['company_name'],
                'role' => $item['role'],
                'start_date' => $item['start_date'],
                'end_date' => !empty($item['end_date']) ? $item['end_date'] : null,
                'description' => $item['description']
            ]);
        }
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Experiences berhasil diperbarui!']);
        }
        return back()->with('success', 'Experiences berhasil diperbarui!');
    }

    public function syncProject(Request $request)
    {
        // Support both JSON payload (old way) and direct array (FormData)
        $items = [];
        if ($request->has('payload')) {
            $items = json_decode($request->payload, true) ?? [];
        } else {
            $items = $request->input('projects', []);
        }
        
        $user = Auth::user();
        
        // Retrieve old images so we don't lose them if not updated
        $oldProjects = $user->projects->keyBy('title');
        
        $user->projects()->delete();
        
        foreach ($items as $index => $item) {
            $imagePath = null;
            
            // Check if there's a file upload for this index
            if ($request->hasFile("projects.{$index}.image")) {
                $imagePath = $request->file("projects.{$index}.image")->store('projects', 'public');
            } elseif (isset($item['image_path']) && !empty($item['image_path'])) {
                // Keep the old image path if it exists and no new file is uploaded
                $imagePath = $item['image_path'];
            }

            $user->projects()->create([
                'title' => $item['title'],
                'description' => $item['description'],
                'image_path' => $imagePath,
                'tech_stack' => is_array($item['tech_stack']) ? $item['tech_stack'] : array_map('trim', explode(',', $item['tech_stack'])),
                'project_url' => $item['project_url'] ?? null,
                'github_url' => $item['github_url'] ?? null
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Projects berhasil diperbarui!']);
    }

    public function syncCertificate(Request $request)
    {
        $items = [];
        if ($request->has('payload')) {
            $items = json_decode($request->payload, true) ?? [];
        } else {
            $items = $request->input('certificates', []);
        }
        
        $user = Auth::user();
        $user->certificates()->delete();
        
        foreach ($items as $index => $item) {
            $imagePath = null;
            if ($request->hasFile("certificates.{$index}.image")) {
                $imagePath = $request->file("certificates.{$index}.image")->store('certificates', 'public');
            } elseif (isset($item['image_path']) && !empty($item['image_path'])) {
                $imagePath = $item['image_path'];
            }

            $user->certificates()->create([
                'title' => $item['title'],
                'issuer' => $item['issuer'],
                'date' => $item['date'],
                'description' => $item['description'] ?? null,
                'image_path' => $imagePath,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Sertifikat berhasil diperbarui!']);
    }

    public function syncSkill(Request $request)
    {
        $items = json_decode($request->payload, true) ?? [];
        
        $user = Auth::user();
        $user->skills()->delete();
        
        foreach ($items as $item) {
            $category = config("skills.{$item['name']}.category") ?? 'General';
            $user->skills()->create([
                'name' => $item['name'],
                'category' => $category,
            ]);
        }
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Skills berhasil diperbarui!']);
        }
        return back()->with('success', 'Skills berhasil diperbarui!');
    }
}