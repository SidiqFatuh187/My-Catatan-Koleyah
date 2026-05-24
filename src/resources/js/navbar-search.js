// resources/js/navbar-search.js

var searchInput = document.getElementById('navbar-search');
var searchDropdown = document.getElementById('search-dropdown');
var searchResults = document.getElementById('search-results');
var searchTimer = null;

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        var query = this.value.trim();

        if (query.length < 1) {
            searchDropdown.classList.add('hidden');
            return;
        }

        searchTimer = setTimeout(function() {
            fetch('/todo/search?q=' + encodeURIComponent(query), {
                    headers: { 'Accept': 'application/json' }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.length === 0) {
                        searchResults.innerHTML = '<p class="text-xs text-gray-400 text-center py-3 px-4">Tidak ada hasil</p>';
                    } else {
                        searchResults.innerHTML = data.map(function(todo) {
                            var statusColor = todo.status === 'completed' ? '#22c55e' :
                                todo.status === 'active' ? '#3b82f6' :
                                '#f59e0b';

                            return '<a href="' + todo.url + '" class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">' +
                                '<div class="w-2 h-2 rounded-full shrink-0" style="background-color:' + statusColor + '"></div>' +
                                '<div class="flex-1 min-w-0">' +
                                '<p class="text-sm text-gray-700 truncate font-medium">' + todo.title + '</p>' +
                                (todo.category ? '<p class="text-xs text-gray-400 flex items-center gap-1 mt-0.5"><span class="w-1.5 h-1.5 rounded-full inline-block" style="background-color:' + todo.color + '"></span>' + todo.category + '</p>' : '') +
                                '</div>' +
                                '<span class="text-xs text-gray-400 shrink-0">' + todo.priority + '</span>' +
                                '</a>';
                        }).join('');
                    }

                    searchDropdown.classList.remove('hidden');
                });
        }, 300);
    });

    // Tutup dropdown kalau klik di luar
    document.addEventListener('click', function(e) {
        if (!document.getElementById('search-wrapper').contains(e.target)) {
            searchDropdown.classList.add('hidden');
        }
    });

    // Navigasi pakai keyboard
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchDropdown.classList.add('hidden');
            this.blur();
        }
    });
}