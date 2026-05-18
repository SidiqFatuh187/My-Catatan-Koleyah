@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Back Button --}}
    <a href="{{ route('category.index') }}"
        class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-gray-600 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Kategori
    </a>

    {{-- Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white text-base font-bold flex-shrink-0"
                style="background-color: {{ $category->color ?? '#3B82F6' }}">
                {{ $category->icon ?? strtoupper(substr($category->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-base font-semibold text-gray-700">Edit Kategori</h2>
                <p class="text-sm text-gray-400 mt-0.5">Ubah detail kategori <span class="font-medium text-gray-500">{{ $category->name }}</span></p>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm font-medium text-red-700">Terdapat kesalahan:</p>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="text-sm text-red-600">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('category.update', $category->id) }}" method="POST" id="categoryForm">
            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600 mb-1.5">
                        Nama Kategori <span class="text-red-400">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Contoh: Pekerjaan, Pribadi, Belanja..."
                        autocomplete="off"
                        class="w-full text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-300 @error('name') border-red-300 bg-red-50 @enderror"
                    />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Icon (emoji-only) --}}
                <div class="relative">
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">
                        Ikon <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>

                    {{-- Hidden real input sent to server --}}
                    <input type="hidden" name="icon" id="iconValue" value="{{ old('icon', $category->icon) }}" />

                    {{-- Emoji Selector Row --}}
                    <div class="flex items-center gap-3">

                        {{-- Big clickable emoji bubble --}}
                        <button type="button" id="emojiPickerToggle"
                            class="group relative w-14 h-14 flex-shrink-0 rounded-2xl border-2 border-dashed border-gray-200 hover:border-blue-300 bg-gray-50 hover:bg-blue-50 flex items-center justify-center transition-all"
                            title="Klik untuk pilih emoji">
                            <span id="iconDisplay" class="text-2xl leading-none select-none transition-transform group-hover:scale-110">
                                {{ old('icon', $category->icon) ?: '➕' }}
                            </span>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center shadow-sm group-hover:bg-blue-600 transition-colors">
                                <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828 9 16.5l.672-2.828z"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Info text + clear --}}
                        <div class="flex-1">
                            <div id="emojiSelectedInfo"
                                class="{{ old('icon', $category->icon) ? 'flex' : 'hidden' }} items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-gray-700" id="emojiSelectedLabel">
                                    {{ old('icon', $category->icon) }}
                                </span>
                                <button type="button" id="iconClearBtn"
                                    class="text-xs text-red-400 hover:text-red-600 hover:underline transition-colors">
                                    Hapus
                                </button>
                            </div>
                            <p id="emojiHint" class="text-sm text-gray-400 {{ old('icon', $category->icon) ? 'hidden' : '' }}">
                                Klik bubble untuk memilih emoji
                            </p>
                            <p class="text-xs text-gray-300 mt-0.5">
                                Atau gunakan emoji keyboard:
                                <kbd class="bg-gray-100 text-gray-400 rounded px-1 text-xs">⊞ Win+.</kbd>
                                <kbd class="bg-gray-100 text-gray-400 rounded px-1 text-xs">⌘ Cmd+Ctrl+Space</kbd>
                                <span class="text-gray-300">lalu paste ke bubble</span>
                            </p>
                        </div>
                    </div>

                    {{-- Emoji Picker Panel --}}
                    <div id="emojiPickerPanel"
                        class="hidden mt-3 bg-white rounded-2xl border border-gray-200 shadow-xl overflow-hidden z-50">

                        {{-- Search bar --}}
                        <div class="p-3 border-b border-gray-100">
                            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2">
                                <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="emojiSearch"
                                    placeholder="Cari emoji... (misal: hati, api, bintang)"
                                    class="flex-1 text-sm bg-transparent outline-none text-gray-600 placeholder-gray-300"
                                    autocomplete="off" />
                                <button type="button" id="emojiSearchClear"
                                    class="hidden text-gray-300 hover:text-gray-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Category Tabs --}}
                        <div id="emojiTabsWrapper" class="flex border-b border-gray-100 overflow-x-auto">
                            @php
                                $emojiCategories = [
                                    ['id' => 'popular',  'label' => '⭐',  'title' => 'Populer'],
                                    ['id' => 'work',     'label' => '💼',  'title' => 'Kerja'],
                                    ['id' => 'home',     'label' => '🏠',  'title' => 'Rumah'],
                                    ['id' => 'sport',    'label' => '⚽',  'title' => 'Olahraga'],
                                    ['id' => 'food',     'label' => '🍔',  'title' => 'Makanan'],
                                    ['id' => 'travel',   'label' => '✈️',  'title' => 'Perjalanan'],
                                    ['id' => 'nature',   'label' => '🌿',  'title' => 'Alam'],
                                    ['id' => 'symbol',   'label' => '💡',  'title' => 'Simbol'],
                                ];
                            @endphp
                            @foreach($emojiCategories as $i => $cat)
                                <button type="button"
                                    class="emoji-tab flex-shrink-0 flex flex-col items-center gap-0.5 px-3 py-2 text-xs font-medium transition-colors whitespace-nowrap {{ $i === 0 ? 'text-blue-600 border-b-2 border-blue-500 bg-blue-50/40' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}"
                                    data-tab="{{ $cat['id'] }}"
                                    title="{{ $cat['title'] }}">
                                    <span class="text-base leading-none">{{ $cat['label'] }}</span>
                                    <span class="hidden sm:block">{{ $cat['title'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Emoji Grid --}}
                        <div class="p-3 max-h-52 overflow-y-auto">

                            {{-- Search results panel --}}
                            <div id="searchResultsPanel" class="hidden">
                                <p class="text-xs text-gray-400 mb-2" id="searchResultsLabel"></p>
                                <div class="grid grid-cols-10 gap-0.5" id="searchResultsGrid"></div>
                            </div>

                            @php
                                $emojiData = [
                                    'popular' => [
                                        '⭐' => 'bintang', '❤️' => 'hati', '🔥' => 'api', '✅' => 'centang',
                                        '📝' => 'catatan', '💡' => 'ide lampu', '🎯' => 'target', '🚀' => 'roket',
                                        '💪' => 'kuat otot', '😊' => 'senyum', '👍' => 'jempol', '🙌' => 'tepuk',
                                        '💯' => 'seratus', '🎉' => 'pesta', '⚡' => 'petir', '🌟' => 'bintang berkilau',
                                        '💎' => 'berlian', '🏆' => 'piala', '📌' => 'pin', '🔖' => 'bookmark',
                                        '😍' => 'suka hati', '🤩' => 'kagum bintang', '😎' => 'keren', '🥳' => 'pesta ulang tahun',
                                        '🤗' => 'pelukan', '👏' => 'tepuk tangan', '🫶' => 'hati tangan', '✨' => 'kilau',
                                        '🎊' => 'konfeti', '🎁' => 'kado hadiah',
                                    ],
                                    'work' => [
                                        '💼' => 'tas kerja', '📊' => 'grafik', '📈' => 'naik', '📉' => 'turun',
                                        '💻' => 'laptop', '🖥️' => 'monitor', '📱' => 'ponsel', '⌨️' => 'keyboard',
                                        '📋' => 'clipboard', '📁' => 'folder', '🗂️' => 'map berkas', '📎' => 'klip',
                                        '✏️' => 'pensil', '🖊️' => 'pena', '📏' => 'penggaris', '🔧' => 'kunci pas',
                                        '⚙️' => 'roda gigi setting', '🔩' => 'baut', '📦' => 'kotak paket', '📮' => 'pos',
                                        '💰' => 'uang kantong', '💳' => 'kartu kredit', '🏦' => 'bank', '📞' => 'telepon',
                                        '☎️' => 'telepon klasik', '🖇️' => 'klip kertas', '🗓️' => 'kalender', '📅' => 'tanggal',
                                        '📆' => 'kalender robek', '🗒️' => 'buku catatan', '📤' => 'kirim', '📥' => 'terima',
                                        '📧' => 'email', '🖨️' => 'printer', '🗃️' => 'kotak arsip', '🔐' => 'kunci gembok',
                                        '🔑' => 'kunci', '🏷️' => 'label tag', '🧾' => 'struk nota',
                                    ],
                                    'home' => [
                                        '🏠' => 'rumah', '🏡' => 'rumah taman', '🛋️' => 'sofa', '🛏️' => 'kasur tidur',
                                        '🚿' => 'shower mandi', '🛁' => 'bathtub', '🪴' => 'tanaman pot', '🧹' => 'sapu',
                                        '🧺' => 'keranjang cuci', '🧼' => 'sabun', '🪟' => 'jendela', '🚪' => 'pintu',
                                        '💡' => 'lampu', '🕯️' => 'lilin', '🧯' => 'pemadam api', '🪑' => 'kursi',
                                        '🚽' => 'toilet', '🧻' => 'tisu toilet', '🫧' => 'gelembung sabun', '🔑' => 'kunci',
                                        '🗝️' => 'kunci kuno', '🔒' => 'gembok terkunci', '📺' => 'televisi', '📻' => 'radio',
                                        '🎮' => 'gamepad', '🕹️' => 'joystick', '🎲' => 'dadu', '🧸' => 'boneka beruang',
                                        '🪆' => 'boneka matryoshka', '🎨' => 'palet cat', '🖼️' => 'gambar bingkai',
                                    ],
                                    'sport' => [
                                        '⚽' => 'bola sepak', '🏀' => 'basket', '🏈' => 'american football', '⚾' => 'baseball',
                                        '🎾' => 'tenis', '🏐' => 'voli', '🏉' => 'rugby', '🎱' => 'biliar',
                                        '🏓' => 'pingpong tenis meja', '🏸' => 'badminton', '🥊' => 'tinju sarung',
                                        '🥋' => 'bela diri karate', '🤸' => 'senam akrobat', '🧘' => 'yoga meditasi',
                                        '🏋️' => 'angkat beban', '🚴' => 'bersepeda', '🏊' => 'renang', '🤽' => 'polo air',
                                        '🧗' => 'panjat tebing', '🏇' => 'balap kuda', '⛷️' => 'ski salju', '🏂' => 'snowboard',
                                        '🏄' => 'surfing', '🚵' => 'sepeda gunung', '🤺' => 'anggar pedang', '🎿' => 'ski',
                                        '🛷' => 'kereta luncur', '🥅' => 'gawang gol', '🎯' => 'target panah', '🏹' => 'panah busur',
                                        '⛳' => 'golf', '🎣' => 'mancing ikan', '🏆' => 'piala juara', '🥇' => 'medali emas',
                                        '🥈' => 'medali perak', '🥉' => 'medali perunggu', '🎖️' => 'medali penghargaan',
                                    ],
                                    'food' => [
                                        '🍔' => 'burger', '🍕' => 'pizza', '🍜' => 'mie ramen', '🍱' => 'bento kotak',
                                        '🍣' => 'sushi', '🍩' => 'donat', '🎂' => 'kue ulang tahun', '🍰' => 'kue potong',
                                        '🍦' => 'es krim', '🧁' => 'cupcake', '🥗' => 'salad', '🍲' => 'sup semur',
                                        '🥘' => 'masakan wajan', '🫕' => 'fondue', '🥙' => 'kebab', '🌮' => 'taco',
                                        '🌯' => 'wrap burrito', '🧆' => 'falafel', '🥚' => 'telur', '🍳' => 'goreng telur',
                                        '🥓' => 'bacon', '🥩' => 'daging steak', '🍗' => 'ayam paha', '🍖' => 'tulang daging',
                                        '🌭' => 'hotdog sosis', '🥪' => 'sandwich', '🧇' => 'wafel', '🥞' => 'pancake',
                                        '🍞' => 'roti', '🥐' => 'croissant', '🥦' => 'brokoli', '🥕' => 'wortel',
                                        '🍎' => 'apel merah', '🍊' => 'jeruk', '🍋' => 'lemon', '☕' => 'kopi',
                                        '🧃' => 'jus kotak', '🥤' => 'minuman cup', '🍵' => 'teh', '🧋' => 'boba bubble tea',
                                    ],
                                    'travel' => [
                                        '✈️' => 'pesawat terbang', '🚂' => 'kereta api', '🚢' => 'kapal laut', '🚗' => 'mobil',
                                        '🏕️' => 'kemah camping', '⛺' => 'tenda', '🗺️' => 'peta', '🧭' => 'kompas',
                                        '🗼' => 'menara eiffel', '🗽' => 'patung liberty', '🏰' => 'kastil', '🛫' => 'pesawat lepas landas',
                                        '🚁' => 'helikopter', '🛸' => 'ufo', '🚐' => 'minibus', '🚌' => 'bus',
                                        '🏎️' => 'mobil balap', '🚒' => 'pemadam kebakaran', '🚑' => 'ambulans', '🚜' => 'traktor',
                                        '🏍️' => 'motor', '🛵' => 'skuter', '🚲' => 'sepeda', '🛴' => 'skuter kaki',
                                        '⛽' => 'pompa bensin', '🧳' => 'koper', '👝' => 'tas kecil', '🎒' => 'ransel',
                                        '🪪' => 'kartu id', '🏟️' => 'stadion', '⛪' => 'gereja', '🕌' => 'masjid',
                                        '🛕' => 'kuil pura', '⛩️' => 'torii gerbang jepang', '🏯' => 'kastil jepang',
                                        '🌃' => 'malam kota', '🌆' => 'senja kota', '🌇' => 'matahari terbenam kota',
                                        '🎡' => 'bianglala', '🎢' => 'rollercoaster', '🎠' => 'komedi putar', '🎪' => 'sirkus',
                                    ],
                                    'nature' => [
                                        '🌿' => 'daun hijau', '🌸' => 'bunga sakura', '🌺' => 'bunga mekar', '🌻' => 'bunga matahari',
                                        '🌹' => 'mawar', '🌷' => 'tulip', '🍀' => 'semanggi', '🌱' => 'bibit tanaman',
                                        '🌲' => 'pohon cemara', '🌳' => 'pohon rindang', '🌴' => 'pohon kelapa', '🍃' => 'daun angin',
                                        '🍂' => 'daun gugur', '🍁' => 'daun maple', '🌾' => 'padi', '🐶' => 'anjing',
                                        '🐱' => 'kucing', '🐭' => 'tikus', '🐹' => 'hamster', '🐰' => 'kelinci',
                                        '🦊' => 'rubah', '🐻' => 'beruang', '🐼' => 'panda', '🐨' => 'koala',
                                        '🐯' => 'harimau', '🦁' => 'singa', '🐸' => 'katak', '🦆' => 'bebek',
                                        '🦋' => 'kupu kupu', '🐝' => 'lebah', '🌍' => 'bumi', '🌕' => 'bulan purnama',
                                        '☀️' => 'matahari', '🌤️' => 'cerah berawan', '🌧️' => 'hujan', '⛈️' => 'petir badai',
                                        '❄️' => 'salju', '🌈' => 'pelangi', '🌊' => 'ombak laut', '🌋' => 'gunung berapi',
                                        '🏔️' => 'gunung salju', '🏝️' => 'pulau', '🌅' => 'matahari terbit', '🌄' => 'fajar pegunungan',
                                    ],
                                    'symbol' => [
                                        '💡' => 'ide lampu', '🔔' => 'bel notif', '🔕' => 'bel mati', '📣' => 'megafon',
                                        '📢' => 'pengeras suara', '💬' => 'chat', '💭' => 'pikiran', '❗' => 'seru penting',
                                        '❓' => 'tanya', '‼️' => 'sangat penting', '⁉️' => 'tanya seru', '♻️' => 'daur ulang',
                                        '✅' => 'centang ok', '❎' => 'silang', '🔰' => 'pemula', '💠' => 'berlian biru',
                                        '🔷' => 'berlian besar biru', '🔶' => 'berlian besar oranye', '🔹' => 'berlian kecil biru',
                                        '🔸' => 'berlian kecil oranye', '🔺' => 'segitiga merah', '🔻' => 'segitiga merah bawah',
                                        '▶️' => 'play putar', '⏸️' => 'pause', '⏹️' => 'stop', '⏭️' => 'lanjut',
                                        '🔀' => 'acak shuffle', '🔁' => 'ulang repeat', '⬆️' => 'atas', '⬇️' => 'bawah',
                                        '⬅️' => 'kiri', '➡️' => 'kanan', '↩️' => 'balik kiri', '↪️' => 'balik kanan',
                                        '🆕' => 'new baru', '🆗' => 'ok', '🆘' => 'sos darurat', '🆙' => 'up naik',
                                        '🅰️' => 'huruf a', '🅱️' => 'huruf b', '🆎' => 'ab', '🆑' => 'cl',
                                    ],
                                ];
                            @endphp

                            @foreach($emojiData as $catId => $emojis)
                                <div class="emoji-panel {{ $catId !== 'popular' ? 'hidden' : '' }}" data-panel="{{ $catId }}">
                                    <div class="grid grid-cols-10 gap-0.5">
                                        @foreach($emojis as $emoji => $keyword)
                                            <button type="button"
                                                class="emoji-pick w-8 h-8 flex items-center justify-center text-xl rounded-lg hover:bg-blue-50 active:scale-90 transition-all"
                                                data-emoji="{{ $emoji }}"
                                                data-keyword="{{ $keyword }}"
                                                title="{{ $emoji }} {{ $keyword }}">{{ $emoji }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer --}}
                        <div class="px-3 pb-2.5 pt-2 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-300">Pilih dari grid atau gunakan emoji keyboard device kamu</p>
                            <button type="button" id="emojiPickerClose"
                                class="text-xs text-gray-400 hover:text-gray-600 font-medium transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Warna Kategori</label>

                    <div class="flex flex-wrap gap-2 mb-3">
                        @php
                            $presets = [
                                '#3B82F6', '#8B5CF6', '#EC4899', '#EF4444',
                                '#F97316', '#EAB308', '#22C55E', '#14B8A6',
                                '#06B6D4', '#64748B',
                            ];
                        @endphp
                        @foreach($presets as $preset)
                            <button type="button"
                                class="color-preset w-8 h-8 rounded-xl border-2 border-transparent transition-all hover:scale-110 focus:outline-none"
                                data-color="{{ $preset }}"
                                style="background-color: {{ $preset }}">
                            </button>
                        @endforeach

                        <label for="colorPicker"
                            class="w-8 h-8 rounded-xl border-2 border-dashed border-gray-300 hover:border-gray-400 flex items-center justify-center cursor-pointer transition-colors"
                            title="Pilih warna custom">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                            <input type="color" id="colorPicker" class="sr-only"
                                value="{{ old('color', $category->color ?? '#3B82F6') }}" />
                        </label>
                    </div>

                    <input type="hidden" name="color" id="colorValue"
                        value="{{ old('color', $category->color ?? '#3B82F6') }}" />

                    <div class="flex items-center gap-2">
                        <div id="colorSwatch" class="w-5 h-5 rounded-md border border-gray-200"
                            style="background-color: {{ old('color', $category->color ?? '#3B82F6') }}"></div>
                        <span id="colorHex" class="text-xs font-mono text-gray-500">
                            {{ old('color', $category->color ?? '#3B82F6') }}
                        </span>
                    </div>
                </div>

                {{-- Live Preview --}}
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Preview Kartu</label>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col gap-4 max-w-xs">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div id="previewAvatar"
                                        class="w-10 h-10 rounded-xl flex items-center justify-center font-bold"
                                        style="background-color: {{ old('color', $category->color ?? '#3B82F6') }};
                                              color: {{ old('icon', $category->icon) ? '#111827' : '#ffffff' }};
                                               font-size: {{ old('icon', $category->icon) ? '1.25rem' : '1rem' }}">
                                        {{ old('icon', $category->icon) ?: strtoupper(substr(old('name', $category->name), 0, 1)) }}
                                    </div>
                                    <div>
                                        <p id="previewName" class="text-sm font-semibold text-gray-700">
                                            {{ old('name', $category->name) }}
                                        </p>
                                        <p class="text-xs text-gray-400">0 tasks</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div id="previewBar" class="h-1.5 rounded-full w-full"
                                style="background-color: {{ old('color', $category->color ?? '#3B82F6') }}"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
                {{-- Cancel + Save (kanan) --}}
                <div class="flex items-center gap-3">
                    <a href="{{ route('category.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 font-medium px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-xl transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/category-create.js')
@endpush