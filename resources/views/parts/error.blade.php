@if ($errors->any())
    @foreach($errors->all() as $error)
        <div class="row">
            <div class="col-md-12 alert alert-danger">
                {{ $error }}
            </div>
        </div>
    @endforeach
@endif
