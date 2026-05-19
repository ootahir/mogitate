@extends('products.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
	const imageInput = document.getElementById('image');
	const previewContainer = document.getElementById('imagePreview');
	const previewImage = document.getElementById('previewImage');
	const filenameDisplay = document.querySelector('.detail-form__image-filename');

	if (!imageInput || !previewContainer || !previewImage || !filenameDisplay) {
		return;
	}

	imageInput.addEventListener('change', function(event) {
		const file = event.target.files[0];

		if (file) {
			filenameDisplay.textContent = file.name;

			const reader = new FileReader();
			reader.onload = function(e) {
				previewImage.src = e.target.result;
				previewContainer.classList.remove('is-hidden');
			};
			reader.readAsDataURL(file);
		} else {
			previewContainer.classList.add('is-hidden');
			previewImage.src = '';
			filenameDisplay.textContent = '';
		}
	});
});
</script>
@endsection

@section('content')
<div class="detail-page">
	<div class="detail-page__inner">
		<p class="detail-page__breadcrumb"><a href="/products">商品一覧</a> &gt; {{ $product->name }}</p>

		<form class="detail-form" action="/products/{{ $product->id }}/update" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PATCH')

			<div class="detail-form__container">
				@php
					$selectedSeasonIds = old('season_ids', $product->seasons->pluck('id')->all());
				@endphp

				<div class="detail-form__image-section">
					<div id="imagePreview" class="detail-form__image-preview {{ !$product->image ? 'is-hidden' : '' }}">
						@if($product->image)
							<img id="previewImage" class="detail-form__image-preview-img" src="{{ Storage::url($product->image) }}" alt="商品画像">
						@else
							<img id="previewImage" class="detail-form__image-preview-img" src="" alt="プレビュー">
						@endif
						<p class="detail-form__image-filename">{{ $product->image ? basename($product->image) : '' }}</p>
					</div>

					<div class="detail-form__file-wrapper">
						<input id="image" type="file" name="image" class="detail-form__file-input" accept="image/jpeg,image/png">
						<label for="image" class="detail-form__file-button">ファイルを選択</label>
					</div>

					@error('image')
						<p class="detail-form__error-message">{{ $message }}</p>
					@enderror
				</div>

				<div class="detail-form__content-section">
					<div class="detail-form__group">
						<label class="detail-form__label-row" for="name">
							<span>商品名</span>
						</label>
						<input id="name" class="detail-form__input" type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="商品名を入力">
						@error('name')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>

					<div class="detail-form__group">
						<label class="detail-form__label-row" for="price">
							<span>値段</span>
						</label>
						<input id="price" class="detail-form__input" type="text" name="price" value="{{ old('price', $product->price) }}" placeholder="値段を入力">
						@error('price')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>

					<div class="detail-form__group">
						<label class="detail-form__label-row">
							<span>季節</span>
						</label>
						<div class="detail-form__season-list">
							@foreach($seasons as $season)
								<label class="detail-form__season-item">
									<input type="checkbox" name="season_ids[]" value="{{ $season->id }}" {{ in_array($season->id, $selectedSeasonIds, true) ? 'checked' : '' }}>
									<span>{{ $season->name }}</span>
								</label>
							@endforeach
						</div>
						@error('season_ids')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>

					<div class="detail-form__group">
						<label class="detail-form__label-row" for="description">
							<span>商品説明</span>
						</label>
						<textarea id="description" class="detail-form__textarea" name="description" placeholder="商品の説明を入力">{{ old('description', $product->description) }}</textarea>
						@error('description')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>
				</div>
			</div>

			<div class="detail-form__actions">
				<button type="button" class="detail-form__button detail-form__button--back" onclick="location.href='/products/detail/{{ $product->id }}'">戻る</button>
				<button type="submit" class="detail-form__button detail-form__button--save">変更を保存</button>
			</div>
		</form>
	</div>
</div>
@endsection
