<script>
    @if (\Session::has('success'))
        $.notify("{!! \Session::get('success') !!}", {position:'bottom-right', className: 'success'});
    @endif
    
    @if (\Session::has('error'))
        $.notify("{!! \Session::get('error') !!}", {position:'bottom-right', className: 'error'});
    @endif
</script>