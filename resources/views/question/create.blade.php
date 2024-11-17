@extends('parts.master')
@section('title', 'تعریف سوال')
@section('styles')
<style>
    .ck-editor__editable {
        height: 180px;
    }
</style>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                تعریف سوال
                            </h3>
                        </div>
                        <form action="{{ route('question.store') }}" method="POST">
                            <div class="card-body">
                                @csrf
                                <input type="hidden" name="item" value="{{$item?->id}}">
                                @if ($errors->any())
                                    @foreach($errors->all() as $error)
                                        <div class="row">
                                            <div class="col-md-12 alert alert-danger">
                                                {{ $error }}
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">بخش</label>
                                        <select name="component" class="form-control" required>
                                            <option value="">انتخاب کنید...</option>
                                            @foreach ($components as $component)
                                                <option @selected($component->id==$item?->component_id) value="{{ $component->id }}">{{ $component->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label required">عنوان <span class="text-danger">*</span></label>
                                        <input required type="text" name="title" class="form-control" placeholder="عنوان..." value="{{ old("title")??$item?->title }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>متن</label>
                                        <textarea name="body" class="form-control Reditor1" placeholder="متن...">{{ old("body")??$item?->body }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="reset" class="btn btn-secondary">انصراف</button>
                                        {!!
                                            $item?'<button type="submit" class="btn btn-success">ویرایش</button>'
                                            :'<button type="submit" class="btn btn-success">ذخیره</button>'
                                        !!}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
<script src="{{ asset('js/ckeditor.js') }}"></script>
<script>
  ClassicEditor.create(document.querySelector('.Reditor1'), {
            toolbar: {
                items: [
                    'undo', 'redo',
                    '|', 'heading',
                    '|', 'bold', 'italic',
                    '|', 'link', 'insertImage', 'insertTable', 'mediaEmbed',
                    '|', 'bulletedList', 'numberedList'
                ]
            },
            language: {
                // The UI will be Arabic.
                ui: 'fa',
                // And the content will be edited in Arabic.
                content: 'fa'
            }
        }).catch(error => {
            console.error(error);
        });
</script>
@endsection
