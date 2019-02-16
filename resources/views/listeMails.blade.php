@extends('header')
@section('contenu')

  
<mail-component 
    :les-mails = "{{ json_encode( $lesMails )}}"
    :liste-client = "{{ json_encode( $listeClient )}}"
></mail-component>
    

@endsection
