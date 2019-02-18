<?php 
/*
 * In this file goes everything from dashboard-sidebar.php
 * this needs to have validation of the type of user in session
 * this is done with an if as the one presented below
 * the three types of user are internal(admin) buyer and seller
 */
?>
@if(Auth::user()->type=='internal')

@endif