@php
  if (empty($field['config']['url'])) {
    $field['config']['url'] = route('dropzone');
  }
@endphp

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

@if(!empty($field['value']))
  <p>
    <a href="{{ $field['value'] }}" target="_blank">{{ $field['open_label'] ?? 'Open the file' }}</a>
  </p>
@endif

<div
  id="dz-{{ Illuminate\Support\Str::random() }}"
  class="dropzone"
  data-init-function="bpFieldInitDropzoneElement"
  data-config='@json($field['config'] ?? [], JSON_FORCE_OBJECT)'
>
  <input
    type="text"
    name="{{ $field['name'] }}"
    style="position:absolute; height: 1px; width: 1px; opacity: 0;"
  />
</div>

{{-- HINT --}}
@if(isset($field['hint']))
  <p class="help-block">{!! $field['hint'] !!}</p>
  @endif
  </div>

  @if($crud->fieldTypeNotLoaded($field))
    @php
      $crud->markFieldTypeAsLoaded($field);
    @endphp

    @push('crud_fields_styles')
      <link href="{{ asset('vendor/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css"/>
    @endpush

    @push('crud_fields_scripts')
      <script src="{{ asset('vendor/dropzone/js/dropzone.js') }}"></script>
    @endpush
  @endif
