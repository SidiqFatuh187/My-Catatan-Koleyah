<div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[1.5rem] shadow-xl w-full max-w-sm overflow-hidden transform transition-all">
        
    <form id="deleteForm" action="" method="POST">
            @csrf
            @method('DELETE')

            <div class="p-6 text-center">

                <div class="w-16 h-16 mx-auto bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mb-5 border-[6px] border-white shadow-sm shadow-rose-100">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>

                <h3 class="text-lg font-bold text-slate-900 tracking-tight mb-2">Hapus Kategori Ini?</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-8">
                    Kategori <span id="modal-category-name" class="font-semibold text-slate-700"></span> akan dihapus permanen dan tidak bisa dikembalikan.
                </p>
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-all active:scale-95 shadow-lg shadow-rose-200">
                        Ya, Hapus
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>