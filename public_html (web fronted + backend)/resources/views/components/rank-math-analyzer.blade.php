{{-- Rank Math SEO Real-Time Analyzer Component --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300" id="rank-math-container">
    {{-- Header Widget --}}
    <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center text-purple-700 shadow-sm">
                <i class="fas fa-chart-line text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                    Rank Math SEO
                    <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded-full font-semibold border border-purple-200">Live</span>
                </h3>
                <p class="text-[11px] text-gray-400">Audit otomatis kualitas SEO konten Anda</p>
            </div>
        </div>
        
        {{-- Skor Badge Live --}}
        <div id="rm-score-badge" class="px-3.5 py-1.5 rounded-xl text-xs font-black bg-red-50 text-red-600 border border-red-200 flex items-center gap-2 transition-all">
            <span id="rm-score-number" class="text-base font-extrabold">0</span>
            <span class="text-[10px] text-gray-400 font-normal">/100</span>
            <span id="rm-score-label" class="text-[10px] px-1.5 py-0.5 rounded bg-red-100 uppercase tracking-wider font-bold">POOR</span>
        </div>
    </div>

    {{-- Body Widget --}}
    <div class="p-5 space-y-4">
        {{-- Input Focus Keyword --}}
        <div>
            <label class="block text-xs font-bold text-gray-700 mb-1.5 flex items-center justify-between">
                <span><i class="fas fa-key text-purple-600 mr-1.5"></i> Focus Keyword (Kata Kunci Sasaran)</span>
                <span class="text-[10px] text-purple-600 font-semibold cursor-pointer hover:underline" onclick="rmAutoDetectKeyword()">Otomatis dari Judul</span>
            </label>
            <div class="relative">
                <input type="text" id="rm-focus-keyword" placeholder="contoh: supplier kecap manis jerigen"
                    class="w-full pl-9 pr-4 py-2.5 text-xs font-medium border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition bg-gray-50 focus:bg-white">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-xs"></i>
            </div>
            <p class="text-[10px] text-gray-400 mt-1 italic">*Keyword utama yang ingin diranking di halaman 1 Google.</p>
        </div>

        {{-- Passing Score Notice --}}
        <div id="rm-passing-notice" class="p-3 rounded-xl text-xs bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-2.5">
            <i class="fas fa-info-circle text-amber-600 mt-0.5 text-sm"></i>
            <div class="text-[11px]">
                <strong>Ambang Lolos: Minimal Skor 80 (Hijau)</strong> agar artikel memiliki daya saing kuat di Google SERP sebelum diterbitkan.
            </div>
        </div>

        {{-- Accordion Checklist --}}
        <div class="space-y-2 pt-1" id="rm-checklist-accordion">
            {{-- 1. Basic SEO --}}
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button type="button" class="w-full px-4 py-2.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between text-left text-xs font-bold text-gray-700 transition" onclick="rmToggleSection('rm-sec-basic')">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-shield-alt text-purple-600"></i>
                        1. Basic SEO (Fondasi)
                    </span>
                    <span id="rm-count-basic" class="text-[11px] font-semibold text-gray-500">0/40 Poin</span>
                </button>
                <div id="rm-sec-basic" class="p-3 space-y-2 text-xs bg-white">
                    <div id="chk-kw-title" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di Judul SEO (H1) <b class="text-gray-400">(+8)</b></span>
                    </div>
                    <div id="chk-kw-slug" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di Permalink / Slug URL <b class="text-gray-400">(+8)</b></span>
                    </div>
                    <div id="chk-kw-intro" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di Paragraf Pembuka (10% Awal) <b class="text-gray-400">(+8)</b></span>
                    </div>
                    <div id="chk-kw-content" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword ditemukan dalam isi artikel <b class="text-gray-400">(+8)</b></span>
                    </div>
                    <div id="chk-word-count" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Panjang Konten: <span id="rm-word-val">0</span> kata (Target: ≥600 / ≥1000 kata) <b class="text-gray-400">(+8)</b></span>
                    </div>
                </div>
            </div>

            {{-- 2. Additional SEO --}}
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button type="button" class="w-full px-4 py-2.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between text-left text-xs font-bold text-gray-700 transition" onclick="rmToggleSection('rm-sec-additional')">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-plus-circle text-blue-600"></i>
                        2. Additional SEO (Optimasi)
                    </span>
                    <span id="rm-count-additional" class="text-[11px] font-semibold text-gray-500">0/30 Poin</span>
                </button>
                <div id="rm-sec-additional" class="p-3 space-y-2 text-xs bg-white">
                    <div id="chk-kw-heading" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di Sub-Heading (H2 / H3) <b class="text-gray-400">(+6)</b></span>
                    </div>
                    <div id="chk-kw-image" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di ALT Text / File Gambar Produk <b class="text-gray-400">(+6)</b></span>
                    </div>
                    <div id="chk-kw-density" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Kerapatan Keyword: <span id="rm-density-val">0%</span> (Ideal: 1.0% - 2.0%) <b class="text-gray-400">(+6)</b></span>
                    </div>
                    <div id="chk-slug-len" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Panjang URL Slug Singkat (≤ 75 karakter) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-internal-link" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Tautan Internal ke Produk (<code class="text-[10px] text-purple-600">/products/..</code>) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-external-link" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Tautan Otoritas / WhatsApp CS Resmi <b class="text-gray-400">(+4)</b></span>
                    </div>
                </div>
            </div>

            {{-- 3. Title Readability --}}
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button type="button" class="w-full px-4 py-2.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between text-left text-xs font-bold text-gray-700 transition" onclick="rmToggleSection('rm-sec-title')">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-font text-green-600"></i>
                        3. Title Readability (Daya Tarik Judul)
                    </span>
                    <span id="rm-count-title" class="text-[11px] font-semibold text-gray-500">0/15 Poin</span>
                </button>
                <div id="rm-sec-title" class="p-3 space-y-2 text-xs bg-white">
                    <div id="chk-kw-start" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Keyword di Paruh Awal Judul <b class="text-gray-400">(+5)</b></span>
                    </div>
                    <div id="chk-sentiment" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Kata Sentimen Menarik (Murah, Juara, Terbaik, Untung) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-powerword" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Power Word (Supplier, Grosir, Pabrik, Panduan, Solusi) <b class="text-gray-400">(+3)</b></span>
                    </div>
                    <div id="chk-number" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Angka Numerik di Judul (6.200, 26.000, 3 Varian, 2026) <b class="text-gray-400">(+3)</b></span>
                    </div>
                </div>
            </div>

            {{-- 4. Content Readability --}}
            <div class="border border-gray-100 rounded-xl overflow-hidden">
                <button type="button" class="w-full px-4 py-2.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between text-left text-xs font-bold text-gray-700 transition" onclick="rmToggleSection('rm-sec-content')">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-book-reader text-amber-600"></i>
                        4. Content Readability (Kenyamanan Baca)
                    </span>
                    <span id="rm-count-content" class="text-[11px] font-semibold text-gray-500">0/15 Poin</span>
                </button>
                <div id="rm-sec-content" class="p-3 space-y-2 text-xs bg-white">
                    <div id="chk-paragraphs" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Paragraf Ringkas (Bebas Dinding Teks > 120 kata) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-media" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Kekayaan Gambar Produk (Minimal 2 gambar) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-lists-tables" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Format Terstruktur (Bullet List / Tabel Perbandingan) <b class="text-gray-400">(+4)</b></span>
                    </div>
                    <div id="chk-subheadings" class="flex items-center gap-2 text-gray-600 text-[11px]">
                        <i class="fas fa-times-circle text-red-500 w-4 text-center"></i>
                        <span>Hirarki Sub-Heading Terstruktur (H2 & H3) <b class="text-gray-400">(+3)</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // State Tracker untuk Rank Math SEO
    window.RankMathState = {
        keyword: '',
        titleInput: null,
        slugInput: null,
        getContentCallback: null,
        getMediaCountCallback: null
    };

    function rmToggleSection(id) {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('hidden');
    }

    function rmAutoDetectKeyword() {
        if (!window.RankMathState.titleInput) return;
        const title = window.RankMathState.titleInput.value.trim();
        if (!title) return;
        
        // Ambil 3-5 kata pertama dari judul sebagai default keyword
        const words = title.split(/\s+/).slice(0, 4).join(' ').toLowerCase();
        const kwInput = document.getElementById('rm-focus-keyword');
        if (kwInput) {
            kwInput.value = words;
            rmRunAudit();
        }
    }

    function rmUpdateCheckUI(elementId, passed, customText) {
        const row = document.getElementById(elementId);
        if (!row) return;
        const icon = row.querySelector('i');
        if (passed) {
            icon.className = 'fas fa-check-circle text-green-500 w-4 text-center';
            row.classList.remove('text-gray-400', 'text-gray-600');
            row.classList.add('text-gray-800', 'font-medium');
        } else {
            icon.className = 'fas fa-times-circle text-red-400 w-4 text-center';
            row.classList.remove('text-gray-800', 'font-medium');
            row.classList.add('text-gray-500');
        }
    }

    function rmRunAudit() {
        const kwInput = document.getElementById('rm-focus-keyword');
        const kw = (kwInput ? kwInput.value : '').trim().toLowerCase();
        
        const titleEl = window.RankMathState.titleInput;
        const slugEl = window.RankMathState.slugInput;
        const title = (titleEl ? titleEl.value : '').trim();
        const tLower = title.toLowerCase();

        let slug = (slugEl ? slugEl.value : '').trim();
        if (!slug && title) {
            slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }
        const sLower = slug.toLowerCase();

        const contentHtml = window.RankMathState.getContentCallback ? window.RankMathState.getContentCallback() : '';
        const mediaCount = window.RankMathState.getMediaCountCallback ? window.RankMathState.getMediaCountCallback() : 0;

        // Parse HTML content
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = contentHtml || '';
        const plainText = (tempDiv.innerText || tempDiv.textContent || '').trim();
        const words = plainText ? plainText.match(/\b[\w'-]+\b/g) || [] : [];
        const wordCount = words.length;

        // Update display word count
        const wordValEl = document.getElementById('rm-word-val');
        if (wordValEl) wordValEl.textContent = wordCount;

        let scoreBasic = 0;
        let scoreAdditional = 0;
        let scoreTitle = 0;
        let scoreContent = 0;

        const kwSlug = kw.replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        const kwWords = kw ? kw.split(/\s+/).filter(Boolean) : [];

        // --- 1. BASIC SEO (40 pts) ---
        // 1.1 Keyword in Title (8)
        const passKwTitle = kw && tLower.includes(kw);
        if (passKwTitle) scoreBasic += 8;
        rmUpdateCheckUI('chk-kw-title', passKwTitle);

        // 1.2 Keyword in Slug (8)
        const passKwSlug = kw && (sLower.includes(kwSlug) || sLower.includes(kw.replace(/\s+/g, '-')));
        if (passKwSlug) scoreBasic += 8;
        rmUpdateCheckUI('chk-kw-slug', passKwSlug);

        // 1.3 Keyword in Intro (8)
        const introText = plainText.substring(0, Math.max(180, Math.floor(plainText.length * 0.12))).toLowerCase();
        const passKwIntro = kw && introText.includes(kw);
        if (passKwIntro) scoreBasic += 8;
        rmUpdateCheckUI('chk-kw-intro', passKwIntro);

        // 1.4 Keyword in Content (8)
        const passKwContent = kw && plainText.toLowerCase().includes(kw);
        if (passKwContent) scoreBasic += 8;
        rmUpdateCheckUI('chk-kw-content', passKwContent);

        // 1.5 Word count (8)
        let passWordCount = false;
        if (wordCount >= 1000) {
            scoreBasic += 8;
            passWordCount = true;
        } else if (wordCount >= 600) {
            scoreBasic += 5;
            passWordCount = true;
        } else if (wordCount >= 300) {
            scoreBasic += 2;
        }
        rmUpdateCheckUI('chk-word-count', passWordCount);

        // --- 2. ADDITIONAL SEO (30 pts) ---
        // 2.1 Keyword in Subheading (6)
        const headings = tempDiv.querySelectorAll('h2, h3, h4');
        let passHeading = false;
        if (kw) {
            headings.forEach(h => {
                if ((h.textContent || '').toLowerCase().includes(kw)) passHeading = true;
            });
        }
        if (passHeading) scoreAdditional += 6;
        rmUpdateCheckUI('chk-kw-heading', passHeading);

        // 2.2 Keyword in Image Alt / File (6)
        let passImage = false;
        tempDiv.querySelectorAll('img').forEach(img => {
            const alt = (img.getAttribute('alt') || '').toLowerCase();
            const src = (img.getAttribute('src') || '').toLowerCase();
            if (kw && (alt.includes(kw) || src.includes(kwSlug))) passImage = true;
        });
        if (!passImage && mediaCount > 0 && kw && (sLower.includes(kwSlug) || passHeading)) {
            passImage = true; // Anggap nama berkas cover mengikuti slug judul
        }
        if (passImage) scoreAdditional += 6;
        rmUpdateCheckUI('chk-kw-image', passImage);

        // 2.3 Keyword Density (6)
        let density = 0;
        let passDensity = false;
        if (kw && wordCount > 0) {
            const regex = new RegExp(kw.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'), 'gi');
            const matches = (plainText.match(regex) || []).length;
            density = ((matches * kwWords.length) / wordCount) * 100;
            if (density >= 0.8 && density <= 2.5) {
                scoreAdditional += 6;
                passDensity = true;
            } else if (density >= 0.4) {
                scoreAdditional += 3;
            }
        }
        const densityEl = document.getElementById('rm-density-val');
        if (densityEl) densityEl.textContent = density.toFixed(2) + '%';
        rmUpdateCheckUI('chk-kw-density', passDensity);

        // 2.4 Slug length <= 75 (4)
        const passSlugLen = slug.length > 0 && slug.length <= 75;
        if (passSlugLen) scoreAdditional += 4;
        rmUpdateCheckUI('chk-slug-len', passSlugLen);

        // 2.5 Internal Links to Products (4)
        let passInternal = false;
        let passExternal = false;
        tempDiv.querySelectorAll('a').forEach(a => {
            const href = a.getAttribute('href') || '';
            if (href.startsWith('/') || href.includes('mywowin.com') || href.includes('products') || href.includes('artikels')) {
                passInternal = true;
            }
            if (href.startsWith('http') || href.includes('wa.me') || href.includes('whatsapp') || href.includes('bpom') || href.includes('halal')) {
                passExternal = true;
            }
        });
        if (passInternal) scoreAdditional += 4;
        rmUpdateCheckUI('chk-internal-link', passInternal);

        // 2.6 External / Authority / WA Links (4)
        if (passExternal) scoreAdditional += 4;
        rmUpdateCheckUI('chk-external-link', passExternal);

        // --- 3. TITLE READABILITY (15 pts) ---
        // 3.1 Keyword in First Half of Title (5)
        const firstHalf = tLower.substring(0, Math.ceil(tLower.length * 0.55));
        const passKwStart = kw && firstHalf.includes(kw);
        if (passKwStart) scoreTitle += 5;
        rmUpdateCheckUI('chk-kw-start', passKwStart);

        // 3.2 Sentiment Word (4)
        const sentiments = ['murah', 'juara', 'terbaik', 'untung', 'sukses', 'unggul', 'efisien', 'hemat', 'kualitas', 'mantap', 'mudah', 'praktis', 'bermutu', 'lezat', 'gurih'];
        const passSentiment = sentiments.some(w => tLower.includes(w));
        if (passSentiment) scoreTitle += 4;
        rmUpdateCheckUI('chk-sentiment', passSentiment);

        // 3.3 Power Word (3)
        const powerWords = ['supplier', 'distributor', 'grosir', 'pabrik', 'panduan', 'rahasia', 'solusi', 'rekomendasi', 'strategi', 'resep', 'cara', 'trik', 'kunci', 'bocoran'];
        const passPower = powerWords.some(w => tLower.includes(w));
        if (passPower) scoreTitle += 3;
        rmUpdateCheckUI('chk-powerword', passPower);

        // 3.4 Number in Title (3)
        const passNumber = /\d+/.test(title);
        if (passNumber) scoreTitle += 3;
        rmUpdateCheckUI('chk-number', passNumber);

        // --- 4. CONTENT READABILITY (15 pts) ---
        // 4.1 Short Paragraphs (4)
        const paragraphs = tempDiv.querySelectorAll('p');
        let passParagraphs = paragraphs.length >= 2;
        paragraphs.forEach(p => {
            const pWords = (p.textContent || '').trim().split(/\s+/).filter(Boolean).length;
            if (pWords > 130) passParagraphs = false;
        });
        if (passParagraphs) scoreContent += 4;
        rmUpdateCheckUI('chk-paragraphs', passParagraphs);

        // 4.2 Media Richness (4)
        const totalMedia = tempDiv.querySelectorAll('img').length + mediaCount;
        const passMedia = totalMedia >= 2;
        if (passMedia) scoreContent += 4;
        else if (totalMedia >= 1) scoreContent += 2;
        rmUpdateCheckUI('chk-media', passMedia);

        // 4.3 Lists & Tables (4)
        const passListsTables = tempDiv.querySelectorAll('ul, ol, table').length > 0;
        if (passListsTables) scoreContent += 4;
        rmUpdateCheckUI('chk-lists-tables', passListsTables);

        // 4.4 Subheadings (3)
        const passSubheadings = headings.length >= 2;
        if (passSubheadings) scoreContent += 3;
        rmUpdateCheckUI('chk-subheadings', passSubheadings);

        // Update Category Points Display
        const elB = document.getElementById('rm-count-basic');
        if (elB) elB.textContent = scoreBasic + '/40 Poin';
        const elA = document.getElementById('rm-count-additional');
        if (elA) elA.textContent = scoreAdditional + '/30 Poin';
        const elT = document.getElementById('rm-count-title');
        if (elT) elT.textContent = scoreTitle + '/15 Poin';
        const elC = document.getElementById('rm-count-content');
        if (elC) elC.textContent = scoreContent + '/15 Poin';

        // TOTAL SCORE
        const totalScore = Math.min(100, Math.max(0, scoreBasic + scoreAdditional + scoreTitle + scoreContent));
        const scoreNumEl = document.getElementById('rm-score-number');
        const scoreBadgeEl = document.getElementById('rm-score-badge');
        const scoreLabelEl = document.getElementById('rm-score-label');
        const noticeEl = document.getElementById('rm-passing-notice');

        if (scoreNumEl) scoreNumEl.textContent = totalScore;

        if (totalScore >= 80) {
            // GREEN / EXCELLENT
            scoreBadgeEl.className = 'px-3.5 py-1.5 rounded-xl text-xs font-black bg-green-50 text-green-700 border border-green-300 flex items-center gap-2 transition-all';
            scoreLabelEl.className = 'text-[10px] px-1.5 py-0.5 rounded bg-green-100 uppercase tracking-wider font-bold text-green-800';
            scoreLabelEl.textContent = 'EXCELLENT';
            if (noticeEl) {
                noticeEl.className = 'p-3 rounded-xl text-xs bg-green-50 border border-green-200 text-green-800 flex items-start gap-2.5';
                noticeEl.innerHTML = '<i class="fas fa-check-circle text-green-600 mt-0.5 text-sm"></i><div class="text-[11px]"><strong>Selamat! Skor SEO mencapai ' + totalScore + '/100 (Kategori Hijau).</strong> Artikel telah memenuhi standar Rank Math dan sangat siap diterbitkan.</div>';
            }
        } else if (totalScore >= 50) {
            // YELLOW / FAIR
            scoreBadgeEl.className = 'px-3.5 py-1.5 rounded-xl text-xs font-black bg-amber-50 text-amber-700 border border-amber-300 flex items-center gap-2 transition-all';
            scoreLabelEl.className = 'text-[10px] px-1.5 py-0.5 rounded bg-amber-100 uppercase tracking-wider font-bold text-amber-800';
            scoreLabelEl.textContent = 'FAIR';
            if (noticeEl) {
                noticeEl.className = 'p-3 rounded-xl text-xs bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-2.5';
                noticeEl.innerHTML = '<i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 text-sm"></i><div class="text-[11px]"><strong>Skor saat ini: ' + totalScore + '/100 (Kategori Kuning).</strong> Silakan lengkapi item yang masih bertanda silang merah untuk mencapai skor minimal 80.</div>';
            }
        } else {
            // RED / POOR
            scoreBadgeEl.className = 'px-3.5 py-1.5 rounded-xl text-xs font-black bg-red-50 text-red-600 border border-red-200 flex items-center gap-2 transition-all';
            scoreLabelEl.className = 'text-[10px] px-1.5 py-0.5 rounded bg-red-100 uppercase tracking-wider font-bold text-red-700';
            scoreLabelEl.textContent = 'POOR';
            if (noticeEl) {
                noticeEl.className = 'p-3 rounded-xl text-xs bg-red-50 border border-red-200 text-red-800 flex items-start gap-2.5';
                noticeEl.innerHTML = '<i class="fas fa-times-circle text-red-600 mt-0.5 text-sm"></i><div class="text-[11px]"><strong>Skor saat ini: ' + totalScore + '/100 (Kategori Merah).</strong> Artikel belum memenuhi syarat SEO dasar. Lengkapi focus keyword, heading, dan konten.</div>';
            }
        }
    }

    // Attach listeners on load
    document.addEventListener('DOMContentLoaded', function() {
        const kwInput = document.getElementById('rm-focus-keyword');
        if (kwInput) {
            kwInput.addEventListener('input', rmRunAudit);
        }
        
        // Cek apakah ada input judul di halaman
        const titleEl = document.querySelector('input[name="judul"]');
        const slugEl = document.querySelector('input[name="slug"]');
        
        if (titleEl) {
            window.RankMathState.titleInput = titleEl;
            titleEl.addEventListener('input', function() {
                // Auto isi keyword jika masih kosong
                if (kwInput && !kwInput.value.trim()) {
                    rmAutoDetectKeyword();
                }
                rmRunAudit();
            });
        }
        if (slugEl) {
            window.RankMathState.slugInput = slugEl;
            slugEl.addEventListener('input', rmRunAudit);
        }

        // Jalankan audit pertama
        setTimeout(rmRunAudit, 800);
    });
</script>
