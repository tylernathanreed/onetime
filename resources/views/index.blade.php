@extends('layout')

@section('content')
    <form action="{{ route('store') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="secret">What's your secret?</label>
            <textarea id="secret" name="secret" rows="5" class="form-control" maxlength="{{ app('secrets')->byteLimit() }}" required></textarea>
        </div>
        <div class="form-group">
            <label for="expires">When should it expire?</label>
            <select id="expires" name="expires" class="form-control" required>
                <option value="+1 hour">In 1 Hour</option>
                <option value="+2 hours">In 2 Hours</option>
                <option value="+4 hours">In 4 Hours</option>
                <option value="+12 hours">In 12 Hours</option>
                <option value="+1 day">In 1 Day</option>
                <option value="+2 days">In 2 Days</option>
                <option value="+3 days">In 3 Days</option>
                <option value="">Never</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            @icon('check') Get your Secret URL
        </button>
    </form>
@endsection
