@extends('frontend.layout.app')

@section('content')

    @include('frontend.menu.section.hero')

    @include('frontend.menu.section.search-filter')

    @include('frontend.menu.section.regular-menu')

    @include('frontend.menu.section.special-menu')

    @include('frontend.menu.section.regular-modal')

    @include('frontend.menu.section.special-modal')

    @include('frontend.menu.section.floating-checkout')

    @include('frontend.menu.section.scripts')

@endsection
