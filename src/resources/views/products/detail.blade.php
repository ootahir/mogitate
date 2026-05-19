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
		<h1 class="detail-page__title">商品：{{ $product->name }}</h1>

		<form class="detail-form" action="/products/{{ $product->id }}/update" method="POST" enctype="multipart/form-data">
			@csrf
			@method('PATCH')

			<div class="detail-form__container">
				@php
					$selectedSeasonIds = old('season_ids', $product->seasons->pluck('id')->all());
				@endphp

				<!-- 左側：画像 -->
				<div class="detail-form__image-section">
					<!-- 現在の画像 -->
					<div id="imagePreview" class="detail-form__image-preview {{ !$product->image ? 'is-hidden' : '' }}">
						@if($product->image)
							<img id="previewImage" class="detail-form__image-preview-img" src="{{ Storage::url($product->image) }}" alt="商品画像">
						@else
							<img id="previewImage" class="detail-form__image-preview-img" src="" alt="プレビュー">
						@endif
						<p class="detail-form__image-filename">{{ $product->image ? basename($product->image) : '' }}</p>
					</div>

					<!-- ファイル選択 -->
					<div class="detail-form__file-wrapper">
						<input id="image" type="file" name="image" class="detail-form__file-input" accept="image/jpeg,image/png">
						<label for="image" class="detail-form__file-button">ファイルを選択</label>
					</div>

					@error('image')
						<p class="detail-form__error-message">{{ $message }}</p>
					@enderror
				</div>

				<!-- 右側：フォーム -->
				<div class="detail-form__content-section">
					<!-- 商品名 -->
					<div class="detail-form__group">
						<label class="detail-form__label-row" for="name">
							<span>商品名</span>
							<span class="detail-form__badge">必須</span>
						</label>
						<input id="name" class="detail-form__input" type="text" name="name" value="{{ old('name', $product->name) }}">
						@error('name')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>

					<!-- 値段 -->
					<div class="detail-form__group">
						<label class="detail-form__label-row" for="price">
							<span>値段</span>
							<span class="detail-form__badge">必須</span>
						</label>
						<input id="price" class="detail-form__input" type="text" name="price" value="{{ old('price', $product->price) }}">
						@error('price')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>

					<!-- 季節 -->
					<div class="detail-form__group">
						<label class="detail-form__label-row">
							<span>季節</span>
							<span class="detail-form__badge">必須</span>
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

					<!-- 商品説明 -->
					<div class="detail-form__group">
						<label class="detail-form__label-row" for="description">
							<span>商品説明</span>
							<span class="detail-form__badge">必須</span>
						</label>
						<textarea id="description" class="detail-form__textarea" name="description">{{ old('description', $product->description) }}</textarea>
						@error('description')
							<p class="detail-form__error-message">{{ $message }}</p>
						@enderror
					</div>
				</div>
			</div>

			<!-- ボタン -->
			<div class="detail-form__actions">
				<button type="button" class="detail-form__button detail-form__button--back" onclick="location.href='/products'">戻る</button>
				<button type="submit" class="detail-form__button detail-form__button--save">変更を保存</button>
				<button type="button" class="detail-form__button detail-form__button--delete" onclick="if(confirm('この商品を削除してもよろしいですか？')) { document.getElementById('deleteForm').submit(); }">削除</button>
			</div>
		</form>

		<!-- 削除フォーム -->
		<form id="deleteForm" action="/products/detail/{{ $product->id }}" method="POST" style="display:none;">
			@csrf
			@method('DELETE')
		</form>
	</div>
</div>
@endsection
