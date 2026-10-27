@php
    /** @var \App\Models\Product $product */
@endphp

<div class="grid gap-5 lg:grid-cols-[1fr_20rem]">
    <div class="admin-card p-5 sm:p-6">
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="admin-label">Nama Produk <span class="text-[#b42318]">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                       class="admin-input @error('name') has-error @enderror" required maxlength="150">
                @error('name') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sku" class="admin-label">SKU / Kode Produk <span class="text-[#b42318]">*</span></label>
                <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                       class="admin-input font-mono @error('sku') has-error @enderror" required maxlength="60">
                @error('sku') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="admin-label">Kategori <span class="text-[#b42318]">*</span></label>
                <select name="category_id" id="category_id" class="admin-input @error('category_id') has-error @enderror" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price" class="admin-label">Harga (Rp) <span class="text-[#b42318]">*</span></label>
                <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                       class="admin-input @error('price') has-error @enderror" required min="0" step="1" inputmode="numeric">
                @error('price') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stock" class="admin-label">Stok <span class="text-[#b42318]">*</span></label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                       class="admin-input @error('stock') has-error @enderror" required min="0" step="1" inputmode="numeric">
                @error('stock') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-espresso/45">Stok &le; {{ \App\Models\Product::LOW_STOCK_THRESHOLD }} ditandai sebagai stok menipis.</p>
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="admin-label">Deskripsi</label>
                <textarea name="description" id="description" rows="4" maxlength="2000"
                          class="admin-input resize-y @error('description') has-error @enderror">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <input type="hidden" name="is_active" value="0">
                <label for="is_active" class="flex cursor-pointer items-center gap-2.5 text-sm font-medium text-espresso">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           class="h-4 w-4 rounded border-softgray text-caramel focus:ring-caramel"
                           @checked(old('is_active', $product->is_active))>
                    Produk aktif dan dapat dijual
                </label>
            </div>
        </div>
    </div>

    <div class="admin-card h-fit p-5">
        <label for="image" class="admin-label">Foto Produk</label>

        <div class="mt-1 flex aspect-square items-center justify-center overflow-hidden rounded-lg border border-dashed border-softgray bg-cream/60" id="image-preview-wrap">
            <img id="image-preview"
                 src="{{ $product->imageUrl() ?? '' }}"
                 alt="Pratinjau foto produk"
                 class="h-full w-full object-cover {{ $product->imageUrl() ? '' : 'hidden' }}">
            <div id="image-placeholder" class="flex flex-col items-center gap-1.5 px-4 text-center {{ $product->imageUrl() ? 'hidden' : '' }}">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" class="text-espresso/30" aria-hidden="true">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>
                </svg>
                <span class="text-xs text-espresso/45">Belum ada foto</span>
            </div>
        </div>

        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
               class="mt-3 block w-full text-xs text-espresso/70 file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:bg-cream file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-espresso hover:file:bg-softgray @error('image') has-error @enderror">
        <p class="mt-1.5 text-xs text-espresso/45">JPG, PNG, atau WebP. Maks. 2 MB. {{ $product->exists ? 'Biarkan kosong untuk mempertahankan foto lama.' : '' }}</p>
        @error('image') <p class="mt-1 text-xs text-[#b42318]">{{ $message }}</p> @enderror
    </div>
</div>

@push('scripts')
<script>
    (() => {
        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const placeholder = document.getElementById('image-placeholder');
        if (!input || !preview) return;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.classList.remove('hidden');
            placeholder?.classList.add('hidden');
        });
    })();
</script>
@endpush
