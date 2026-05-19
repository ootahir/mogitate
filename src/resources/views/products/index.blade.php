@extends('products.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="product-list">
  <aside class="product-list__sidebar">
    <h2 class="product-list__title">商品一覧</h2>
    <form id="search-form" class="search-form" method="GET" action="/products">
      <input
        type="text"
        class="search-form__input"
        name="keyword"
        placeholder="商品名で検索"
        value="{{ old('keyword', request('keyword')) }}"
      >
      <button type="submit" class="search-form__button">検索</button>
    </form>
    <div class="price-filter">
      <p class="price-filter__label">価格帯で表示</p>
      <select class="price-filter__select" name="sort" form="search-form" onchange="this.form.submit()">
        <option value="">価格順で表示</option>
        <option value="high" {{ request('sort') === 'high' ? 'selected' : '' }}>価格が高い順</option>
        <option value="low" {{ request('sort') === 'low' ? 'selected' : '' }}>価格が低い順</option>
      </select>
      @if(request('sort') === 'high' || request('sort') === 'low')
        <div class="price-filter__tag-wrap">
          <span class="price-filter__tag">
            {{ request('sort') === 'high' ? '高い順に表示' : '低い順に表示' }}
            <a href="/products" class="price-filter__tag-remove" aria-label="検索条件をリセット">×</a>
          </span>
        </div>
      @endif
    </div>
  </aside>

  <div class="product-list__main">
    <div class="product-list__header product-list__header--right">
      <a href="/products/register" class="product-list__add-btn">+ 商品を追加</a>
    </div>

    <div class="product-grid">
      @isset($products)
        @foreach($products as $product)
        <a href="/products/detail/{{ $product->id }}" class="product-card">
          <div class="product-card__image-wrapper">
            <img class="product-card__image" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
          </div>
          <div class="product-card__info">
            <span class="product-card__name">{{ $product->name }}</span>
            <span class="product-card__price">¥{{ number_format($product->price) }}</span>
          </div>
        </a>
        @endforeach
      @endisset
    </div>

    <div class="pagination">
      @isset($products)
        {{ $products->links('pagination::custom') }}
      @endisset
    </div>
  </div>
</div>
@endsection
