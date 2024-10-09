@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $branch->name }}</h1>
    <p>Address: {{ $branch->address }}</p>
    <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit">Delete</button>
    </form>
    <a href="{{ route('branches.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
