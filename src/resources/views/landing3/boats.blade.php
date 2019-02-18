<?php
/*
 * In @section('content') goes all that's inside
 * .dashboard-content-container
 * (Lines 30->166 dash-list-barcos)
 * In the foreach goes each ship container with all the actions and data
 * 
 */
?>
@extends('landing3/partials/layout-admin')
@section('title',' | Mis Barcos')
@section('content')

@foreach($boats as $boat)
<?php echo 'Boat <br>';var_dump($boat);echo '<br>'?>
@endforeach


@endsection
