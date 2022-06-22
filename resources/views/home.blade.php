@extends('layouts.app')

@section('content')
<div class="content">
    <main>
        <kanban-board :initial-data="{{ $tasks }}"></kanban-board>
    </main>
</div>
@endsection