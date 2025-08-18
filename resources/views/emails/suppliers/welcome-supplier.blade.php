@extends('beautymail::templates.ark')
@section('content')
@include('beautymail::templates.ark.heading', [
        'heading' => 'Welcome',
        'level' => 'h2'
    ])
@include('beautymail::templates.ark.contentStart')
<h4 class="secondary"><strong>We are happy to deal with you.</strong></h4>
        <p>Thanks</p>
@include('beautymail::templates.ark.contentEnd')
@stop