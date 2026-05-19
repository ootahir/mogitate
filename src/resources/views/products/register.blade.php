@extends('products.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
	const imageInput = document.getElementById('image');
	const previewContainer = document.getElementById('imagePreview');
	const previewImage = document.getElementById('previewImage');
	const filenameDisplay = document.querySelector('.register-form__image-filename');

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
<div class="register-page">
	<div class="register-page__inner">
		<h1 class="register-page__title">商品登録</h1>

		<form class="register-form" action="/products/register" method="POST" enctype="multipart/form-data">
			@csrf

			<div class="register-form__group">
				<label class="register-form__label-row" for="name">
					<span>商品名</span>
					<span class="register-form__badge">必須</span>
				</label>
				<input id="name" class="register-form__input" type="text" name="name" value="{{ old('name') }}" placeholder="必須入力">
				@error('name')
					<p class="register-form__error-message">{{ $message }}</p>
				@enderror
			</div>

			<div class="register-form__group">
				<label class="register-form__label-row" for="price">
					<span>値段</span>
					<span class="register-form__badge">必須</span>
				</label>
				<input id="price" class="register-form__input" type="text" name="price" value="{{ old('price') }}" placeholder="必須入力">
				@error('price')
					<p class="register-form__error-message">{{ $message }}</p>
				@enderror
			</div>

			<div class="register-form__group">
				<label class="register-form__label-row">
					<span>商品画像</span>
					<span class="register-form__badge">必須</span>
				</label>
				<div class="register-form__file-wrapper">
					<input id="image" type="file" name="image" class="register-form__file-input" accept="image/jpeg,image/png">
					<label for="image" class="register-form__file-button">ファイルを選択</label>
				</div>
				
				<!-- プレビューコンテナ -->
				<div id="imagePreview" class="register-form__image-preview is-hidden">
					<img id="previewImage" class="register-form__image-preview-img" src="" alt="プレビュー">
					<p class="register-form__image-filename"></p>
				</div>
				
				@error('image')
					<p class="register-form__error-message">{{ $message }}</p>
				@enderror
			</div>

			<div class="register-form__group">
				<label class="register-form__label-row">
					<span>季節</span>
					<span class="register-form__badge">必須</span>
					<span class="register-form__season-label">対象会期中</span>
				</label>
				<div class="register-form__season-list">
					@foreach($seasons as $season)
						<label class="register-form__season-item">
							<input type="checkbox" name="season_ids[]" value="{{ $season->id }}" {{ in_array($season->id, old('season_ids', []), true) ? 'checked' : '' }}>
							<span>{{ $season->name }}</span>
						</label>
					@endforeach
				</div>
				@error('season_ids')
					<p class="register-form__error-message">{{ $message }}</p>
				@enderror
			</div>

			<div class="register-form__group">
				<label class="register-form__label-row" for="description">
					<span>商品説明</span>
					<span class="register-form__badge">必須</span>
				</label>
				<textarea id="description" class="register-form__textarea" name="description" placeholder="商品説明を入力">{{ old('description') }}</textarea>
				@error('description')
					<p class="register-form__error-message">{{ $message }}</p>
				@enderror
			</div>

			<div class="register-form__actions">
				<button type="button" class="register-form__button register-form__button--back" onclick="location.href='/products'">戻る</button>
				<button type="submit" class="register-form__button register-form__button--save">登録</button>
			</div>
		</form>
	</div>
</div>
@endsection
