@extends('blog::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {{ __('blogs::blogs.name') }}</p>
@endsection
