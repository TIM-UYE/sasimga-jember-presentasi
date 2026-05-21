@extends('frontend.layout.app')

@section('title', 'Home')

@section('content')

    @include('frontend.sections.hero')
    @include('frontend.sections.showcase')
    @include('frontend.sections.about')
    @include('frontend.sections.menu')
    @include('frontend.sections.gallery-orbit')
    @include('frontend.sections.reservasi')
    @include('frontend.sections.gallery')
    @include('frontend.sections.testimoni')

    <x-frontend.scroll-to-top />

@endsection
