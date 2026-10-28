@if ($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert" aria-live="polite">
        <button type="button" class="close" data-dismiss="alert" aria-label="بستن">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul class="mb-0 pr-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

