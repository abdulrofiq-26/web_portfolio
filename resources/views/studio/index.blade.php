@extends('layouts.studio')

@section('content')
<div class="w-full pb-12">
    
    <div class="border-b border-slate-300 dark:border-slate-800 pb-6 mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Portfolio Studio</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Manage your profile, experiences, projects, certificates, and skills easily.</p>
    </div>

    @if(session('success'))
        <div x-data x-init="setTimeout(() => window.dispatchEvent(new CustomEvent('toast', { detail: { message: '{{ session('success') }}', type: 'success' }})), 100)"></div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
        
        <!-- KOLOM KIRI: HOME & ABOUT -->
        <div class="space-y-8">
            
            <!-- 1. Home Section -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">1</span> 
                        Hero & Global Settings
                    </h2>
                </div>

                <form action="{{ route('studio.home.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 mb-6 pb-6 border-b border-slate-200 dark:border-slate-800" x-data="{ headlines: @js($user->headlines ?? []), visibility: @js($user->section_visibility ?? ['about' => true, 'skills' => true, 'projects' => true, 'certificates' => true, 'experiences' => true, 'contact' => true]) }">
                    @csrf
                    
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                        <label class="block text-sm font-bold text-slate-900 dark:text-white mb-3">Section Visibility Toggles</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <template x-for="(val, key) in visibility" :key="key">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" x-model="visibility[key]" class="w-4 h-4 text-accent border-slate-300 rounded focus:ring-accent">
                                    <span class="text-sm text-slate-700 dark:text-slate-300 capitalize" x-text="key"></span>
                                </label>
                            </template>
                        </div>
                        <input type="hidden" name="section_visibility" :value="JSON.stringify(visibility)">
                    </div>

                    <div x-data="photoCropper()">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Profile Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full overflow-hidden border border-slate-300 dark:border-slate-700 bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-slate-500 text-2xl font-bold shrink-0">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!previewUrl">
                                    @if($user->profile_photo)
                                        <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <span>{{ substr($user->name, 0, 1) }}</span>
                                    @endif
                                </template>
                            </div>
                            <input type="file" id="realProfileInput" name="profile_photo" accept="image/*" class="hidden">
                            <button type="button" @click="document.getElementById('fakeProfileInput').click()" class="text-sm bg-accent/10 hover:bg-accent/20 text-accent font-semibold py-2 px-4 rounded-full transition">Select & Crop Photo</button>
                            <input type="file" id="fakeProfileInput" accept="image/*" class="hidden" @change="openCropper">
                        </div>

                        <!-- Cropper Modal -->
                        <template x-teleport="body">
                            <div x-show="isCropping" class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-sm" x-cloak>
                                <div class="bg-white dark:bg-darkCard rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl flex flex-col">
                                    <div class="p-4 border-b border-slate-200 dark:border-slate-800 flex justify-between items-center">
                                        <h3 class="font-bold text-lg dark:text-white">Adjust Profile Photo</h3>
                                        <button type="button" @click="closeCropper" class="text-slate-400 hover:text-red-500">&times;</button>
                                    </div>
                                    <div class="w-full h-80 bg-slate-100 dark:bg-black relative">
                                        <img id="cropperImage" class="max-w-full block">
                                    </div>
                                    <div class="p-4 border-t border-slate-200 dark:border-slate-800 flex justify-end gap-3">
                                        <button type="button" @click="closeCropper" class="px-4 py-2 rounded-xl text-sm font-medium bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-slate-700 transition">Cancel</button>
                                        <button type="button" @click="cropImage" class="px-4 py-2 rounded-xl text-sm font-medium bg-accent hover:bg-indigo-600 text-white transition shadow-lg shadow-accent/30">Apply & Save</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Headlines / Expertise (Multiple allowed)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(hl, idx) in headlines" :key="idx">
                                <span class="px-3 py-1 bg-accent/10 text-accent text-sm rounded-full flex items-center gap-1 border border-accent/20">
                                    <span x-text="hl"></span>
                                    <button type="button" @click="headlines.splice(idx, 1)" class="hover:text-red-500 ml-1">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="newHeadline" placeholder="e.g. Data Scientist, type and press Enter" class="flex-1 bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all"
                                   @keydown.enter.prevent="if($event.target.value.trim()){ headlines.push($event.target.value.trim()); $event.target.value=''; }">
                            <button type="button" @click="let val = document.getElementById('newHeadline').value.trim(); if(val){ headlines.push(val); document.getElementById('newHeadline').value=''; }" class="bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 px-4 rounded-xl transition font-medium">Add</button>
                        </div>
                        <input type="hidden" name="headlines" :value="JSON.stringify(headlines)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Short Bio (Home)</label>
                        <textarea name="home_bio" rows="2" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">{{ $user->home_bio }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Years of Experience</label>
                            <input type="text" name="hero_experience_years" value="{{ $user->hero_experience_years }}" placeholder="e.g. 5" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Floating Icon</label>
                            <input type="text" name="hero_icon" value="{{ $user->hero_icon }}" list="hero-icon-options" placeholder="Search Tool / Language..." class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                            <datalist id="hero-icon-options">
                                @foreach(config('skills') as $key => $skillData)
                                    <option value="{{ $key }}">{{ $key }} ({{ $skillData['category'] }})</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2.5 rounded-xl transition shadow-lg shadow-accent/30 text-sm">Save Home Settings</button>
                    </div>
                </form>

                <!-- Social Media -->
                <div x-data="socialMediaManager(@js($user->socialMedias), '{{ route('studio.social-media.sync') }}')">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Social Media Links</h3>
                        <button type="button" @click="isEditing = !isEditing" class="text-xs px-3 py-1 rounded bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition" x-text="isEditing ? 'Done Editing' : 'Edit'"></button>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <template x-for="(social, index) in items" :key="index">
                            <div class="flex items-center justify-between bg-slate-100 dark:bg-slate-800 px-4 py-2 rounded-lg border border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-sm capitalize text-slate-800 dark:text-white" x-text="social.platform"></span>
                                    <a :href="social.url" target="_blank" class="text-xs text-accent truncate max-w-[150px] sm:max-w-[200px]" x-text="social.url"></a>
                                </div>
                                <div x-show="isEditing" class="flex gap-2">
                                    <button type="button" @click="editItem(index)" class="text-blue-500 hover:text-blue-600 p-1 bg-blue-50 dark:bg-blue-500/10 rounded-lg transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-1 bg-red-50 dark:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                        <p x-show="items.length === 0" class="text-xs text-slate-500 dark:text-slate-400">No social media added yet.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 mb-4">
                        <select x-model="newItem.platform" class="bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-3 py-2.5 text-slate-800 dark:text-white outline-none focus:border-accent transition-all text-sm">
                            <option value="github">GitHub</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="instagram">Instagram</option>
                            <option value="twitter">Twitter</option>
                            <option value="facebook">Facebook</option>
                        </select>
                        <input type="url" x-model="newItem.url" placeholder="https://..." class="flex-1 bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-2.5 text-slate-800 dark:text-white outline-none focus:border-accent transition-all text-sm">
                        <button type="button" @click="addItem" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white px-4 rounded-xl transition text-sm">Tambah Draft</button>
                    </div>

                    <div class="text-right">
                        <button type="button" @click="syncAll" :disabled="isSyncing" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2 rounded-xl transition shadow-lg shadow-accent/30 text-sm disabled:opacity-50" x-text="isSyncing ? 'Menyimpan...' : 'Simpan Social Media'"></button>
                    </div>
                </div>
            </div>

            <!-- 2. About Section -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">2</span> 
                        About Me
                    </h2>
                </div>

                <form action="{{ route('studio.about.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Location</label>
                            <input type="text" name="location" value="{{ $user->location }}" placeholder="e.g. Jakarta, Indonesia" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Availability</label>
                            <input type="text" name="availability" value="{{ $user->availability }}" placeholder="e.g. Available for freelance" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white focus:border-accent outline-none transition-all">
                        </div>
                    </div>
                    
                    <div x-data="quillEditor(`{!! addslashes($user->bio) !!}`)">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Biography (Bio)</label>
                        <div class="bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white">
                            <div x-ref="quill" class="min-h-[100px] border-none"></div>
                        </div>
                        <input type="hidden" name="bio" x-model="content">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Upload CV (PDF, Max 5MB)</label>
                        <div class="flex items-center gap-3">
                            <input type="file" name="cv_file" accept=".pdf" class="text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer transition">
                            @if($user->cv_path)
                                <a href="{{ asset('storage/' . $user->cv_path) }}" target="_blank" class="text-sm text-accent underline">View Current CV</a>
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2.5 rounded-xl transition shadow-lg shadow-accent/30 text-sm">Save About Settings</button>
                    </div>
                </form>
            </div>

            <!-- 5. Certificates Card -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50" x-data="certificateManager(@js($user->certificates), '{{ route('studio.certificate.sync') }}')">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">5</span> 
                        Certificates & Awards
                    </h2>
                    <button type="button" @click="isEditing = !isEditing" class="text-xs px-3 py-1 rounded bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-slate-700 dark:text-slate-300" x-text="isEditing ? 'Done Editing' : 'Edit'"></button>
                </div>

                <div class="mb-6 space-y-4">
                    <template x-for="(cert, index) in items" :key="index">
                        <div class="bg-white/40 dark:bg-[#0b0f19]/60 backdrop-blur-md border border-slate-200 dark:border-slate-700 p-4 rounded-xl flex gap-4 shadow-sm">
                            <div x-show="cert.image_path || cert.image" class="w-20 h-16 bg-slate-200 dark:bg-slate-800 rounded-lg overflow-hidden flex-shrink-0">
                                <img :src="cert.image ? URL.createObjectURL(cert.image) : '/storage/' + cert.image_path" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-slate-900 dark:text-white" x-text="cert.title"></h3>
                                    <div x-show="isEditing" class="flex gap-2">
                                        <button type="button" @click="editItem(index)" class="text-blue-500 hover:text-blue-600 p-1.5 bg-blue-50 dark:bg-blue-500/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-1.5 bg-red-50 dark:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <p class="text-sm text-accent font-medium" x-text="cert.issuer"></p>
                                <p class="text-xs text-slate-500 mt-1" x-text="formatDate(cert.date)"></p>
                            </div>
                        </div>
                    </template>
                    <p x-show="items.length === 0" class="text-xs text-slate-500 dark:text-slate-400">No certificates added yet.</p>
                </div>

                <div class="space-y-5 bg-white/30 dark:bg-[#0b0f19]/50 backdrop-blur-md p-5 rounded-xl border border-slate-300 dark:border-slate-800 border-dashed mb-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Add New Certificate (Draft)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <input type="text" x-model="newItem.title" placeholder="Certificate / Award Name" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <input type="text" x-model="newItem.issuer" placeholder="Issuer (e.g. Google, Coursera)" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Date Acquired</label>
                            <input type="date" x-model="newItem.date" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Preview Image (Optional)</label>
                            <input type="file" id="cert-file" accept="image/*" @change="handleFileChange($event)" class="text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer transition">
                        </div>
                    </div>
                    <div x-data="quillEditor(newItem.description)" x-effect="content = newItem.description" @quill-update="newItem.description = $event.detail">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <div class="bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white">
                            <div x-ref="quill" class="min-h-[100px] border-none"></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" @click="addItem" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium px-6 py-2 rounded-xl transition">Add to Draft</button>
                    </div>
                </div>

                <div class="text-right border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button type="button" @click="syncAll" :disabled="isSyncing" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2 rounded-xl transition shadow-lg shadow-accent/30 text-sm disabled:opacity-50" x-text="isSyncing ? 'Saving...' : 'Save All Certificates'"></button>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: SKILLS, PROJECTS, EXPERIENCES -->
        <div class="space-y-8">
            
            <!-- 3. Skills Card -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50" x-data="skillManager(@js($user->skills), @js(config('skills')), '{{ route('studio.skill.sync') }}')">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">3</span> 
                        Skills
                    </h2>
                    <button type="button" @click="isEditing = !isEditing" class="text-xs px-3 py-1 rounded bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-slate-700 dark:text-slate-300" x-text="isEditing ? 'Done Editing' : 'Edit'"></button>
                </div>

                <div class="flex flex-wrap gap-2 mb-6">
                    <template x-for="(skill, index) in items" :key="index">
                        <div class="px-4 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-full text-sm flex items-center gap-2 border border-slate-200 dark:border-slate-700 shadow-sm">
                            <i x-show="getIcon(skill.name)" :class="getIcon(skill.name) + ' text-lg'"></i>
                            <span x-text="skill.name"></span>
                            <button x-show="isEditing" type="button" @click="removeItem(index)" class="text-slate-400 hover:text-red-500 dark:text-slate-500 dark:hover:text-red-400 font-bold ml-1">&times;</button>
                        </div>
                    </template>
                    <p x-show="items.length === 0" class="text-xs text-slate-500 dark:text-slate-400">No skills added yet.</p>
                </div>

                <div class="flex gap-3 mb-4">
                    <input type="text" x-model="newItemName" list="skill-options" placeholder="Search Tools / Languages..." class="flex-1 bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                    <datalist id="skill-options">
                        @foreach(config('skills') as $key => $skillData)
                            <option value="{{ $key }}">{{ $key }} ({{ $skillData['category'] }})</option>
                        @endforeach
                    </datalist>
                    <button type="button" @click="addItem" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white px-6 rounded-xl transition">Add to Draft</button>
                </div>

                <div class="text-right border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button type="button" @click="syncAll" :disabled="isSyncing" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2 rounded-xl transition shadow-lg shadow-accent/30 text-sm disabled:opacity-50" x-text="isSyncing ? 'Saving...' : 'Save All Skills'"></button>
                </div>
            </div>

            <!-- 4. Projects Card -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50" x-data="projectManager(@js($user->projects), '{{ route('studio.project.sync') }}')">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">4</span> 
                        Projects
                    </h2>
                    <button type="button" @click="isEditing = !isEditing" class="text-xs px-3 py-1 rounded bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-slate-700 dark:text-slate-300" x-text="isEditing ? 'Done Editing' : 'Edit'"></button>
                </div>

                <div class="mb-6 space-y-4">
                    <template x-for="(proj, index) in items" :key="index">
                        <div class="bg-white/40 dark:bg-[#0b0f19]/60 backdrop-blur-md border border-slate-200 dark:border-slate-700 p-4 rounded-xl shadow-sm flex flex-col sm:flex-row gap-4">
                            <div x-show="proj.image_path || proj.image" class="w-full sm:w-32 h-24 bg-slate-200 dark:bg-slate-800 rounded-lg overflow-hidden flex-shrink-0">
                                <img :src="proj.image ? URL.createObjectURL(proj.image) : '/storage/' + proj.image_path" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h3 class="font-bold text-slate-900 dark:text-white" x-text="proj.title"></h3>
                                    <div x-show="isEditing" class="flex gap-2">
                                        <button type="button" @click="editItem(index)" class="text-blue-500 hover:text-blue-600 p-1.5 bg-blue-50 dark:bg-blue-500/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-1.5 bg-red-50 dark:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1" x-html="proj.description"></div>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <template x-for="tech in (Array.isArray(proj.tech_stack) ? proj.tech_stack : (typeof proj.tech_stack === 'string' ? proj.tech_stack.split(',').map(s=>s.trim()) : []))" :key="tech">
                                        <span class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 px-2 py-1 rounded border border-slate-200 dark:border-slate-700 shadow-sm" x-text="tech"></span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>
                    <p x-show="items.length === 0" class="text-xs text-slate-500 dark:text-slate-400">No projects added yet.</p>
                </div>

                <div class="space-y-5 bg-white/30 dark:bg-[#0b0f19]/50 backdrop-blur-md p-5 rounded-xl border border-slate-300 dark:border-slate-800 border-dashed mb-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Add New Project (Draft)</p>
                    <div>
                        <input type="text" x-model="newItem.title" placeholder="Project Name" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                    </div>
                    <div x-data="quillEditor(newItem.description)" x-effect="content = newItem.description" @quill-update="newItem.description = $event.detail">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <div class="bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white">
                            <div x-ref="quill" class="min-h-[100px] border-none"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="text-xs text-slate-500 block mb-1">Tech Stack</label>
                            <div class="flex flex-wrap gap-2 mb-2">
                                <template x-for="(tech, tIdx) in (Array.isArray(newItem.tech_stack) ? newItem.tech_stack : [])" :key="tIdx">
                                    <span class="px-2 py-1 bg-slate-200 dark:bg-slate-800 text-xs rounded flex items-center gap-1">
                                        <span x-text="tech"></span>
                                        <button type="button" @click="newItem.tech_stack.splice(tIdx, 1)" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                    </span>
                                </template>
                            </div>
                            <div class="flex gap-2">
                                <input type="text" id="newTechInput" list="project-skill-options" placeholder="Select from skill list..." class="flex-1 bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all text-sm"
                                       @keydown.enter.prevent="let val = $event.target.value.trim(); if(val){ if(!Array.isArray(newItem.tech_stack)) newItem.tech_stack = []; newItem.tech_stack.push(val); $event.target.value=''; }">
                                <button type="button" @click="let val = document.getElementById('newTechInput').value.trim(); if(val){ if(!Array.isArray(newItem.tech_stack)) newItem.tech_stack = []; newItem.tech_stack.push(val); document.getElementById('newTechInput').value=''; }" class="bg-slate-200 dark:bg-slate-700 px-3 rounded-xl transition text-sm font-medium">Add</button>
                            </div>
                            <datalist id="project-skill-options">
                                @foreach(config('skills') as $key => $skillData)
                                    <option value="{{ $key }}">{{ $key }} ({{ $skillData['category'] }})</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Live Demo URL</label>
                            <input type="url" x-model="newItem.project_url" placeholder="https://..." class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">GitHub URL</label>
                            <input type="url" x-model="newItem.github_url" placeholder="https://github.com/..." class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs text-slate-500 block mb-1">Preview Image (Optional)</label>
                            <input type="file" id="project-file" accept="image/*" @change="handleFileChange($event)" class="text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent/10 file:text-accent hover:file:bg-accent/20 cursor-pointer transition">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" @click="addItem" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium px-6 py-2 rounded-xl transition">Add to Draft</button>
                    </div>
                </div>

                <div class="text-right border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button type="button" @click="syncAll" :disabled="isSyncing" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2 rounded-xl transition shadow-lg shadow-accent/30 text-sm disabled:opacity-50" x-text="isSyncing ? 'Saving...' : 'Save All Projects'"></button>
                </div>
            </div>

            <!-- 6. Experiences Card -->
            <div class="bg-white/30 dark:bg-cardBg/40 backdrop-blur-2xl border border-white/80 dark:border-white/10 rounded-2xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-black/50" x-data="experienceManager(@js($user->experiences), '{{ route('studio.experience.sync') }}')">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center text-sm">6</span> 
                        Experiences
                    </h2>
                    <button type="button" @click="isEditing = !isEditing" class="text-xs px-3 py-1 rounded bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition text-slate-700 dark:text-slate-300" x-text="isEditing ? 'Done Editing' : 'Edit'"></button>
                </div>

                <div class="mb-6 space-y-4">
                    <template x-for="(exp, index) in items" :key="index">
                        <div class="bg-white/40 dark:bg-[#0b0f19]/60 backdrop-blur-md border border-slate-200 dark:border-slate-700 p-4 rounded-xl flex justify-between items-start shadow-sm">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white" x-text="exp.company_name"></h3>
                                <p class="text-sm text-accent font-medium" x-text="exp.role"></p>
                                <p class="text-xs text-slate-500 mt-1">
                                    <span x-text="formatDate(exp.start_date)"></span> - 
                                    <span x-text="exp.end_date ? formatDate(exp.end_date) : 'Present'"></span>
                                </p>
                                <div class="text-sm text-slate-600 dark:text-slate-400 mt-2" x-html="exp.description"></div>
                            </div>
                            <div x-show="isEditing" class="flex gap-2 ml-4">
                                        <button type="button" @click="editItem(index)" class="text-blue-500 hover:text-blue-600 p-1.5 bg-blue-50 dark:bg-blue-500/10 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-600 p-1.5 bg-red-50 dark:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                            </div>
                        </div>
                    </template>
                    <p x-show="items.length === 0" class="text-xs text-slate-500 dark:text-slate-400">No experiences added yet.</p>
                </div>

                <div class="space-y-5 bg-white/30 dark:bg-[#0b0f19]/50 backdrop-blur-md p-5 rounded-xl border border-slate-300 dark:border-slate-800 border-dashed mb-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-2">Add New Experience (Draft)</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <input type="text" x-model="newItem.company_name" placeholder="Company Name" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <input type="text" x-model="newItem.role" placeholder="Position / Role" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">Start Date</label>
                            <input type="date" x-model="newItem.start_date" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-1">End Date (Leave blank if present)</label>
                            <input type="date" x-model="newItem.end_date" class="w-full bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3 text-slate-800 dark:text-white outline-none focus:border-accent transition-all">
                        </div>
                    </div>
                    <div x-data="quillEditor(newItem.description)" x-effect="content = newItem.description" @quill-update="newItem.description = $event.detail">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Description</label>
                        <div class="bg-white/50 dark:bg-[#0b0f19]/50 backdrop-blur-sm border border-slate-300 dark:border-slate-700 rounded-xl text-slate-800 dark:text-white">
                            <div x-ref="quill" class="min-h-[100px] border-none"></div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" @click="addItem" class="bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-medium px-6 py-2 rounded-xl transition">Add to Draft</button>
                    </div>
                </div>

                <div class="text-right border-t border-slate-200 dark:border-slate-800 pt-4">
                    <button type="button" @click="syncAll" :disabled="isSyncing" class="bg-accent hover:bg-indigo-600 text-white font-medium px-6 py-2 rounded-xl transition shadow-lg shadow-accent/30 text-sm disabled:opacity-50" x-text="isSyncing ? 'Saving...' : 'Save All Experiences'"></button>
                </div>
            </div>

        </div> 
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        
        Alpine.data('photoCropper', () => ({
            isCropping: false,
            cropper: null,
            previewUrl: null,
            openCropper(e) {
                const file = e.target.files[0];
                if(!file) return;
                
                this.isCropping = true;
                const url = URL.createObjectURL(file);
                
                this.$nextTick(() => {
                    const img = document.getElementById('cropperImage');
                    img.src = url;
                    
                    if (this.cropper) {
                        this.cropper.destroy();
                    }
                    
                    this.cropper = new Cropper(img, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        guides: true,
                        center: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                    });
                });
            },
            closeCropper() {
                this.isCropping = false;
                if(this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                document.getElementById('fakeProfileInput').value = '';
            },
            cropImage() {
                if(!this.cropper) return;
                
                const canvas = this.cropper.getCroppedCanvas({
                    width: 500,
                    height: 500
                });
                
                canvas.toBlob((blob) => {
                    this.previewUrl = URL.createObjectURL(blob);
                    
                    const file = new File([blob], "profile_cropped.jpg", { type: "image/jpeg", lastModified: new Date().getTime() });
                    const container = new DataTransfer();
                    container.items.add(file);
                    document.getElementById('realProfileInput').files = container.files;
                    
                    this.closeCropper();
                }, 'image/jpeg', 0.9);
            }
        }));
        
        Alpine.data('socialMediaManager', (initialData, syncUrl) => ({
            items: initialData || [],
            isEditing: false,
            isSyncing: false,
            newItem: { platform: 'github', url: '' },
            addItem() {
                if(!this.newItem.url) return window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'URL harus diisi!', type: 'error' } }));
                this.items.push({ ...this.newItem });
                this.newItem = { platform: 'github', url: '' };
            },
            editItem(index) {
                this.newItem = { ...this.items[index] };
                this.items.splice(index, 1);
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            async syncAll() {
                this.isSyncing = true;
                try {
                    let res = await fetch(syncUrl, { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ payload: JSON.stringify(this.items) })
                    });
                    if(res.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Social Media berhasil disimpan!' } }));
                } catch(e) {}
                this.isSyncing = false;
            }
        }));

        Alpine.data('skillManager', (initialData, configSkills, syncUrl) => ({
            items: initialData || [],
            isEditing: false,
            isSyncing: false,
            newItemName: '',
            configSkills: configSkills,
            getIcon(name) {
                return this.configSkills[name] ? this.configSkills[name].icon : null;
            },
            addItem() {
                if(!this.newItemName) return;
                if(this.items.find(i => i.name === this.newItemName)) return window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Skill sudah ditambahkan ke draft!', type: 'error' } }));
                this.items.push({ name: this.newItemName });
                this.newItemName = '';
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            async syncAll() {
                this.isSyncing = true;
                try {
                    let res = await fetch(syncUrl, { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ payload: JSON.stringify(this.items) })
                    });
                    if(res.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Skills berhasil disimpan!' } }));
                } catch(e) {}
                this.isSyncing = false;
            }
        }));

        Alpine.data('projectManager', (initialData, syncUrl) => ({
            items: initialData || [],
            isEditing: false,
            isSyncing: false,
            newItem: { title: '', description: '', tech_stack: '', project_url: '', github_url: '', image: null, image_path: null },
            
            handleFileChange(e) {
                if(e.target.files.length > 0) this.newItem.image = e.target.files[0];
            },
            addItem() {
                if(!this.newItem.title || !this.newItem.description || !this.newItem.tech_stack) return window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Nama, Deskripsi, dan Tech Stack wajib diisi!', type: 'error' } }));
                let techStack = typeof this.newItem.tech_stack === 'string' 
                    ? this.newItem.tech_stack.split(',').map(s => s.trim()) 
                    : this.newItem.tech_stack;
                this.items.push({ 
                    ...this.newItem, 
                    tech_stack: techStack 
                });
                this.newItem = { title: '', description: '', tech_stack: '', project_url: '', github_url: '', image: null, image_path: null };
                document.getElementById('project-file').value = '';
            },
            editItem(index) {
                let item = this.items[index];
                this.newItem = { 
                    ...item,
                    tech_stack: Array.isArray(item.tech_stack) ? item.tech_stack.join(', ') : item.tech_stack
                };
                this.items.splice(index, 1);
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            async syncAll() {
                this.isSyncing = true;
                const formData = new FormData();
                this.items.forEach((item, index) => {
                    formData.append(`projects[${index}][title]`, item.title);
                    formData.append(`projects[${index}][description]`, item.description);
                    if (item.project_url) formData.append(`projects[${index}][project_url]`, item.project_url);
                    if (item.github_url) formData.append(`projects[${index}][github_url]`, item.github_url);
                    if (item.image_path) formData.append(`projects[${index}][image_path]`, item.image_path);
                    if (item.image instanceof File) formData.append(`projects[${index}][image]`, item.image);
                    
                    item.tech_stack.forEach((ts, tIndex) => {
                        formData.append(`projects[${index}][tech_stack][${tIndex}]`, ts);
                    });
                });
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    let res = await fetch(syncUrl, { method: 'POST', body: formData });
                    if(res.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Projects berhasil disimpan!' } }));
                } catch(e) {}
                this.isSyncing = false;
            }
        }));

        Alpine.data('certificateManager', (initialData, syncUrl) => ({
            items: initialData || [],
            isEditing: false,
            isSyncing: false,
            newItem: { title: '', issuer: '', date: '', description: '', image: null, image_path: null },
            handleFileChange(e) {
                if(e.target.files.length > 0) this.newItem.image = e.target.files[0];
            },
            addItem() {
                if(!this.newItem.title || !this.newItem.issuer || !this.newItem.date) return window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Field wajib diisi!', type: 'error' } }));
                this.items.push({ ...this.newItem });
                this.newItem = { title: '', issuer: '', date: '', description: '', image: null, image_path: null };
                document.getElementById('cert-file').value = '';
            },
            editItem(index) {
                this.newItem = { ...this.items[index] };
                this.items.splice(index, 1);
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            async syncAll() {
                this.isSyncing = true;
                const formData = new FormData();
                this.items.forEach((item, index) => {
                    formData.append(`certificates[${index}][title]`, item.title);
                    formData.append(`certificates[${index}][issuer]`, item.issuer);
                    formData.append(`certificates[${index}][date]`, item.date);
                    if (item.description) formData.append(`certificates[${index}][description]`, item.description);
                    if (item.image_path) formData.append(`certificates[${index}][image_path]`, item.image_path);
                    if (item.image instanceof File) formData.append(`certificates[${index}][image]`, item.image);
                });
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    let res = await fetch(syncUrl, { method: 'POST', body: formData });
                    if(res.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Sertifikat berhasil disimpan!' } }));
                } catch(e) {}
                this.isSyncing = false;
            }
        }));

        Alpine.data('experienceManager', (initialData, syncUrl) => ({
            items: initialData || [],
            isEditing: false,
            isSyncing: false,
            newItem: { company_name: '', role: '', start_date: '', end_date: '', description: '' },
            addItem() {
                if(!this.newItem.company_name || !this.newItem.role || !this.newItem.start_date || !this.newItem.description) return window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Nama Perusahaan, Posisi, Mulai, dan Deskripsi wajib diisi!', type: 'error' } }));
                this.items.push({ ...this.newItem });
                this.newItem = { company_name: '', role: '', start_date: '', end_date: '', description: '' };
            },
            editItem(index) {
                this.newItem = { ...this.items[index] };
                this.items.splice(index, 1);
            },
            removeItem(index) {
                this.items.splice(index, 1);
            },
            async syncAll() {
                this.isSyncing = true;
                try {
                    let res = await fetch(syncUrl, { 
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ payload: JSON.stringify(this.items) })
                    });
                    if(res.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Experiences berhasil disimpan!' } }));
                } catch(e) {}
                this.isSyncing = false;
            }
        }));
    });
</script>
@endsection