@extends('layouts.app')

@section('content')

<div class="dashboard">

  <!-- NAVBAR -->
  <div class="navbar">
    <div class="logo">SweetBloom</div>
    <div class="nav-links">
      <a href="#">Home</a>
      <a href="#">Orders</a>
      <a href="#">Profile</a>
    </div>
  </div>

  <!-- HERO -->
  <div class="hero">
    <div class="hero-text">
      <h1>Freshly Baked Happiness 🍓</h1>
      <p>Kue handmade dengan cinta untuk momen spesialmu</p>
      <button>Explore Menu</button>
    </div>
  </div>

  <!-- SECTION TITLE -->
  <div class="section-title">
    <h2>✨ Best Seller Cakes</h2>
  </div>

  <!-- PRODUCT -->
  <div class="product-grid">

    <div class="product-card">
      <div class="product-img">🍓</div>
      <h3>Strawberry Bliss</h3>
      <span>Rp 120.000</span>
    </div>

    <div class="product-card">
      <div class="product-img">🍫</div>
      <h3>Choco Heaven</h3>
      <span>Rp 95.000</span>
    </div>

    <div class="product-card">
      <div class="product-img">🎂</div>
      <h3>Birthday Special</h3>
      <span>Rp 200.000</span>
    </div>

  </div>

</div>

@endsection