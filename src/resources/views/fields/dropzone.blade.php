@php
  if (empty($field['config']['url'])) {
      $field['config']['url'] = route('dropzone');
  }
  if (!empty($field['value']) && is_string($field['value'])) {
      $field['value'] = [$field['value']];
  }
  if (!isset($field['allow_multiple'])) {
      $field['allow_multiple'] = true;
  }

  $id = 'dz-' . Illuminate\Support\Str::random();
@endphp

@include('crud::fields.inc.wrapper_start')
  <label>{!! $field['label'] !!}</label>
  @include('crud::fields.inc.translatable_icon')

  <div
    id="{{ $id }}"
    class="dropzone"
    data-init-function="bpFieldInitDropzoneElement"
    data-config='@json($field['config'] ?? [], JSON_FORCE_OBJECT)'
    data-allow-multiple='@json(!!$field['allow_multiple'])'
  >
    <input
      @if(!empty($field['value'])) data-value='@json($field['value'])' @endif
      type="hidden"
      data-name="{{ $field['name'] }}"
      style="position:absolute; height: 1px; width: 1px; opacity: 0;"
    />
    <div data-input-list></div>
  </div>

  {{-- HINT --}}
  @if(isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

@if($crud->fieldTypeNotLoaded($field))
  @php
    $crud->markFieldTypeAsLoaded($field);
  @endphp

  @push('crud_fields_styles')
    <link href="{{ asset('packages/dropzone/css/dropzone.css') }}" rel="stylesheet" type="text/css"/>
  @endpush

  @push('crud_fields_scripts')
    <script src="{{ asset('packages/dropzone/js/dropzone.js') }}"></script>
  @endpush
@endif
