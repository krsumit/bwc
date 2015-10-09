@extends('layouts.master')

@section('content')
    
    <h1> {{ $articles->title }} </h1>
        
    <articles>
        {{ $articles->body }}
    </articles>

@stop