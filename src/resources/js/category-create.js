/**
 * category-create.js
 * Emoji-only icon picker: no free-text input.
 * Features: grid picker, category tabs, search, device emoji via paste/drop.
 */

document.addEventListener('DOMContentLoaded', function() {

    // ─── Elements ────────────────────────────────────────────────────────────
    const iconValue = document.getElementById('iconValue');
    const iconDisplay = document.getElementById('iconDisplay');
    const emojiSelectedInfo = document.getElementById('emojiSelectedInfo');
    const emojiSelectedLabel = document.getElementById('emojiSelectedLabel');
    const emojiHint = document.getElementById('emojiHint');
    const iconClearBtn = document.getElementById('iconClearBtn');

    const emojiPickerToggle = document.getElementById('emojiPickerToggle');
    const emojiPickerPanel = document.getElementById('emojiPickerPanel');
    const emojiPickerClose = document.getElementById('emojiPickerClose');
    const emojiTabsWrapper = document.getElementById('emojiTabsWrapper');
    const emojiTabs = document.querySelectorAll('.emoji-tab');
    const emojiPanels = document.querySelectorAll('.emoji-panel');
    const emojiPickButtons = document.querySelectorAll('.emoji-pick');

    const emojiSearch = document.getElementById('emojiSearch');
    const emojiSearchClear = document.getElementById('emojiSearchClear');
    const searchResultsPanel = document.getElementById('searchResultsPanel');
    const searchResultsGrid = document.getElementById('searchResultsGrid');
    const searchResultsLabel = document.getElementById('searchResultsLabel');

    const nameInput = document.getElementById('name');
    const colorValue = document.getElementById('colorValue');
    const colorPicker = document.getElementById('colorPicker');
    const colorSwatch = document.getElementById('colorSwatch');
    const colorHex = document.getElementById('colorHex');
    const colorPresets = document.querySelectorAll('.color-preset');

    const previewAvatar = document.getElementById('previewAvatar');
    const previewName = document.getElementById('previewName');
    const previewBar = document.getElementById('previewBar');

    // ─── Emoji regex ─────────────────────────────────────────────────────────
    // Matches any Unicode emoji character (broad coverage)
    const EMOJI_REGEX = /(\p{Emoji_Presentation}|\p{Extended_Pictographic})/u;

    function isEmoji(str) {
        return EMOJI_REGEX.test(str);
    }

    // Extract first emoji from a string (for paste/drop from device picker)
    function extractFirstEmoji(str) {
        const segmenter = typeof Intl.Segmenter !== 'undefined' ?
            new Intl.Segmenter('en', { granularity: 'grapheme' }) :
            null;

        if (segmenter) {
            for (const { segment }
                of segmenter.segment(str)) {
                if (isEmoji(segment)) return segment;
            }
        } else {
            // Fallback: match emoji-like sequences
            const match = str.match(/\p{Emoji_Presentation}|\p{Extended_Pictographic}/u);
            return match ? match[0] : null;
        }
        return null;
    }

    // ─── Set icon value ───────────────────────────────────────────────────────
    function setIcon(emoji) {
        iconValue.value = emoji || '';

        if (emoji) {
            iconDisplay.textContent = emoji;
            emojiSelectedLabel.textContent = emoji;
            emojiSelectedInfo.classList.remove('hidden');
            emojiSelectedInfo.classList.add('flex');
            emojiHint.classList.add('hidden');
            // Pulse animation on bubble
            emojiPickerToggle.classList.add('scale-110');
            setTimeout(() => emojiPickerToggle.classList.remove('scale-110'), 150);
        } else {
            iconDisplay.textContent = '➕';
            emojiSelectedInfo.classList.add('hidden');
            emojiSelectedInfo.classList.remove('flex');
            emojiHint.classList.remove('hidden');
        }

        // Highlight matching button in grid
        emojiPickButtons.forEach(b => {
            const active = b.dataset.emoji === emoji;
            b.classList.toggle('bg-blue-100', active);
            b.classList.toggle('ring-2', active);
            b.classList.toggle('ring-blue-400', active);
        });

        updatePreview();
    }

    // ─── Clear ────────────────────────────────────────────────────────────────
    iconClearBtn.addEventListener('click', () => setIcon(''));

    // ─── Picker open/close ────────────────────────────────────────────────────
    let pickerOpen = false;

    function openPicker() {
        emojiPickerPanel.classList.remove('hidden');
        pickerOpen = true;
        emojiPickerToggle.setAttribute('aria-expanded', 'true');
        // Focus search
        setTimeout(() => emojiSearch.focus(), 50);
    }

    function closePicker() {
        emojiPickerPanel.classList.add('hidden');
        pickerOpen = false;
        emojiPickerToggle.setAttribute('aria-expanded', 'false');
        // Reset search
        emojiSearch.value = '';
        handleSearch('');
    }

    emojiPickerToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        pickerOpen ? closePicker() : openPicker();
    });

    emojiPickerClose.addEventListener('click', closePicker);

    // Close on outside click
    document.addEventListener('click', (e) => {
        if (pickerOpen && !emojiPickerPanel.contains(e.target) && e.target !== emojiPickerToggle) {
            closePicker();
        }
    });

    emojiPickerPanel.addEventListener('click', (e) => e.stopPropagation());

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && pickerOpen) closePicker();
    });

    // ─── Category tabs ────────────────────────────────────────────────────────
    emojiTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const target = tab.dataset.tab;
            emojiTabs.forEach(t => {
                t.classList.remove('text-blue-600', 'border-b-2', 'border-blue-500', 'bg-blue-50/40');
                t.classList.add('text-gray-400');
            });
            tab.classList.add('text-blue-600', 'border-b-2', 'border-blue-500', 'bg-blue-50/40');
            tab.classList.remove('text-gray-400');

            emojiPanels.forEach(panel => panel.classList.toggle('hidden', panel.dataset.panel !== target));
        });
    });

    // ─── Pick emoji from grid ─────────────────────────────────────────────────
    emojiPickButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const emoji = btn.dataset.emoji;
            // Toggle: click same emoji to deselect
            setIcon(iconValue.value === emoji ? '' : emoji);
            if (iconValue.value) setTimeout(closePicker, 120);
        });
    });

    // ─── Search ───────────────────────────────────────────────────────────────
    // Build flat list of all emoji + keywords from grid buttons
    const allEmojis = Array.from(emojiPickButtons).map(b => ({
        emoji: b.dataset.emoji,
        keyword: b.dataset.keyword || '',
    }));

    function handleSearch(query) {
        const q = query.trim().toLowerCase();

        emojiSearchClear.classList.toggle('hidden', !q);

        if (!q) {
            searchResultsPanel.classList.add('hidden');
            emojiTabsWrapper.classList.remove('hidden');
            emojiPanels.forEach(p => {
                // Only show active tab's panel
                const activeTab = document.querySelector('.emoji-tab.text-blue-600');
                if (activeTab) {
                    p.classList.toggle('hidden', p.dataset.panel !== activeTab.dataset.tab);
                }
            });
            return;
        }

        // Filter
        const results = allEmojis.filter(e =>
            e.emoji.includes(q) || e.keyword.includes(q)
        );

        // Show search panel, hide tabs + category panels
        emojiTabsWrapper.classList.add('hidden');
        emojiPanels.forEach(p => p.classList.add('hidden'));
        searchResultsPanel.classList.remove('hidden');

        searchResultsLabel.textContent = results.length ?
            `${results.length} emoji ditemukan untuk "${query}"` :
            `Tidak ada emoji untuk "${query}"`;

        searchResultsGrid.innerHTML = '';
        results.forEach(({ emoji, keyword }) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-8 h-8 flex items-center justify-center text-xl rounded-lg hover:bg-blue-50 active:scale-90 transition-all';
            btn.dataset.emoji = emoji;
            btn.dataset.keyword = keyword;
            btn.title = `${emoji} ${keyword}`;
            btn.textContent = emoji;
            btn.addEventListener('click', () => {
                setIcon(iconValue.value === emoji ? '' : emoji);
                if (iconValue.value) setTimeout(closePicker, 120);
            });
            // Highlight if currently selected
            if (iconValue.value === emoji) {
                btn.classList.add('bg-blue-100', 'ring-2', 'ring-blue-400');
            }
            searchResultsGrid.appendChild(btn);
        });
    }

    emojiSearch.addEventListener('input', (e) => handleSearch(e.target.value));
    emojiSearchClear.addEventListener('click', () => {
        emojiSearch.value = '';
        handleSearch('');
        emojiSearch.focus();
    });

    // ─── Allow device emoji via PASTE onto the bubble ─────────────────────────
    // User can open OS emoji picker (Win+. / Cmd+Ctrl+Space), then paste onto bubble
    emojiPickerToggle.addEventListener('paste', (e) => {
        e.preventDefault();
        const text = (e.clipboardData || window.clipboardData).getData('text');
        const emoji = extractFirstEmoji(text);
        if (emoji) setIcon(emoji);
    });

    // Also allow pasting into the search box — if it's an emoji, set it directly
    emojiSearch.addEventListener('paste', (e) => {
        const text = (e.clipboardData || window.clipboardData).getData('text');
        const emoji = extractFirstEmoji(text);
        if (emoji) {
            e.preventDefault();
            setIcon(emoji);
            setTimeout(closePicker, 120);
        }
        // Otherwise let it paste normally as search text
    });

    // ─── Color ───────────────────────────────────────────────────────────────
    function setColor(hex) {
        colorValue.value = hex;
        colorPicker.value = hex;
        colorSwatch.style.backgroundColor = hex;
        colorHex.textContent = hex;

        colorPresets.forEach(btn => {
            const active = btn.dataset.color.toLowerCase() === hex.toLowerCase();
            btn.style.borderColor = active ? hex : 'transparent';
            btn.style.transform = active ? 'scale(1.15)' : '';
        });

        updatePreview();
    }

    colorPresets.forEach(btn => btn.addEventListener('click', () => setColor(btn.dataset.color)));
    colorPicker.addEventListener('input', () => setColor(colorPicker.value));

    // ─── Live preview ─────────────────────────────────────────────────────────
    function updatePreview() {
        const color = colorValue.value || '#3B82F6';
        const name = nameInput.value.trim() || 'Nama Kategori';
        const emoji = iconValue.value;
        const label = emoji || (name ? name.charAt(0).toUpperCase() : 'K');

        previewAvatar.style.backgroundColor = color;
        previewAvatar.textContent = label;
        previewAvatar.style.fontSize = emoji ? '1.25rem' : '';
        previewAvatar.style.color = emoji ? 'initial' : '#ffffff';
        previewName.textContent = name;
        previewBar.style.backgroundColor = color;
    }

    nameInput.addEventListener('input', updatePreview);

    // ─── Init ─────────────────────────────────────────────────────────────────
    setColor(colorValue.value || '#3B82F6');
    setIcon(iconValue.value || '');
    updatePreview();
});