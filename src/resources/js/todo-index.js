// resources/js/todo-index.js

function updateStatus(id, currentStatus) {
    var next = currentStatus === 'pending' ? 'active' :
        currentStatus === 'active' ? 'completed' :
        'pending';

    var token = document.querySelector('meta[name="csrf-token"]');

    if (!token) {
        console.error('CSRF token tidak ditemukan!');
        return;
    }

    fetch('/todo/status/' + id, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token.content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: next })
        })
        .then(function(res) {
            if (!res.ok) {
                return res.text().then(function(text) {
                    console.error('Server error:', res.status, text);
                    throw new Error('Server error ' + res.status);
                });
            }
            return res.json();
        })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                console.error('Gagal update status:', data);
            }
        })
        .catch(function(err) {
            console.error('Error:', err);
            alert('Gagal update status, coba lagi.');
        });
}

// Modal delete
function openDeleteModal(action, name) {
    document.getElementById('deleteForm').action = action;
    document.getElementById('modal-item-name').textContent = '"' + name + '"';
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Tambah di paling bawah file
window.updateStatus = updateStatus;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;