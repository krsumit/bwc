@extends('layouts.master ')

@section('title', 'Show Test Articles')

@section('content')
    
    <div>
        <h2> {{ $article->title }} </h2>
        
        <article>
            <div class="body">{{ $article->body }}</div>
        </article>

    </div>
@stop      