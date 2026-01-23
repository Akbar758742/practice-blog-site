@extends('backend.layout.pages-layout')
@section('pageTitle', isset($title) ? $title : 'page title herexx')

@section('content')
    <livewire:admin.dashboard />
@endsection