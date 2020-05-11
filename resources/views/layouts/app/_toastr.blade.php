@if (Session::has('warning'))
<script>
  toastr.warning('{{ Session::get('warning') }}', 'Alerta');
</script>
@endif

@if (Session::has('info'))
<script>
  toastr.info('{{ Session::get('info') }}', 'Informação');
</script>
@endif

@if (Session::has('success'))
<script>
  toastr.success('{{ Session::get('success') }}', 'Sucesso');
</script>
@endif

@if (Session::has('error'))
<script>
  toastr.error('{{ Session::get('error') }}', 'Erro');
</script>
@endif

@if (count($errors) > 0)
  @if (count($errors) == 1)
  <script>
    toastr.error('{{ $errors->first() }}', 'Erro');
  </script>
  @else
    @foreach ($errors->all() as $error)
    <script>
      toastr.error('{{ $error }}', 'Erro');
    </script>
    @endforeach
  @endif
@endif
